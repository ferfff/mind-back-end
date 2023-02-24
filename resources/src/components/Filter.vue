<template>
    <Header></Header>
    <h2>Logs</h2>
    <div class="container">
        <div class="row">
            <section class="col-md-9">
                <div class="table-responsive text-nowrap">
                    <table class="table table-success table-striped w-auto">
                        <thead>
                            <tr>
                                <th scope="col">Account Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in logs" :key="item.id">
                                <th scope="row">{{ item.accountname }}</th>
                                <td>{{ item.username }}</td>
                                <td>{{ item.start_date }}</td>
                                <td>{{ item.end_date }}</td>
                                <td>{{ item.active }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="col-md-3">
                <div class="login">
                    <div class="mb-3">
                        <input type="text" name="user_id" id="" placeholder="Enter user id" v-model="information.user_id">
                    </div>
                    <div class="mb-3">
                        <input type="datetime" name="start_date" id="" placeholder="Start date" v-model="information.start_date">
                    </div>
                    <div class="mb-3">
                        <input type="datetime" name="end_date" id="" placeholder="End date" v-model="information.end_date">
                    </div>
                    <div class="mb-3">
                        <button v-on:click="loadLogs" class="btn btn-info">Find logs</button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import Header from './Header.vue'
import axios from 'axios'
export default {
    name: 'Filter',
    components: {
        Header
    },
    methods:{ 
        async loadLogs() {
            await axios.post('/api/accounts/filter/', this.information, this.config).then(r => {
                if (r.status == 200 && r.data.status == "success") {
                    this.logs = r.data.log;
                    console.warn(r.data.log);
                }
            }).catch(error => {
                console.log(error);
            })
        },
    },
    data() {
        return {
            information: {
                account_id: this.$route.params.id,
                user_id: '',
                start_date: '',
                end_date: '',
            },
            logs: '',
            config: {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem('authtoken')}`
                }
            }
        }
    },
    async mounted() {
        let user= localStorage.getItem('user-info')
        if (!user) {
            this.$router.push({name:'Login'})
        }
        this.loadLogs()
    }
}
</script>
