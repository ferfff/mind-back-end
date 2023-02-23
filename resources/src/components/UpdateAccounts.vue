<template>
    <Header></Header>
    <h1>Update Accounts</h1>
    <div>
        <form @submit.prevent="onSubmit" class="add">
        <input type="text" name="name" id="" placeholder="Enter account name" v-model="account.name">
        <input type="text" name="customer" placeholder="Enter customer name" v-model="account.customer">
        <input type="text" name="responsible" placeholder="Enter responsible ID" v-model="account.responsible.id">
        <button v-on:click="updateAccount">Update account</button>
    </form>
    </div>
    
</template>

<script>
import Header from './Header.vue'
import axios from 'axios'
export default {
    name: 'UpdateAccounts',
    components:{
        Header
    },
    data() 
    {
        return {
            account :{
                name: '',
                customer: '',
                responsible: { id:'', name:''},
            }
        }
    },
    methods: {
        async updateAccount()
        {
            let config = {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            };

            axios.put('/api/accounts/update/' + this.$route.params.id, {
                name: this.account.name,
                customer: this.account.customer,
                responsible: this.account.responsible.id,
            }, config).then(r => {
                if (r.status == 200 && r.data.status == "success") {
                    this.$router.push({name:'Home'})
                }
            }).catch(error => {
                console.log(error);
            })
        }
    },
    async mounted()
    {
        let user= localStorage.getItem('user-info')
        if (!user) {
            this.$router.push({name:'Login'})
        }
        let config = {
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: `Bearer ${localStorage.getItem('authtoken')}`
            }
        }
        await axios.get('/api/accounts/show/'+this.$route.params.id, config).then(r => {
            this.account = r.data.accounts;
            this.account.responsible = r.data.accounts.responsible[0];
        }).catch(error => {console.log(error)});
        //await axios.put('/accounts/update', {}, config)
        
    }
}
</script>
