import { defineStore } from 'pinia'

export const usePostStore = defineStore({
    id: 'post',
    
    getters: {

    },
    actions: {
        async fetchPosts() {
            console.log("hai");
        },

    }
})