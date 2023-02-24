<template>
    <Header></Header>
    <h1>Update Accounts</h1>
    <div class="login">
        <form @submit.prevent="onSubmit">
            <div class="mb-3">
                <input type="text" name="name" placeholder="Enter account name" v-model="account.name">
            </div>
            <div class="mb-3">
                <input type="text" name="customer" placeholder="Enter customer name" v-model="account.customer">        
            </div>
            <div class="mb-3">
                <input type="text" name="responsible" placeholder="Enter responsible ID" v-model="account.responsible.id">
            </div>
            <div class="mb-3">
                <button v-on:click="updateAccount" class="btn btn-primary">Update account</button>
            </div>
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
            },
            config : {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            },
        }
    },
    methods: {
        async updateAccount()
        {
            await axios.put('/api/accounts/update/' + this.$route.params.id, {
                name: this.account.name,
                customer: this.account.customer,
                responsible: this.account.responsible.id,
            }, this.config).then(r => {
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
        await axios.get('/api/accounts/show/'+this.$route.params.id, this.config).then(r => {
            this.account = r.data.accounts;
            this.account.responsible = r.data.accounts.responsible[0];
        }).catch(error => {console.log(error)});
    }
}
</script>
