import * as bootstrap from 'bootstrap'
import { createApp } from "vue"
import App from "../src/App.vue"
import router from './routers'

createApp(App).use(router).mount("#app")
