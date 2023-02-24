<template>
    <Header></Header>
    <div>
        <h3 class="text-primary">Users</h3>
        <router-link to="/users/add">
            <button type="button" class="btn btn-success">Add User (only superadmin)</button>
        </router-link> 
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-light table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">English Level</th>
                    <th scope="col">Knowledge</th>
                    <th scope="col">Link CV</th>
                    <th scope="col">Role</th>
                    <th scope="col">Update</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in users" :key="item.id">
                    <th  scope="row">{{ item.id }}</th>
                    <td>{{ item.name }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.english_level }}</td>
                    <td>{{ item.knowledge }}</td>
                    <td>{{ item.link_cv }}</td>
                    <td>{{ item.role }}</td>
                    <td>
                        <router-link :to="'/users/update/' + item.id">
                            <button type="button" class="btn btn-primary">Update</button>
                        </router-link>
                    </td>
                    <td>
                        <button v-on:click="erase(item.id)" type="button" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import Header from './Header.vue'
import axios from 'axios'
export default {
    name: 'Users',
    components:{
        Header
    },
    methods: {
        async loadUsers() {
            let userdata = localStorage.getItem('user-info');
            this.name = JSON.parse(userdata).user.name;
            if (!userdata) {
                this.$router.push({name:'Login'})
            }

            await axios.get('/api/users/index', this.config)
                    .then((response) => {
                        this.users = response.data.users;
                        console.warn(this.users);
                    })
                    .catch(error => {
                        console.warn(error)
                    });
        },
        async erase(id)
        { 
            await axios.delete("/api/users/delete/" + id, this.config)
                    .then((r) => {
                        if (r.status == 200 && r.data.status == "success") {
                            this.loadUsers()
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(error => console.log(error))
        },
    },
    mounted()
    {
        let user= localStorage.getItem('user-info')
        if (!user) {
            this.$router.push({name:'Login'})
        }
        this.loadUsers();
    },
    data() {
        return {
            users: [],
            config: {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            }
        }
    },
}
</script>
