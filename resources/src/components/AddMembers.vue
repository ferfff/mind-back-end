<template>
    <Header></Header>
    <h1>Add Members</h1>
    <div class="container">
        <div class="row">
            <section class="col-md-6">
                <div class="table-responsive text-nowrap">
                    <table class="table table-success table-striped w-auto">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Member Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in members" :key="item.id">
                                <th scope="row">{{ item.id }}</th>
                                <td>{{ item.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="col-md-6">
                <div class="login">
                    <form @submit.prevent="onSubmit">
                        <div class="mb-3">
                            <input type="text" name="id" id="" placeholder="Enter member id" v-model="id">
                        </div>
                        <div class="mb-3">
                            <input type="datetime" name="startdate" id="" placeholder="Start date" v-model="startdate">    
                        </div>
                        <div class="mb-3">
                            <input type="datetime" name="enddate" id="" placeholder="End date" v-model="enddate">    
                        </div>
                        <div class="mb-3">
                            <button v-on:click="addMember" class="btn btn-primary">Add Member</button>
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
    name: 'AddMembers',
    components:{
        Header
    },
    methods:{
        async addMember()
        {
            axios.put('/api/accounts/add_users/' + this.$route.params.id, {
                id: this.id,
                start_date: this.startdate,
                end_date: this.enddate,
                
            }, this.config).then(r => {
                if (r.status == 200 && r.data.status == "success") {
                    this.$router.push({name:'Home'})
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

        await axios.get('/api/accounts/show/' + this.$route.params.id, this.config)
                    .then((response) => {
                        this.members = response.data.accounts.members;
                        console.log(this.members);
                    })
                    .catch(error => {
                        console.warn(error)
                    });
    },
    data() {
        return {
            id: '',
            enddate: '',
            startdate: '',
            account: '',
            members: {
                id: '',
                name: '',
            },
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
