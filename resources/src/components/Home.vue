<template>
    <Header></Header>
    <div>
        <h1>Hello {{ name }} this is the home page</h1>
    </div>
    <table border="1">
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Responsible</td>
            <td>Customer</td>
            <td>Members</td>
            <td>Update</td>
            <td>Delete</td>
        </tr>
        <tr v-for="item in account" :key="item.id">
            <td>{{ item.id }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.responsible[0].name }}</td>
            <td>{{ item.customer }}</td>
            <td>
                <router-link :to="'/accounts/add/' + item.id">Add Members</router-link>
                <ul v-for="member in item.members" :key="member.id">
                    <li>
                        {{ member.name }} <a v-on:click="deletemember(item.id, member.id)" href="#">D</a>
                    </li>
                </ul>
            </td>
            <td><router-link :to="'/accounts/update/' + item.id">Update</router-link></td>
            <td>
                <a v-on:click="erase(item.id)" href="#">Delete</a>
            </td>
        </tr>
    </table>

    <router-link to="/accounts/add">Add Account</router-link> 
</template>

<script>
import Header from './Header.vue'
import axios from 'axios'
export default {
    name: 'Home',
    methods:{
        async erase(id)
        { 
            await axios.delete("http://localhost/api/accounts/delete/" + id, this.config)
                    .then((r) => {
                        if (r.status == 200 && r.data.status == "success") {
                            this.loadData()
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(error => console.log(error))
        },
        async loadData() {
            let userdata = localStorage.getItem('user-info');
            this.name = JSON.parse(userdata).user.name;
            if (!userdata) {
                this.$router.push({name:'Login'})
            }

            await axios.get('/api/accounts/index', this.config)
                    .then((response) => {
                        this.account = response.data.accounts;
                    })
                    .catch(error => {
                        console.warn(error)
                    });
        },
        async deletemember(id, idMember)
        {
            await axios.put('/api/accounts/remove_users/' + id, {
                userstoremove: idMember,
            }, this.config).then(r => {
                if (r.status == 200 && r.data.status == "success") {
                    this.loadData()
                }else {
                    console.log(r);
                }
            }).catch(error => {
                console.log(error);
            })
        }
    },
    data() {
        return {
            name: '',
            account: [],
            config: {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            }
        }
    },
    components:{
        Header
    },
    async mounted()
    {
        this.loadData();
    }
}
</script>

<style>
td{
    width: 160px;
}
</style>
