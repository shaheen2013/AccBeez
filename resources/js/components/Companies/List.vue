<template>
    <el-row :gutter="12">
        <el-col :span="12">
            <h3>Comapnies</h3>
            <p class="text-muted">{{ getCompanyList.length }} companies</p>
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
        <el-col :span="8" v-for="company in getCompanyList" class="pb-2" :key="company.id">
            <router-link :to="'/' + company.slug + '/dashboard'" style="text-decoration: none;">
                <el-card shadow="always"> {{ company.name }} </el-card>
            </router-link>
        </el-col>
    </el-row>
</template>



<script >

// import { useCompanyStore } from "@/stores/CompanyStore"
import create from './Create.vue'
import { mapState } from 'pinia'
import { useCompanyStore } from '@/stores/CompanyStore'

//const companyData = useCompanyStore();


export default {
    name: 'CompanyList',
    components: {
        create
    },

    computed: {
        ...mapState(useCompanyStore, { getCompanyList: 'getCompanyList' })
    },

    mounted() {
        const getAllCompanies = useCompanyStore()
        getAllCompanies.fetchCompanies()
    },
    methods: {

    }
}
</script>


