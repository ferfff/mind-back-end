import Home from '../src/components/Home.vue'
import Login from '../src/components/Login.vue'
import Users from '../src/components/Users.vue'
import AddAcounts from '../src/components/AddAccounts.vue'
import UpdateAccounts from '../src/components/UpdateAccounts.vue'
import AddMembers from '../src/components/AddMembers.vue'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        name: "Home",
        component: Home,
        path: "/"
    },
    {
        name: "Login",
        component: Login,
        path: "/login"
    },
    {
        name: "Users",
        component: Users,
        path: "/users"
    },
    {
        name: "AddAcounts",
        component: AddAcounts,
        path: "/accounts/add"
    },
    {
        name: "UpdateAccounts",
        component: UpdateAccounts,
        path: "/accounts/update/:id"
    },
    {
        name: "AddMembers",
        component: AddMembers,
        path: "/accounts/add/:id"
    }    
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router;
