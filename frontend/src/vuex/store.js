import Vue from 'vue'
import Vuex from 'vuex'

import Emails from './modules/emails/emails'

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: { Emails }
})
export default store
