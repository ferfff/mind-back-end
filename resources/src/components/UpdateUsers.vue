<template>
    <Header></Header>
    <h1>Update Users</h1>
    <div class="container">
        <div class="row">
            <section class="col-md-6">
                <div class="login">
                    <form @submit.prevent="onSubmit">
                        <div class="mb-3">
                            <input type="text" name="name" id="name" placeholder="Name" v-model="user.name">
                        </div>
                        <div class="mb-3">
                            <input type="email"  name="email" id="email" placeholder="Enter email" v-model="user.email">
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" id="password" placeholder="New password" v-model="user.password">
                        </div>
                        <div class="mb-3">
                            <button v-on:click="updateUser" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import Header from './Header.vue'
import axios from 'axios'
export default {
    name: 'UpdateUsers',
    components:{
        Header
    },
    data() 
    {
        return {
            user :[],
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
        async updateUser()
        {
            await axios.put('/api/users/update/' + this.$route.params.id, {
                name: this.user.name,
                email: this.user.email,
                password: this.user.password,
            }, this.config).then(r => {
                if (r.status == 200 && r.data.status == "success") {
                    this.$router.push({name:'Users'})
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
        await axios.get('/api/users/show/'+this.$route.params.id, this.config).then(r => {
            this.user = r.data.user;
            console.log(this.user);
        }).catch(error => {console.log(error)});
    }
}
</script>
