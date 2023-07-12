import 'es6-promise/auto'
import { createStore } from 'vuex'

import createPersistedState from 'vuex-persistedstate'
import auth from '@/store/auth'
import bill from "./bill.js";
import company from '@/store/company';
import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth, bill, company
    }
})

export default store
