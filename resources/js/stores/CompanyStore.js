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
        getCompanyList(state){
            return state.companies;
        }
    },

    actions: {
        async fetchCompanies() {
            try {
                await axios.get(`/api/companies`)
                .then(({data})=>{
                    this.companies=data.data;
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
                    if(data.success === 'true'){
                        this.company.name = ''
                        this.companies.unshift(data.data)
                    }
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