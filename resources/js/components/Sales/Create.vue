<template>
    <el-form ref="ruleFormRef" :model="sale" class="demo-sale"
        label-position="top"
        status-icon
    >
        <el-text tag="b" v-if="operation === 'view'" type="primary" size="large">View Sale</el-text>
        <el-text tag="b" v-if="operation === 'edit'" type="primary" size="large">Edit Sale</el-text>
        <el-text tag="b" v-if="operation === 'create'" type="primary" size="large">Create Sale</el-text>


        <el-card class="box-card">
            <el-form-item label="Date" required>
                <el-date-picker v-model="sale.date" type="date" label="Pick a date" placeholder="Pick a date"
                    format="YYYY-MM-DD"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                    :disabled="operation === 'view'" />
            </el-form-item>
            <el-form-item label="Description" prop="description">
                <el-input v-model="sale.description" type="textarea" :disabled="operation === 'view'" />
            </el-form-item>


            <el-row>
                <el-col :span="24">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th :style="operation === 'view' ? { 'width': '25%' } : { 'width': '15%' }">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Name</span>
                                </th>
                                <th width="15%">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Rate</span>
                                </th>
                                <th width="15%">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Unit</span>
                                </th>
                                <th width="15%">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Quantity</span>
                                </th>
                                <th width="15%">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Item Total</span>
                                </th>
                                <th v-if="operation !== 'view'" width="10%">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <saleItem
                                v-for="(item, index) in sale.items"
                                :key="index"
                                :index="index"
                                :item="item"
                                :saleItems="sale.items"
                                :sale="sale"
                                :operation="operation"
                                :products="products"
                                @changeInvoiceTotal="changeInvoiceTotal"
                                :deletedItemsID="deletedItemsID"
                            />
                        </tbody>
                    </table>
                    <el-button type="info" 
                                @click="addItem" 
                                v-if="operation === 'create' || operation === 'edit'"
                                class="mb-3">
                        Add
                    </el-button>
                </el-col>

            </el-row>

            <el-form-item label="Invoice Total">
                <el-input v-model="formattedTotal" type="number" placeholder="Invoice Total" disabled />
            </el-form-item>



            <el-row>
                <el-col>
                    <el-button v-if="operation === 'create'" type="primary" @click="createSale" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateSale" class="me-2">Update</el-button>
                    <router-link :to="'/'+ $route.params.slug + '/sales'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>

                    <el-button v-if="operation === 'view'" type="primary" @click="downloadPdf" class="me-2">Download PDF</el-button>
                </el-col>
            </el-row>
        </el-card>
    </el-form>
</template>

<script >

import SaleItem from "./Item.vue";
import Show from "./Show.vue";
import { showErrors } from '@/utils/helper.js'

export default {
    name: 'SaleCreate',
    data() {
        return {
            routeName: '',
            operation: 'create',
            singleItem: {
                sku: null,
                name: '',
                rate: 0,
                unit: '',
                quantity: 0,
                total: 0,
            },
            sale : {
                id: null,
                description: '',
                invoice_total: 0,
                invoice_number: 0,
                date: '',
                items: [{
                    name: '',
                    rate: 0,
                    unit: '',
                    quantity: 0,
                    total: 0,
                }]
            },
            deletedItemsID: [],
            products: [],
        };
    },
    components:{
        SaleItem,
        Show
    },
    async created() {
        if(this.$route.name == 'SaleCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'SaleEdit'){
            this.operation = 'edit';
            this.sale.id = this.$route.params.id;
        } else {
            this.operation = 'view';
            let paths = this.$route.path.split("/");
            this.sale.id = paths[3];
        }
        if(this.sale.id){
            axios.get(`/api/sales/edit/`+this.sale.id)
                    .then((res) => {
                        console.log('res:', res);
                        this.sale.id = res.data.id;
                        this.sale.description = res.data.description;
                        this.sale.invoice_total = res.data.invoice_total;
                        this.sale.date = res.data.date;
                        this.sale.items = res.data.sale_items;
                    });
            console.log('Sale edit', this.sale)
        }


        await axios.get(`/api/bom-sale-items?slug=` + this.$route.params.slug)
                .then((res) => {
                    console.log('res====================', res.data.data);
                    this.products = res.data.data;
                });
    },
    methods: {

        addItem(){
            var obj = {...this.singleItem};
            this.sale.items.push(obj);
        },
        changeInvoiceTotal(val){
            console.log('changeInvoiceTotal:', val);
            this.sale.invoice_total = val;
        },
        submitForm(){
            console.log(this.sale)
        },
        async createSale() {
            console.log('createSale:', this.sale)
            this.sale.slug = this.$route.params.slug;
            try {
                await axios.post(`/api/sales`, this.sale)
                            .then((res) => {
                                console.log('res:', res, this.$router);
                                this.$router.push('/'+ this.$route.params.slug + '/sales');
                            });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateSale() {
            this.sale.deletedItemsID = this.deletedItemsID;
            console.log('updateSale:', this.sale)
            try {
                await axios.post(`/api/sales/`+this.sale.id, this.sale).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/'+ this.$route.params.slug + '/sales');
                        });
            } catch (error) {
                showErrors(error);
                console.error(error);
            }
        },

        downloadPdf(){
            window.location.href = `/sales/download-pdf/`+this.sale.id;
        },
    },
    computed: {
        formattedTotal() {
            return this.sale.invoice_total.toFixed(2); // Apply precision formatting
        },
    },
};
</script>


<style scoped>
    .demo-sale {
        padding-left: 10px;
    }
    .el-form-item__label {
        font-weight:bold !important;
        color: #212529;
    }
    th {
        padding-left: 0 !important;
        padding-top: 0 !important;
    }
    .required-indicator {
        color: red;
        margin-right: 3px;
    }
</style>>
