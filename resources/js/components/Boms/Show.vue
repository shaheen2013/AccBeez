<template>
    <el-form ref="ruleFormRef" :model="bom" class="demo-bom"
        label-position="top"
        status-icon
    >
        <el-text tag="b" type="primary" size="large">View Bom</el-text>


        <el-card class="box-card">
            <h4>Invoice for AccBeez</h4>
            <p><strong>Name:</strong> {{ bom.name }}</p>

            <el-row>
                <el-col :span="24">
                     <table>
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in bom.items" :key="item.id">
                                <td>{{ item.sku }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ formatCurrency(item.rate) }}</td>
                                <td style="text-align: right;">{{ formatCurrency(item.total) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none;" colspan="2"></td>
                                <td style="border: none;">Invoice Total</td>
                                <td colspan="2" style="text-align: right;">{{ formatCurrency(bom.invoice_total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </el-col>
            </el-row>


            <el-row>
                <el-col>
                    <router-link :to="'/boms'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>
                    <el-button type="primary" @click="downloadPdf" class="me-2">Download PDF</el-button>
                    <el-button type="primary" @click="exportBomXLS" class="me-2">Export Excel</el-button>
                    <el-button type="primary" @click="exportBomCSV" class="me-2">Export CSV</el-button>
                </el-col>
            </el-row>
        </el-card>
    </el-form>
</template>

<script>
export default {
    name: 'BomShow',
    data() {
        return {
            bom : {
                id: null,
                name: '',
                invoice_total: 0,
                items: [{
                    'sku': null,
                    'quantity': 0,
                    'rate': 0,
                    'total': 0,
                }]
            },
        };
    },
    async created() {
        let paths = this.$route.path.split("/");
        this.bom.id = paths[3];
        console.log('Route Name: ', this.$route.name);
        await axios.get(`/api/boms/edit/`+this.bom.id).
                then((res) => {
                    console.log('res:', res);
                    this.bom.id = res.data.id;
                    this.bom.name = res.data.name;
                    this.bom.invoice_total = res.data.invoice_total;
                    this.bom.items = res.data.bom_items;
                });
    },
    methods: {
        downloadPdf(){
            window.location.href = `/boms/download-pdf/`+this.bom.id;
        },
        formatCurrency(value) {
            return parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        exportBomXLS(){
            let format = 'xls';
            window.location.href = `/api/bom/blade/`+this.bom.id+`/export/`+format;
        },
        exportBomCSV(){
            let format = 'csv';
            window.location.href = `/api/bom/blade/`+this.bom.id+`/export/`+format;
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
</style>>
