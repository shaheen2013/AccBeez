import Vue from "vue"
import Vuex from "vuex";
import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from '@/store/auth'
import bill from "./bill.js";
import sku from "./sku.js";
Vue.use(Vuex);
const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth, bill, sku
    }
})

export default store
