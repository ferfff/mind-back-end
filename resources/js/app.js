import './bootstrap'
import { createApp } from "vue"
import App from "../src/App.vue"
import router from './routers'

createApp(App).use(router).mount("#app")

//App.config.globalProperties.$axios = axios

/*let token = JSON.parse( localStorage.getItem('authtoken') );
if( token ){
     window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
}*/
