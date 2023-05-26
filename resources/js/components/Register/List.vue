
<template>
    <div style="padding:20px;">
        <h1>Register List</h1>
        <el-table :data="registers">
            <el-table-column prop="sku" label="SKU" />
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
            registers: []
        };
    },
    async mounted() {
        try {
            await axios.get(`/api/registers`).
                    then((res) => {
                        console.log('response in register list:', res);
                        this.registers = res.data;
                        this.registers.forEach(element => {
                            element.avg_cost = element.avg_cost.toFixed(2)
                            return element;
                        });
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
