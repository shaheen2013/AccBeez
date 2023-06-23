import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from '@/store/auth'
import bill from "./bill.js";

const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth, bill
    }
})

export default store
