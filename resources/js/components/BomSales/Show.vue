<template>
    <el-form ref="ruleFormRef" :model="bomSale" class="demo-bomSale"
        label-position="top"
        status-icon
    >
        <el-text tag="b" type="primary" size="large">View BomSale</el-text>


        <el-card class="box-card">
            <h4>Invoice for AccBeez</h4>
            <p><strong>Description:</strong> {{ bomSale.description }}</p>
            <p><strong>Date:</strong> {{ bomSale.date }}</p>


            <el-row>
                <el-col :span="24">
                     <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in bomSale.items" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ formatCurrency(item.rate) }}</td>
                                <td style="text-align: right;">{{ formatCurrency(item.total) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none;" colspan="2"></td>
                                <td style="border: none;">Invoice Total</td>
                                <td colspan="2" style="text-align: right;">{{ formatCurrency(bomSale.invoice_total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </el-col>
            </el-row>


            <el-row>
                <el-col>
                    <router-link :to="'/' + $route.params.slug + '/bomSales'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>
                    <!-- <el-button type="primary" @click="downloadPdf" class="me-2">Download PDF</el-button>
                    <el-button type="primary" @click="exportBomSaleXLS" class="me-2">Export Excel</el-button>
                    <el-button type="primary" @click="exportBomSaleCSV" class="me-2">Export CSV</el-button> -->
                </el-col>
            </el-row>
        </el-card>
    </el-form>
</template>

<script>
export default {
    name: 'BomSaleShow',
    data() {
        return {
            bomSale : {
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
        };
    },

    async created() {
        this.bomSale.id = this.$route.params.id;
        console.log('Route Name: ', this.$route.name);
        await axios.get(`/api/bomSales/edit/`+this.bomSale.id).
                then((res) => {
                    this.bomSale.id = res.data.id;
                    this.bomSale.description = res.data.description;
                    this.bomSale.invoice_total = res.data.invoice_total;
                    this.bomSale.date = res.data.date;
                    this.bomSale.items = res.data.bom_sale_items;
                });
    },
    methods: {
        downloadPdf(){
            window.location.href = `/bomSales/download-pdf/`+this.bomSale.id;
        },
        exportBomSaleXLS(){
            let format = 'xls';
            window.location.href = `/api/bomSale/blade/`+this.bomSale.id+`/export/`+format;
        },
        exportBomSaleCSV(){
            let format = 'csv';
            window.location.href = `/api/bomSale/blade/`+this.bomSale.id+`/export/`+format;
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
</style>>
