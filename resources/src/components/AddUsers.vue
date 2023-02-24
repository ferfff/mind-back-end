<template>
    <Header></Header>
    <h1>Add Users</h1>
    <div class="container">
        <div class="row">
            <section class="col-md-6">
                <div class="login">
                    <form @submit.prevent="onSubmit">
                        <div class="mb-3">
                            <input type="text" name="name" id="" placeholder="Name" v-model="user.name">
                        </div>
                        <div class="mb-3">
                            <input type="email"  name="email" id="" placeholder="Enter email" v-model="user.email">
                        </div>
                        <div class="mb-3">
                            <label for="english_level">English Level</label>
                            <select class="form-control" id="english_level" v-model="user.english_level">
                                <option value="A1" selected>A1</option>
                                <option value="A2">A2</option>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea name="knowledge" id="" cols="30" rows="3" v-model="user.knowledge" style="height:100%;"></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="link_cv" id="" placeholder="Link to CV" v-model="user.link_cv">
                        </div>
                        <div class="mb-3">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" v-model="user.role">
                                <option value="normal" selected>normal</option>
                                <option value="admin">admin</option>
                                <option value="superadmin">superadmin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" id="" placeholder="Password" v-model="user.password">
                        </div>
                        <div class="mb-3">
                            <button v-on:click="addUser" class="btn btn-primary">Add User</button>
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
    name: 'AddUsers',
    components:{
        Header
    },
    data() 
    {
        return {
            user :[]
        }
    },
    methods: {
        async addUser() {
            let config = {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            };

            await axios.post('/api/register', {
                name: this.user.name,
                email: this.user.email,
                password: this.user.password,
                english_level: this.user.english_level,
                knowledge: this.user.knowledge,
                link_cv: this.user.link_cv,
                role: this.user.role,
            }, config).then(r => {
                if (r.status == 200 && r.data.status == "success") {
                    this.$router.push({name:'Users'})
                }
            }).catch(error => {
                console.log(this.user.english_level)
                console.log(error);
            })
        }
    },
}
</script>
