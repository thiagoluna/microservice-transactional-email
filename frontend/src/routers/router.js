import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../pages/home.vue'
import Emails from '../pages/emails'

Vue.use(VueRouter)

const routes = [
    {path: '/', component: Home, name: 'home'},
    {path: '/emails', component: Emails, name: 'emails'}
]

const router = new VueRouter({
    routes,
    mode: "history"
})

export default router