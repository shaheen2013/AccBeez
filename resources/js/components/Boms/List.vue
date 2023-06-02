
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

        <el-table :data="boms">
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="invoice_total" label="Invoice Total" />
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
            logged_in_user: null
        };
    },
    components:{
        BomItem
    },
    async mounted() {
        try {
            await axios.get(`/api/boms`).
                    then((res) => {
                        console.log('res:', res);
                        this.boms = res.data;
                        this.boms.forEach(element => {
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

        }

    },
};
</script>

<style>
a href{
    text-decoration: none;
    color: inherit;
}
</style>
