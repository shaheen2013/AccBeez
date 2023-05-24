<template>
    <el-form ref="ruleFormRef" :model="bill" :rules="rules" class="demo-bill"
        label-position="top"
        status-icon>
        <el-form-item label="Date" required>
            <el-date-picker v-model="bill.date" type="date" label="Pick a date" placeholder="Pick a date"
                format="YYYY-MM-DD"
                value-format="YYYY-MM-DD"
                style="width: 100%" />
        </el-form-item>
        <el-form-item label="Description" prop="description">
            <el-input v-model="bill.description" type="textarea" />
        </el-form-item>



        <!-- <el-row>
            <el-col :span="11">
                <el-form-item label="Date" >
                    <el-date-picker v-model="bill.date" type="date"
                        label="Pick a date"
                        placeholder="Pick a date"
                        style="width: 100%" />
                </el-form-item>
            </el-col>
            <el-col class="text-center" :span="1" style="margin: 0 0.5rem">-</el-col>
            <el-col :span="11">
                <el-form-item label="Invoice Total">
                    <el-input v-model="bill.invoice_total" type="text" placeholder="Invoice Total" />
                </el-form-item>
            </el-col>
        </el-row> -->


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
                            <th width="30%">Product</th>
                            <th width="20%">Rate</th>
                            <th width="20%">Quantity</th>
                            <th width="30%">Item Total</th>
                            <!-- <th width="10%">Action</th> -->
                            <!-- <th width="10%" v-if="bill.status !== 'approved'">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <billItem
                            v-for="(item, index) in bill.items"
                            :key="index"
                            :item="item"
                            :bill="bill"
                            @changeInvoiceTotal="changeInvoiceTotal"
                        />
                    </tbody>
                </table>
                <el-button type="info" @click="addItem">
                    Add
                </el-button>
            </el-col>

        </el-row>

        <el-form-item label="Invoice Total">
            <el-input v-model="bill.invoice_total" type="text" placeholder="Invoice Total" disabled />
        </el-form-item>

        <el-form-item>
            <el-button type="primary" @click="createBill">
                Create
            </el-button>
            <el-button @click="resetForm(ruleFormRef)">Reset</el-button>
        </el-form-item>
    </el-form>
</template>

<script >

import BillItem from "./Item.vue";
export default {
    name: 'BillCreate',
    data() {
        return {
            singleItem: {
                'product_sku': null,
                'quantity': 0,
                'rate': 0,
                'total': 0,
            },
            bill : {
                description: '',
                invoice_total: 0,
                date: '',
                items: [{
                    'product_sku': null,
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
    mounted() {
        // this.routeName = this.$route.name;
        // console.log('Route Name: ', this.routeName);
    },
    created() {  },
    methods: {

        addItem(){
            // this.bill.items.push(this.singleItem);
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
                            console.log('res:', res);
                        });
            } catch (error) {
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
