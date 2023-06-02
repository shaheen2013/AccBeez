<template>
    <div style="padding: 10px;">
        <h1>
            Sale List
            <router-link to="/sales/create" style="text-decoration: none; color: inherit;">
                <el-button type="primary" v-if="logged_in_user && logged_in_user.role === 'admin'" style="float: right;">
                    Create
                </el-button>
            </router-link>
        </h1>

        <el-table :data="sales">
            <el-table-column prop="date" label="Date" />
            <!-- <el-table-column prop="bom_id" label="Bom" /> -->
            <el-table-column prop="bom" label="Bom">
                <template #default="scope">
                    {{ scope.row.bom.name }}
                </template>
            </el-table-column>
            <el-table-column prop="amount" label="Amount" />
            <el-table-column prop="id" label="Operations" >
                <template  #default="scope">
                    <router-link :to="'/sales/edit/'+scope.row.id"  v-if="logged_in_user && logged_in_user.role === 'admin'">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <Edit />
                        </el-icon>
                    </router-link>
                    <router-link :to="'/sales/view/'+scope.row.id">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <View />
                        </el-icon>
                    </router-link>
                    <el-icon :size="20" :color="'red'"
                            style="width: 1em; height: 1em; margin-right: 8px"
                            @click="handleDelete(scope.row.id);"
                             v-if="logged_in_user && logged_in_user.role === 'admin'"
                    >
                        <Delete />
                    </el-icon>
                </template>
            </el-table-column>
        </el-table>

    </div>
</template>



<script >
import SaleItem from "./Item.vue";

import { ElMessage, ElMessageBox } from 'element-plus'

export default {
    name: 'SaleList',
    data() {
        return {
            sales: [],
            logged_in_user: null
        };
    },
    components:{
        SaleItem
    },
    async mounted() {
        try {
            await axios.get(`/api/sales`).
                    then((res) => {
                        // console.log('res:', res);
                        this.sales = res.data;
                        this.sales.forEach(element => {
                            element.amount = element.amount.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
                            return element;
                        });
                    });
            await axios.get(`/logged_in_user`).
                    then((res) => {
                        this.logged_in_user = res.data;
                        console.log('logged_in_user:', this.logged_in_user);
                    });
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        handleDelete(id){
            console.log(id);

            ElMessageBox.confirm(
                'Are you sure you want to delete the Sale?',
                'Warning',
                {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }
            ).then(() => {
                axios.delete(`/api/sales/`+id).
                    then((res) => {
                        console.log('res:', res);
                        this.sales = res.data;
                        this.sales.forEach(element => {
                            element.amount = element.amount.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
                            return element;
                        });
                        ElMessage({
                            type: 'success',
                            message: 'Delete completed',
                        })
                    });
            }).catch(() => {
                ElMessage({
                    type: 'info',
                    message: 'Delete canceled',
                })
            })

        }

    },
};
</script>

<style>
</style>
