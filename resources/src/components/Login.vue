<template>
    <section>
        <h1>Login</h1>
        <hr>
        <h3>Mind Front end</h3>
    </section>
    <section>
        <div class="login">
            <div class="mb-3">
                <label for="email">Email :</label>
                <input id="email" type="text" v-model="email" placeholder="Enter email" />
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="password">Password :</label>
                <input id="password" type="password" v-model="password" placeholder="Enter password" password-reveal />
            </div>
            <div class="mb-3">
                <button v-on:click="login" class="btn btn-primary">Login</button>
            </div>
        </div>
    </section>
</template>

<script>
import axios from 'axios'
export default {
    name: 'Login',
    data()
    {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        async login()
        {
            await axios.post("http://localhost/api/login", {
                email: this.email,
                password: this.password
            }).then(response => {
                if (response.status == 200 && response.data.status == "success") {
                    let token = response.data.authorization.token
                    localStorage.setItem("user-info", JSON.stringify(response.data))
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    localStorage.setItem('authtoken', token);
                    this.$router.push({name:'Home'})
                }
            }).catch(error => console.log(error))
        }
    },
    mounted()
    {
        let user= localStorage.getItem('user-info')
        if (user) {
            this.$router.push({name:'Home'})
        }
    }
}
</script>

<style>

.login div {
    margin-left: auto;
    margin-right: auto;
    width: 350px;
}
</style>
