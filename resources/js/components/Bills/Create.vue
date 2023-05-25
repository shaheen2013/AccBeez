<template>
    <el-form ref="ruleFormRef" :model="bill" :rules="rules" class="demo-bill"
        label-position="top"
        status-icon
    >
        <el-text tag="b"  v-if="operation === 'view'" type="primary" size="large">View Bill</el-text>
        <el-text tag="b"  v-if="operation === 'edit'" type="primary" size="large">Edit Bill</el-text>
        <el-text tag="b"  v-if="operation === 'create'" type="primary" size="large">Create Bill</el-text>

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
            <el-col :span="18">
                <h5>Items</h5>
            </el-col>
        </el-row>

        <el-row :gutter="10">
            <el-col :span="24">
                <table >
                    <thead >
                        <tr >
                            <th width="30%">SKU</th>
                            <th width="20%">Rate</th>
                            <th width="20%">Quantity</th>
                            <th width="30%">Item Total</th>
                            <!-- <th width="10%">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <billItem
                            v-for="(item, index) in bill.items"
                            :key="index"
                            :item="item"
                            :bill="bill"
                            :operation="operation"
                            @changeInvoiceTotal="changeInvoiceTotal"
                        />
                    </tbody>
                </table>
                <el-button type="info" @click="addItem" v-if="operation === 'create' || operation === 'edit'">
                    Add
                </el-button>
            </el-col>

        </el-row>

        <el-form-item label="Invoice Total">
            <el-input v-model="bill.invoice_total" type="text" placeholder="Invoice Total" disabled />
        </el-form-item>

        <el-form-item>
            <el-button v-if="operation === 'create'" type="primary" @click="createBill">Create</el-button>
            <el-button v-if="operation === 'edit'" type="primary" @click="updateBill">Update</el-button>
            <router-link :to="'/bills'">
                <el-button type="info">Back</el-button>
            </router-link>
        </el-form-item>
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
            }
        };
    },
    components:{
        BillItem
    },
    created() {
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


<style >
    .demo-bill {
        padding: 10px;
    }
    .el-form-item__label {
        font-weight:bold !important;
        color: #212529;
    }
    /* .el-input.is-disabled .el-input__inner {
        color: #000000 !important;
        cursor: not-allowed;
        border-color: #000000 !important;
    } */
</style>>
