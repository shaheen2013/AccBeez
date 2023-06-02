
<template>
    <div style="padding: 10px;">
        <h1>
            Bill List
            <router-link to="/bills/create" style="text-decoration: none; color: inherit;">
                <el-button type="primary" v-if="logged_in_user && logged_in_user.role === 'admin'" style="float: right;">
                    Create
                </el-button>
            </router-link>
        </h1>

        <el-table :data="bills">
            <el-table-column prop="date" label="Date" />
            <el-table-column prop="description" label="Description" />
            <el-table-column prop="invoice_total" label="Invoice Total" />
            <el-table-column prop="id" label="Operations" >

                <template  #default="scope">
                    <router-link :to="'/bills/edit/'+scope.row.id"  v-if="logged_in_user && logged_in_user.role === 'admin'">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <Edit />
                        </el-icon>
                        <!-- <el-button
                            type="primary"
                            size="small"
                            icon="el-icon-edit"
                        >
                            Edit
                        </el-button> -->
                    </router-link>
                    <router-link :to="'/bills/view/'+scope.row.id">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <View />
                        </el-icon>
                        <!-- <el-button
                            type="warning"
                            size="small"
                            icon="el-icon-view"
                        >
                            View
                        </el-button> -->
                    </router-link>
                    <el-icon :size="20" :color="'red'"
                            style="width: 1em; height: 1em; margin-right: 8px"
                            @click="handleDelete(scope.row.id);"
                             v-if="logged_in_user && logged_in_user.role === 'admin'"
                    >
                        <Delete />
                    </el-icon>
                    <!-- <el-button
                        type="danger"
                        size="small"
                        icon="el-icon-delete"
                        @click="handleDelete(scope.row.id);"
                    >
                        Delete
                    </el-button> -->
                </template>
            </el-table-column>
        </el-table>

    </div>
</template>



<script >
import BillItem from "./Item.vue";

import { ElMessage, ElMessageBox } from 'element-plus'

export default {
    name: 'BillList',
    data() {
        return {
            bills: [],
            logged_in_user: null
        };
    },
    components:{
        BillItem
    },
    async mounted() {
        try {
            await axios.get(`/api/bills`).
                    then((res) => {
                        // console.log('res:', res);
                        this.bills = res.data;
                        this.bills.forEach(element => {
                            element.invoice_total = element.invoice_total.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
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
                'Are you sure you want to delete the Bill?',
                'Warning',
                {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }
            ).then(() => {
                axios.delete(`/api/bills/`+id).
                    then((res) => {
                        console.log('res:', res);
                        this.bills = res.data;
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
