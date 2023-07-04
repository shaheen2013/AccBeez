import axios from 'axios';
import { defineStore } from 'pinia'

export const useCompanyStore = defineStore("CompanyStore", {
    state: () => ({
        companies: [],
        totalCompany : 0,
        company: {
            name: ''
        }
    }),

    getters: {

    },

    actions: {
        async fetchCompanies() {
            try {
                await axios.get(`/api/companies`)
                .then(({data})=>{
                    this.companies.push(data.data)
                    this.totalCompany = data.data.length
                })
                .catch((error)=>{
                    console.log(error);
                })
                       
            } catch (error) {
                console.error(error);
            }
        },

        async createCompany(){
            try {
                await axios.post(`/api/companies`, {
                    name: this.company.name,
                  })
                .then(({data})=>{
                   console.log(data.data);
                })
                .catch((error)=>{
                    console.log(error);
                })
                       
            } catch (error) {
                console.error(error);
            }
        }

    }
})