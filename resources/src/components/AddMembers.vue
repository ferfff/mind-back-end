<template>
    <Header></Header>
    <div>
        <h1>Add Members</h1>

        <table border="1">
            <tr>
                <td>ID</td>
                <td>Member Name</td>
            </tr>
            <tr v-for="item in members" :key="item.id">
                <td>{{ item.id }}</td>
                <td>{{ item.name }}</td>
            </tr>
        </table>
        <form @submit.prevent="onSubmit" class="add">
            <input type="text" name="id" id="" placeholder="Enter member id" v-model="id">
            <input type="datetime" name="startdate" id="" placeholder="Start date" v-model="startdate">
            <input type="datetime" name="enddate" id="" placeholder="End date" v-model="enddate">
            <button v-on:click="addMember">Add Member</button>
        </form>
    </div>
</template>

<script>
import Header from './Header.vue'
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
