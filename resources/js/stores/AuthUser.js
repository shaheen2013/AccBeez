import axios from 'axios';
import { defineStore } from 'pinia'


export const useAuthUserStore = defineStore("AuthUserStore", {
    state: () => ({
        authUser: null,
    }),
    getters: {
        user: (state) => state.authUser
    },
    actions: {
        async loggedUser(){
            await axios.get(`/logged_in_user`).
                    then((res) => {
                        console.log('authUserStore_pinia_res', res);
                        this.authUser = res.data;
                    });
        }
    }
}) 