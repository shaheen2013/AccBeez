<template>
    <el-form ref="ruleFormRef" :model="register" class="demo-register"
        label-position="top"
        status-icon
    >
        <el-text tag="b" type="primary" size="large">View Register</el-text>


        <el-card class="box-card" v-if="isMounted">
            <h4>Raw Material Register</h4>


            <el-table :data="register_rows" style="width: 100%">
                <el-table-column :label="bill_item.name">
                    <el-table-column prop="date" label="Date" width="120" />
                    <!-- <el-table-column label="Opening Inventory">
                        <el-table-column prop="state" label="State" width="100" />
                        <el-table-column prop="city" label="City" width="120" />
                        <el-table-column prop="address" label="Address" width="250" />
                    </el-table-column> -->
                    <el-table-column label="Purchase">
                        <el-table-column prop="bill_item_rate" label="Rate" />
                        <el-table-column prop="bill_item_quantity" label="Quantity" />
                        <el-table-column prop="bill_item_total" label="Value" />
                    </el-table-column>
                    <el-table-column label="Sale">
                        <el-table-column prop="sale_item_rate" label="Rate"  />
                        <el-table-column prop="sale_item_quantity" label="Quantity"  />
                        <el-table-column prop="sale_item_total" label="Value" />
                    </el-table-column>
                    <!-- <el-table-column label="Closing Inventory">
                        <el-table-column prop="state" label="State" width="100" />
                        <el-table-column prop="city" label="City" width="120" />
                        <el-table-column prop="address" label="Address" width="250" />
                    </el-table-column> -->
                </el-table-column>
            </el-table>

            <el-row>
                <el-col>
                    <router-link :to="'/registers'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>
                    <el-button type="primary" @click="downloadPdf" class="me-2">Download PDF</el-button>
                </el-col>
            </el-row>
        </el-card>
    </el-form>
</template>

<script>
export default {
    name: 'RegisterShow',
    data() {
        return {
            register : {
                id: null,
                description: '',
                invoice_total: 0,
                date: '',
                items: [{
                    'sku': null,
                    'quantity': 0,
                    'rate': 0,
                    'total': 0,
                }]
            },
            register_rows: [],
            bill_item: null,
            isMounted: false,
        };
    },
    async created() {
        let paths = this.$route.path.split("/");
        this.register.id = paths[3];
        console.log('Route Name: ', this.$route.name);
        await axios.get(`/api/registers/view/`+this.register.id).
                then((res) => {
                    this.register.id = res.data.id;
                    this.register.items = res.data.mergedItems;
                    this.register_rows = res.data.mergedItems;
                    this.bill_item = res.data.bill_item;
                    console.log('res:', this.register_rows, res, this.bill_item);
                });
                this.isMounted = true;
    },
    methods: {
        downloadPdf(){
            window.location.href = `/registers/download-pdf/`+this.register.id;
        },
        formatCurrency(value) {
            return parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
    },
};
</script>


<style scoped>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
    }
    /* .small-font-table .el-table__body {
        font-size: 10px;
    } */
    .el-table thead.is-group th.el-table__cell {
        text-align: center;
    }
</style>>
