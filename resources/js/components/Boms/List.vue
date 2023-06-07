
<template>
    <div style="padding: 10px;">
        <h1>
            Bom List
            <router-link to="/boms/create" style="text-decoration: none; color: inherit;">
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
            />
            <el-button type="primary" @click="handleFilter">
                <el-icon style="vertical-align: middle">
                    <Search />
                </el-icon>
                <span style="vertical-align: middle"> Search </span>
            </el-button>
            <el-button type="danger" @click="handleBulkDelete">
                <el-icon style="vertical-align: middle">
                    <Delete />
                </el-icon>
                <span style="vertical-align: middle">Delete Selecteds</span>
            </el-button>
        </div>

        <el-table :data="boms"
                @selection-change="handleSelectionChange"
        >
            <el-table-column type="selection" width="55" />
            <el-table-column prop="name" label="Name" />           <el-table-column prop="invoice_total" label="Invoice Total">
                <template #default="scope">
                    {{ formattedInvoiceTotal(scope.row.invoice_total) }}
                </template>
            </el-table-column>
            <!-- <el-table-column prop="invoice_total" label="Invoice Total" /> -->
            <el-table-column prop="id" label="Operations" >

                <template  #default="scope">
                    <router-link :to="'/boms/edit/'+scope.row.id">
                        <el-icon :size="20" :color="color" style="width: 1em; height: 1em; margin-right: 8px"  v-if="logged_in_user && logged_in_user.role === 'admin'">
                            <Edit />
                        </el-icon>
                    </router-link>
                    <router-link :to="'/boms/view/'+scope.row.id">
                        <el-icon :size="20" :color="color" style="width: 1em; height: 1em; margin-right: 8px" >
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
import BomItem from "./Item.vue";
import { ElMessage, ElMessageBox } from 'element-plus'

export default {
    name: 'BomList',
    data() {
        return {
            boms: [],
            logged_in_user: null,
            loading: false,
            query: {
                page: 1,
                limit: 5,
                keyword: '',
            },
            total: 10,
            totalPages: null,
            pageSize: 5,
            multipleSelection: [],
            bulkDeleteIds: [],
        };
    },
    components:{
        BomItem
    },
    async mounted() {
        try {
            await this.getList();
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
                'Are you sure you want to delete the BOM?',
                'Warning',
                {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }
            ).then(() => {
                axios.delete(`/api/boms/`+id).
                    then((res) => {
                        console.log('res:', res);
                        this.boms = res.data;
                        this.boms.forEach(element => {
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
        handleBulkDelete(){
                // var multipleSelectionRaw = { ...this.multipleSelection }
            // const multipleSelectionRaw = Object.values(this.multipleSelection);
            const multipleSelectionArray =  Array.from(this.multipleSelection, obj => ({ ...obj }))
            console.log('multipleSelectionArray:', multipleSelectionArray);
            ElMessageBox.confirm(
                'Are you sure you want to bulkdelete the selected Bills?',
                'Warning',
                {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }
            ).then(() => {
                this.multipleSelection.forEach(element => {
                    this.bulkDeleteIds.push(element.id);
                    console.log(element.id, this.bulkDeleteIds);
                });
                axios.post(`/api/boms/bulkdelete`, this.bulkDeleteIds).
                    then((res) => {
                        console.log('res:', res);
                        this.bulkDeleteIds = [];
                        this.getList();
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
                this.bulkDeleteIds = [];
            })
        },handleFilter() {
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
            await axios.get(`/api/boms`, {params}).
                    then((res) => {
                        console.log('res:', res);
                        this.boms = res.data.data;
                        this.query.page = res.data.current_page;
                        this.total = res.data.total;
                        this.totalPages = Math.ceil(res.data.total / this.pageSize); // Calculate the total number of pages
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
        handleSelectionChange(val) {
            this.multipleSelection = val;
        },
    },
    computed: {
        formattedInvoiceTotal() {
            return (val) => {
                return val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            };
        },
    }
};
</script>

<style>
a href{
    text-decoration: none;
    color: inherit;
}
.filter-container {
  padding-bottom: 10px;
}
</style>
