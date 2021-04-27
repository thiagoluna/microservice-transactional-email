require('./bootstrap/bootstrap')
require('./plugins/vue-toastify')
import Vue from 'vue'
import DefaultTemplate from './layouts/DefaultTemplate'
import  router from './routers/router'
import store from "./vuex/store";

Vue.config.productionTip = false

new Vue({
  render: h => h(DefaultTemplate),
  router,
  store
}).$mount('#app')
