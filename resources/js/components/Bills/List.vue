
<template>
    <div style="padding: 10px;">
        <h1>Bill List</h1>
        <el-table :data="bills">
            <el-table-column prop="date" label="Date" />
            <el-table-column prop="description" label="Description" />
            <el-table-column prop="invoice_total" label="Invoice Total" />
            <el-table-column prop="id" label="Operations" >

                <template  #default="scope">
                    <router-link :to="'/bills/edit/'+scope.row.id">
                        <el-button
                            type="primary"
                            size="small"
                            icon="el-icon-edit"
                        >
                            Edit
                        </el-button>
                    </router-link>
                    <router-link :to="'/bills/view/'+scope.row.id">
                        <el-button
                            type="warning"
                            size="small"
                            icon="el-icon-view"
                        >
                            View
                        </el-button>
                    </router-link>
                    <el-button
                        type="danger"
                        size="small"
                        icon="el-icon-delete"
                        @click="handleDelete(scope.row.id);"
                    >
                        Delete
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

    </div>
</template>



<script >

import BillItem from "./Item.vue";
export default {
    name: 'BillList',
    data() {
        return {
            bills: []
        };
    },
    components:{
        BillItem
    },
    async mounted() {
        try {
            await axios.get(`/api/bills`).
                    then((res) => {
                        console.log('res:', res);
                        this.bills = res.data;
                    });
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        handleDelete(id){
            console.log(id);
        }

    },
};
</script>

<style>

</style>
