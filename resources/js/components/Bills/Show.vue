<template>
    <el-form ref="ruleFormRef" :model="bill" class="demo-bill"
        label-position="top"
        status-icon
    >
        <el-text tag="b" type="primary" size="large">View Bill</el-text>


        <el-card class="box-card">
            <h4>Invoice for AccBeez</h4>
            <p><strong>Description:</strong> {{ bill.description }}</p>
            <p><strong>Date:</strong> {{ bill.date }}</p>


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
                            <tr v-for="item in bill.items" :key="item.id">
                                <td>{{ item.sku }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ formatCurrency(item.rate) }}</td>
                                <td style="text-align: right;">{{ formatCurrency(item.total) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none;" colspan="2"></td>
                                <td style="border: none;">Invoice Total</td>
                                <td colspan="2" style="text-align: right;">{{ formatCurrency(bill.invoice_total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </el-col>
            </el-row>


            <el-row>
                <el-col>
                    <router-link :to="'/bills'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>
                    <el-button type="primary" @click="downloadPdf" class="me-2">Download PDF</el-button>
                    <el-button type="primary" @click="exportBillXLS" class="me-2">Export Excel</el-button>
                    <el-button type="primary" @click="exportBillCSV" class="me-2">Export CSV</el-button>
                </el-col>
            </el-row>
        </el-card>
    </el-form>
</template>

<script>
export default {
    name: 'BillShow',
    data() {
        return {
            bill : {
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
        let paths = this.$route.path.split("/");
        this.bill.id = paths[3];
        console.log('Route Name: ', this.$route.name);
        await axios.get(`/api/bills/edit/`+this.bill.id).
                then((res) => {
                    console.log('res:', res);
                    this.bill.id = res.data.id;
                    this.bill.description = res.data.description;
                    this.bill.invoice_total = res.data.invoice_total;
                    this.bill.date = res.data.date;
                    this.bill.items = res.data.bill_items;
                });
    },
    methods: {
        downloadPdf(){
            window.location.href = `/bills/download-pdf/`+this.bill.id;
        },
        exportBillXLS(){
            let format = 'xls';
            window.location.href = `/api/bill/blade/`+this.bill.id+`/export/`+format;
        },
        exportBillCSV(){
            let format = 'csv';
            window.location.href = `/api/bill/blade/`+this.bill.id+`/export/`+format;
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
