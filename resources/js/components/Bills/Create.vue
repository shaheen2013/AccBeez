<template>
    <el-form ref="ruleFormRef" :model="bill" class="demo-bill"
        label-position="top"
        status-icon
    >
        <el-text tag="b" v-if="operation === 'view'" type="primary" size="large">View Bill</el-text>
        <el-text tag="b" v-if="operation === 'edit'" type="primary" size="large">Edit Bill</el-text>
        <el-text tag="b" v-if="operation === 'create'" type="primary" size="large">Create Bill</el-text>


        <el-card class="box-card">
            <el-form-item label="Date" required>
                <el-date-picker v-model="bill.date" type="date" label="Pick a date" placeholder="Pick a date"
                    format="YYYY-MM-DD"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                    :disabled="operation === 'view'" />
            </el-form-item>
            <el-form-item label="Description" prop="description">
                <el-input v-model="bill.description" type="textarea" :disabled="operation === 'view'" />
            </el-form-item>


            <el-row>
                <el-col :span="24">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th :style="operation === 'view' ? { 'width': '40%' } : { 'width': '30%' }">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>SKU</span>
                                </th>
                                <th width="20%">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Rate</span>
                                </th>
                                <th width="20%">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Quantity</span>
                                </th>
                                <th width="20%">
                                    <span class="required-indicator" v-if="operation !== 'view'">*</span>
                                    <span>Item Total</span>
                                </th>
                                <th v-if="operation !== 'view'" width="10%">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <billItem
                                v-for="(item, index) in bill.items"
                                :key="index"
                                :index="index"
                                :item="item"
                                :billItems="bill.items"
                                :bill="bill"
                                :operation="operation"
                                :products="products"
                                @changeInvoiceTotal="changeInvoiceTotal"
                                :deletedItemsID="deletedItemsID"
                            />
                        </tbody>
                    </table>
                    <el-button type="info" @click="addItem" v-if="operation === 'create' || operation === 'edit'"
                                class="mb-3">
                        Add
                    </el-button>
                </el-col>

            </el-row>

            <el-form-item label="Invoice Total">
                <el-input v-model="bill.invoice_total" type="text" placeholder="Invoice Total" disabled />
            </el-form-item>

            <el-row>
                <el-col>
                    <el-button v-if="operation === 'create'" type="primary" @click="createBill" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateBill" class="me-2">Update</el-button>
                    <router-link :to="'/bills'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>
                </el-col>
            </el-row>
        </el-card>

    </el-form>
</template>

<script >

import BillItem from "./Item.vue";
import { showErrors } from '@/utils/helper.js'

export default {
    name: 'BillCreate',
    data() {
        return {
            routeName: '',
            operation: 'create',
            singleItem: {
                'sku': null,
                'quantity': 0,
                'rate': 0,
                'total': 0,
            },
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
            deletedItemsID: [],
            products: [],
        };
    },
    components:{
        BillItem
    },
    async created() {
        if(this.$route.name == 'BillCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'BillEdit'){
            this.operation = 'edit';
            let paths = this.$route.path.split("/");
            this.bill.id = paths[3];
        } else {
            this.operation = 'view';
            let paths = this.$route.path.split("/");
            this.bill.id = paths[3];
        }
        console.log('Route Name: ', this.$route.name);
        if(this.bill.id){
            axios.get(`/api/bills/edit/`+this.bill.id).
                    then((res) => {
                        console.log('res:', res);
                        this.bill.id = res.data.id;
                        this.bill.description = res.data.description;
                        this.bill.invoice_total = res.data.invoice_total;
                        this.bill.date = res.data.date;
                        this.bill.items = res.data.bill_items;
                    });
            console.log('Bill edit', this.bill)
        }


        await axios.get(`/api/products`).
                then((res) => {
                    this.products = res.data;
                    console.log('products:', this.products);
                });
    },
    methods: {

        addItem(){
            var obj = {...this.singleItem};
            this.bill.items.push(obj);
        },
        changeInvoiceTotal(val){
            console.log('changeInvoiceTotal:', val);
            this.bill.invoice_total = val;
        },
        submitForm(){
            console.log(this.bill)
        },
        async createBill() {
            console.log('createBill:', this.bill)
            try {
                await axios.post(`/api/bills`, this.bill).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/bills');
                        });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateBill() {
            this.bill.deletedItemsID = this.deletedItemsID;
            console.log('updateBill:', this.bill)
            try {
                await axios.post(`/api/bills/`+this.bill.id, this.bill).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/bills');
                        });
            } catch (error) {
                showErrors(error);
                console.error(error);
            }
        },
    },
};
</script>


<style scoped>
    .demo-bill {
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
