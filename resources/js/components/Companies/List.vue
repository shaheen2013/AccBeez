<template>
    <el-row :gutter="12">
        <el-col :span="12">
            <h3>Comapnies</h3>
            <p class="text-muted">{{ companyData.totalCompany }} companies</p>
        </el-col>

        <el-col :span="12">

            <create></create>
            <h1>
                <!-- <router-link :to="{ name: 'companyCreate'}" style="text-decoration: none; color: inherit;">
                    <el-button type="primary" style="float: right;">
                        Create company
                    </el-button>
                </router-link> -->
            </h1>
        </el-col>
    </el-row>

    <el-row :gutter="12">
        <el-col :span="8" v-for="company in companyData.companies[0]" class="pb-2">
            <el-card shadow="always"> {{ company.name }} </el-card>
        </el-col>
    </el-row>
</template>



<script >

import { useCompanyStore } from "@/stores/CompanyStore"
import create from './Create.vue'

//const companyData = useCompanyStore();


export default {
    name: 'CompanyList',
    components: {
        create
    },

    data() {
        return { companyData: useCompanyStore() }
    },
    mounted() {
        const getAllCompanies = useCompanyStore()
        getAllCompanies.fetchCompanies()
    },
    methods: {

    }
}
</script>


