<template>
    <Header></Header>
    <div>
        <h1>Users</h1>
    </div>
</template>

<script>
import Header from './Header.vue'
export default {
    name: 'Users',
    components:{
        Header
    },
    mounted()
    {
        let user= localStorage.getItem('user-info')
        if (!user) {
            this.$router.push({name:'Login'})
        }
    }
}
</script>
