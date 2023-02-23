<template>
    <div class="nav">
        <router-link to="/">Home</router-link>
        <router-link to="/login">Login</router-link>
        <router-link to="/users">Users</router-link>
        <a v-on:click="logout" href="#">Logout</a>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    name: 'Header',
    methods:{
        async logout()
        {
            let config = {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            };
            await axios.post("http://localhost/api/logout", {}, config).then((r) => {
                localStorage.clear();
                this.$router.push({name:'Login'})
            }).catch(error => {
                console.log(error);
                localStorage.clear();
            })
        }
    }
}
</script>

<style>
.nav {
    background-color: #333;
    overflow: hidden;
}
.nav a {
    float: left;
    color: whitesmoke;
    padding: 14px 16px;
    text-align: center;
    font-size: 17px;
    text-decoration: none;
    margin-right: 5px;
}
.nav a:hover {
    background: #ddd;
    color: #333;
}
</style>