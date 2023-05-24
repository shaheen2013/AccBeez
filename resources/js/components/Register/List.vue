
<template>
    <div>
        <h1>Register List</h1>
        <el-table :data="registers">
            <el-table-column prop="date" label="Date" width="140" />
            <el-table-column prop="description" label="Description" width="120" />
            <el-table-column prop="invoice_total" label="Invoice Total" />
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
                        console.log('res:', res);
                        this.registers = res.data;
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
