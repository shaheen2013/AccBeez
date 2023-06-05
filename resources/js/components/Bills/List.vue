
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

        <div class="filter-container">
            <el-input
                v-model="query.keyword"
                placeholder="Keyword"
                style="width: 200px;"
                class="filter-item"
                @keyup.enter.native="handleFilter"
            />
            <el-button type="primary" @click="handleFilter">
                <el-icon style="vertical-align: middle">
                    <Search />
                </el-icon>
                <span style="vertical-align: middle"> Search </span>
            </el-button>
            <el-button
                :loading="downloading"
                class="filter-item"
                type="primary"
                @click="handleDownload"
            >
                <el-icon><Download /></el-icon>Export
            </el-button>
        </div>


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
                    </router-link>
                    <router-link :to="'/bills/view/'+scope.row.id">
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


        <div class="demo-pagination-block">
            <el-pagination
                v-show="total>0"
                :page-size="query.limit"
                layout="total, sizes, prev, pager, next"
                :total="total"
                :page-count="totalPages"
                :page-sizes="[1, 2, 5, 10, 20, 50]"
                @size-change="handleSizeChange"
                @current-change="handlePageChange"
            />
        </div>

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
            logged_in_user: null,
            downloading: false,
            loading: false,
            query: {
                page: 1,
                limit: 5,
                keyword: '',
            },
            total: 10,
            totalPages: null,
            pageSize: 10
        };
    },
    components:{
        BillItem
    },
    async created() {
        try {
            await this.getList();
            await axios.get(`/logged_in_user`).
                    then((res) => {
                        this.logged_in_user = res.data;
                        // console.log('logged_in_user:', this.logged_in_user);
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
                        this.bills.forEach(element => {
                            element.invoice_total = element.invoice_total.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
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

        },
        
        handleFilter() {
            this.query.page = 1;
            this.getList();
        },
        async getList() {
            this.loading = true;
            let params = {
                limit: this.pageSize,
                keyword: this.query.keyword,
                page: this.query.page,
            }
            console.log('params', params);
            await axios.get(`/api/bills`, {params}).
                    then((res) => {
                        console.log('res:', res);
                        this.bills = res.data.data;
                        this.query.page = res.data.current_page;
                        this.total = res.data.total;
                        this.totalPages = Math.ceil(res.data.total / this.pageSize); // Calculate the total number of pages
                        this.bills.forEach(element => {
                            element.invoice_total = element.invoice_total.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
                            return element;
                        });
                    });
            this.loading = false;
        },
        handleDownload(){
            
        },

        handleSizeChange(val) {
            this.pageSize = val;
            this.getList();
        },
        handlePageChange(currentPage) {
            this.query.page = currentPage;
            this.getList();
        },

    },
};
</script>

<style scoped>
.filter-container {
  padding-bottom: 10px;
}
.demo-pagination-block  {
  margin-top: 10px;
}
</style>
