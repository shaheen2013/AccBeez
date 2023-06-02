<template>
    <el-form :model="sale" class="demo-sale"
        label-position="top"
        status-icon
    >
        <el-text tag="b" v-if="operation === 'edit'" type="primary" size="large">Edit Sale</el-text>
        <el-text tag="b" v-if="operation === 'create'" type="primary" size="large">Create Sale</el-text>


        <el-card class="box-card">
            <el-form-item label="Date" required>
                <el-date-picker v-model="sale.date" type="date" label="Pick a date" placeholder="Pick a date"
                    format="YYYY-MM-DD"
                    value-format="YYYY-MM-DD"
                    style="width: 100%" />
            </el-form-item>

            <el-form-item label="Bom" required>
                <el-select v-model="selectedBomId"
                    class="m-2"
                    placeholder="Select BOM"
                    style="width:100%;"
                    @change="changeBomId"
                >
                    <el-option
                        v-for="_bom in boms"
                        :key="_bom.id"
                        :label="_bom.name"
                        :value="_bom.id"
                    />
                </el-select>
            </el-form-item>

            <!-- <el-row>
                <el-col :span="24">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th width="30%">
                                    <span class="required-indicator">*</span>
                                    <span>SKU</span>
                                </th>
                                <th width="20%">
                                    <span class="required-indicator">*</span>
                                    <span>Rate</span>
                                </th>
                                <th width="20%">
                                    <span class="required-indicator">*</span>
                                    <span>Quantity</span>
                                </th>
                                <th width="20%">
                                    <span class="required-indicator">*</span>
                                    <span>Item Total</span>
                                </th>
                                <th width="10%">Actions</th>
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
                    <el-button type="info" @click="addItem" v-if="operation === 'create' || operation === 'edit'"
                                class="mb-3">
                        Add
                    </el-button>
                </el-col>
            </el-row> -->

            <el-form-item label="Amount">
                <el-input v-model="formattedAmount" type="number" placeholder="Amount" />
            </el-form-item>


            <el-row>
                <el-col>
                    <el-button v-if="operation === 'create'" type="primary" @click="createSale" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateSale" class="me-2">Update</el-button>
                    <router-link :to="'/sales'">
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
            selectedBomId: null,
            // singleItem: {
            //     'sku': null,
            //     'quantity': 0,
            //     'rate': 0,
            //     'total': 0,
            // },
            boms: [],
            bom: {},
            sale : {
                id: null,
                name: '',
                amount: 0,
                bom_id: null,
                date: '',
                // items: [{
                //     'sku': null,
                //     'quantity': 0,
                //     'rate': 0,
                //     'total': 0,
                // }]
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
            let paths = this.$route.path.split("/");
            this.sale.id = paths[3];
        } else {
            this.operation = 'view';
            let paths = this.$route.path.split("/");
            this.sale.id = paths[3];
        }
        console.log('Route Name: ', this.$route.name);
        if(this.sale.id){
            axios.get(`/api/sales/edit/`+this.sale.id).
                    then((res) => {
                        console.log('res:', res);
                        this.sale.id = res.data.id;
                        this.sale.amount = res.data.amount;
                        this.selectedBomId = res.data.bom_id;
                        this.sale.date = res.data.date;
                        this.sale.items = res.data.sale_items;
                    });
            console.log('Sale edit', this.sale)
        }


        // await axios.get(`/api/products`).
        //         then((res) => {
        //             this.products = res.data;
        //             console.log('products:', this.products);
        //         });
        await axios.get(`/api/boms`).
                then((res) => {
                    this.boms = res.data;
                    console.log('boms:', this.boms);
                });
    },
    methods: {
        addItem(){
            var obj = {...this.singleItem};
            this.sale.items.push(obj);
        },
        changeInvoiceTotal(val){
            console.log('changeInvoiceTotal:', val);
            this.sale.amount = val;
        },
        async changeBomId(){
            await axios.get('/api/boms/edit/' + this.selectedBomId)
                .then(response => {
                    var data = response.data;
                    this.bom = data;
                    this.bom.id = data.id;
                    this.bom.name = data.name;
                    this.sale.items = data.bom_items;
                    // this.sale.amount = data.invoice_total;
                    console.log('changeBomId: ', this.bom, data, this.selectedBomId);
                    console.log('this.sale.items: ', this.sale.items);
                });
        },
        async createSale() {
            this.sale.bom_id = this.selectedBomId;
            try {
                await axios.post(`/api/sales`, this.sale).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/sales');
                        });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateSale() {
            this.sale.bom_id = this.selectedBomId;
            this.sale.deletedItemsID = this.deletedItemsID;
            console.log('updateSale:', this.sale)
            try {
                await axios.post(`/api/sales/`+this.sale.id, this.sale).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/sales');
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
        formattedAmount: {
            get() {
                return this.sale.amount.toFixed(2); // Apply precision formatting when retrieving the value
            },
            set(value) {
                this.sale.amount = Number(value); // Convert the input value back to a number
            },
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
