<template>
    <Header></Header>
    <div>
        <h3 class="text-primary">Hello {{ name }}</h3>
        <router-link to="/accounts/add">
            <button v-on:click="erase(item.id)" type="button" class="btn btn-success">Add acount</button>
        </router-link> 
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-light table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Responsible</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Members</th>
                    <th scope="col">Update</th>
                    <th scope="col">Delete</th>
                    <th scope="col">Logs</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in account" :key="item.id">
                    <th  scope="row">{{ item.id }}</th>
                    <td>{{ item.name }}</td>
                    <td>{{ item.responsible[0].name }}</td>
                    <td>{{ item.customer }}</td>
                    <td>
                        <router-link :to="'/accounts/add/' + item.id" class="link-success">Add Members</router-link>
                        <ul v-for="member in item.members" :key="member.id" class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                {{ member.name }} <a v-on:click="deletemember(item.id, member.id)" href="#" class="link-danger">Delete</a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <router-link :to="'/accounts/update/' + item.id">
                            <button type="button" class="btn btn-primary">Update</button>
                        </router-link>
                    </td>
                    <td>
                        <button v-on:click="erase(item.id)" type="button" class="btn btn-danger">Delete</button>
                    </td>
                    <td>
                        <router-link :to="'/accounts/filter/' + item.id">
                            <button type="button" class="btn btn-info">Logs</button>
                        </router-link>
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
