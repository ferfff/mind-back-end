<template>
    <section>
        <h1>Login</h1>
        <hr>
        <h3>Mind Vue 3</h3>
    </section>
    <section>
        <div class="login">
            <input type="text" v-model="email" placeholder="Enter email" />
            <input type="password" v-model="password" placeholder="Enter password" password-reveal />
            <button v-on:click="login">Login</button>
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