<template>
    <div>
        <div>
            <el-text tag="b" type="primary" size="large">View Register</el-text>
            <div style="float: right;">
                <span style="vertical-align: middle;  font-size:16px;">Year</span>
                <el-date-picker
                    v-model="year"
                    type="year"
                    placeholder="Pick a year"
                    @change="handleYearFilter"
                />
            </div>
        </div>

        <h4>
            <!-- Raw Material Register -->
            <div style="float:right">
                <el-button type="info" class="me-1" @click="closeDate">Close Register</el-button>
                <el-button v-if="isMounted && bill_item.closing_dates && bill_item.closing_dates.length>0"
                        type="info" class="me-1" @click="undoDate">
                    Undo Register
                </el-button>
            </div>
        </h4>

        <!-- <el-card class="box-card" v-if="isMounted"> -->
        <div class="el-table-wrapper" v-if="isMounted">
            <h3 class="text-center mb-3 font-weight-bold">{{bill_item.name}} ({{bill_item.sku}})</h3>
            <div class="table-body" style="max-height: 75vh;">
                <el-table :data="register_rows" style="width: 100%">
                    <el-table-column prop="date" label="Date" width="120" />
                    <el-table-column label="Opening Inventory">
                        <!-- <el-table-column prop="opening_date_rate" label="Rate" width="100%" /> -->
                        <el-table-column prop="opening_date_rate" label="Rate">
                            <template #default="scope">
                                {{ formattedData(scope.row.opening_date_rate) }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="opening_date_quantity" label="Quantity" width="100%" />
                        <el-table-column prop="opening_date_total" label="Value" />
                    </el-table-column>
                    <el-table-column label="Purchase">
                        <!-- <el-table-column prop="bill_item_rate" label="Rate" /> -->
                        <el-table-column prop="bill_item_rate" label="Rate">
                            <template #default="scope">
                                {{ formattedData(scope.row.bill_item_rate) }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="bill_item_quantity" label="Quantity" />
                        <el-table-column prop="bill_item_total" label="Value" />
                    </el-table-column>
                    <el-table-column label="Sale">
                        <!-- <el-table-column prop="sale_item_rate" label="Rate" width="100%" /> -->
                        <el-table-column prop="sale_item_rate" label="Rate">
                            <template #default="scope">
                                {{ formattedData(scope.row.sale_item_rate) }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="sale_item_quantity" label="Quantity" width="100%" />
                        <el-table-column prop="sale_item_total" label="Value" />
                    </el-table-column>
                    <el-table-column label="Closing Inventory">
                        <!-- <el-table-column prop="closing_date_rate" label="Rate" width="100%" /> -->
                        <el-table-column prop="closing_date_rate" label="Rate">
                            <template #default="scope">
                                {{ formattedData(scope.row.closing_date_rate) }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="closing_date_quantity" label="Quantity" width="100%" />
                        <el-table-column prop="closing_date_total" label="Value" />
                    </el-table-column>
                    <!-- <el-table-column  label="Invoice Number">
                    </el-table-column> -->
                    <el-table-column label="Invoice Number" width="150">
                        <template v-slot="{ row }">
                            <div v-if="row && row.invoices">
                                <div v-for="invoice in row.invoices.split(',')">
                                    <a href="#" class="router-link-styling" @click="handleInvoiceClick(invoice)">
                                        {{ invoice }}
                                    </a>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column  label="Remarks">
                        <!-- <el-table-column prop="value" /> -->
                    </el-table-column>
                </el-table>

                <el-row>
                    <el-col>
                        <router-link :to="'/registers'">
                            <el-button type="info" class="me-2">Back</el-button>
                        </router-link>
                        <!-- <el-button type="primary" @click="downloadPdf" class="me-2">Download PDF</el-button> -->
                    </el-col>
                </el-row>
            </div>

        </div>
        <!-- </el-card> -->
    </div>
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
            query: {
                year: '',
            },
            register_rows: [],
            bill_item: null,
            isMounted: false,
            year: null,
        };
    },
    async created() {
        let paths = this.$route.path.split("/");
        this.register.id = paths[3];
        console.log('Route Name: ', this.$route.name);
        await this.getList();
        this.isMounted = true;
    },
    methods: {
        downloadPdf(){
            window.location.href = `/registers/download-pdf/`+this.register.id;
        },
        async getList(){
            let params = {
                year: this.query.year,
            }
            await axios.get(`/api/registers/view/`+this.register.id, {params}).
                then((res) => {
                    this.register.id = res.data.bill_item.id;
                    this.register.items = res.data.mergedItems;
                    this.register_rows = res.data.mergedItems;
                    this.bill_item = res.data.bill_item;
                    console.log('res:', res.data, this.bill_item);
                });
        },
        handleYearFilter(){
            this.query.year = new Date(this.year).getFullYear();
            this.getList();
        },
        formatCurrency(value) {
            return parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        async closeDate(){
            try {
                await axios.post(`/api/registers/close`, {sku: this.bill_item.sku}).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            // this.$router.push('/registers');
                        });
            } catch (error) {
                console.error('error in response:', error.response.data);
            }
        },
        undoDate(){
            ElMessageBox.confirm(
                'Are you sure you want to undo Closing Register for the date '+this.bill_item.closing_dates[0].date+'?',
                'Warning',
                {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }
            ).then(() => {
                axios.post(`/api/registers/undo`, {sku: this.bill_item.sku}).
                    then((res) => {
                        console.log('res:', res, this.$router);
                        this.$router.push('/registers');
                        ElMessage({
                            type: 'success',
                            message: 'Undo completed',
                        })
                    });
            }).catch(() => {
                ElMessage({
                    type: 'info',
                    message: 'Undo canceled',
                })
            })
        },
        formattedData(value) {
            if (value !== null && value !== undefined) {
                return value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        },
        handleInvoiceClick(invoice){
            window.location.href = `/bills/download-pdf/`+invoice.split('-')[1];
        },

    },
};
</script>


<style scoped>
.el-table-wrapper .cell{
  text-align: center;
  overflow: auto;
}
.el-table-wrapper .el-table__cell{
 padding: 0;
}

.table-body {
  overflow-y: auto;
}
.el-table-wrapper .el-table__header th{
  position: sticky !important;
  top: 0 !important;
  z-index: 1;
  /*background-color: green !important;*/
}
</style>>
