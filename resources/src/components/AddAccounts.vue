<template>
    <Header></Header>
    <div>
        <h1>Add accounts</h1>
    </div>
    <div class="login"></div>
    <form @submit.prevent="onSubmit">
        <div class="mb-3">
            <input type="text" name="name" id="" placeholder="Enter account name" v-model="account.name">
        </div>
        <div class="mb-3">
            <input type="text" name="customer" placeholder="Enter customer name" v-model="account.customer">    
        </div>
        <div class="mb-3">
            <input type="text" name="responsible" placeholder="Enter responsible ID" v-model="account.responsible">    
        </div>
        <div class="mb-3">
            <button v-on:click="addAccount" class="btn btn-primary">Add new account</button>
        </div>
    </form>
</template>

<script>
import Header from './Header.vue'
import axios from 'axios'
export default {
    name: 'AddAccounts',
    components:{
        Header
    },
    data() 
    {
        return {
            account :{
                name: '',
                customer: '',
                responsible: '',
            }
        }
    },
    methods: {
        addAccount() {
            let config = {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            };

            const result = axios.post('/api/accounts/create', {
                name: this.account.name,
                customer: this.account.customer,
                responsible: this.account.responsible,
            }, config).then(r => {
                if (r.status == 200 && r.data.status == "success") {
                    this.$router.push({name:'Home'})
                }
            }).catch(error => {
                console.log(error);
            })
        }
    },
    mounted()
    {
        let user = localStorage.getItem('user-info')
        if (!user) {
            this.$router.push({name:'Login'})
        }
    }
}
</script>
