
<template>
    <div style="padding:20px;">
        <h1>Register List</h1>
        <el-table :data="registers">
            <el-table-column prop="sku" label="SKU" />
            <!-- <el-table-column label="Row Key">
                <template v-slot="{ row }">
                    <span>{{ row.key }}</span>
                </template>
            </el-table-column> -->
            <el-table-column prop="total_items" label="Total Item" />
            <el-table-column prop="avg_cost" label="Average Cost" />
        </el-table>

    </div>
</template>



<script >

export default {
    name: 'Register',
    data() {
        return {
            registers: [],
            months: [],
            original_months: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ]
        };
    },
    async mounted() {
        try {
            await axios.get(`/api/registers`).
                    then((res) => {
                        console.log('response in register list:', res.data);
                        this.registers = res.data.registers;
                        this.months = res.data.distinct_months;
                        // this.registers.forEach(element => {
                        //     element.avg_cost = element.avg_cost.toFixed(2)
                        //     return element;
                        // });
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
