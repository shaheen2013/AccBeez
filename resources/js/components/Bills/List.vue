
<template>
    <div>
        <h1>Bill List</h1>
        <el-table :data="bills">
            <el-table-column prop="date" label="Date" width="140" />
            <el-table-column prop="description" label="Description" width="120" />
            <el-table-column prop="invoice_total" label="Invoice Total" />
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

    },
};
</script>

<style>

</style>
