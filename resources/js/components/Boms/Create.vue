<template>
    <el-form ref="ruleFormRef" :model="bom" class="demo-bom"
        label-position="top"
        status-icon
    >
        <el-text tag="b"  v-if="operation === 'view'" type="primary" size="large">View BOM</el-text>
        <el-text tag="b"  v-if="operation === 'edit'" type="primary" size="large">Edit BOM</el-text>
        <el-text tag="b"  v-if="operation === 'create'" type="primary" size="large">Create BOM</el-text>


        <el-card class="box-card">
            <el-form-item label="Name" prop="name" required>
                <el-input v-model="bom.name" type="text" :disabled="operation === 'view'" />
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
                            <bomItem
                                v-for="(item, index) in bom.items"
                                :key="index"
                                :index="index"
                                :item="item"
                                :bomItems="bom.items"
                                :bom="bom"
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


            <el-row>

<!--                <el-col class="col-4">-->
<!--                    <el-form-item label="">-->
<!--                        <el-input v-model="bom.subTotal" type="hidden" placeholder="Sub total" @keyup="" />-->
<!--                    </el-form-item>-->
<!--                </el-col>-->

                <el-col>
                    <el-form-item label="Estimated profit">
                        <el-input v-model="bom.estimatedProfit" type="number" placeholder="Estimated profit" @keyup="changeEstimatedProfit" />
                    </el-form-item>
                </el-col>

                <el-col>
                    <el-form-item label="Invoice Total">
                        <el-input v-model="formattedTotal" type="text" placeholder="Invoice Total" disabled />
                    </el-form-item>
                </el-col>
            </el-row>


            <el-row>
                <el-col>

                    <el-button v-if="operation === 'create'" type="primary" @click="createBom" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateBom" class="me-2">Update</el-button>
                    <router-link :to="'/boms'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>
                </el-col>
            </el-row>

        </el-card>

    </el-form>
</template>

<script >

import BomItem from "./Item.vue";
import { showErrors } from '@/utils/helper.js'

export default {
    name: 'BomCreate',
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
            bom : {
                id: null,
                invoice_total: 0,
                name: '',
                subTotal: '',
                estimatedProfit: 0,
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
        BomItem
    },
    async created() {
        if(this.$route.name == 'BomCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'BomEdit'){
            this.operation = 'edit';
            let paths = this.$route.path.split("/");
            this.bom.id = paths[3];
        } else {
            this.operation = 'view';
            let paths = this.$route.path.split("/");
            this.bom.id = paths[3];
        }
        if(this.bom.id){
            axios.get(`/api/boms/edit/`+this.bom.id).
                    then((res) => {
                        console.log('res:', res);
                        this.bom.id = res.data.id;
                        this.bom.name = res.data.name;
                        this.bom.invoice_total = res.data.invoice_total;
                        this.bom.items = res.data.bom_items;
                    });
            // console.log('BOM edit', this.bom)
        }

        await axios.get(`/api/products`).
                then((res) => {
                    this.products = res.data;
                });
    },
    methods: {

        addItem(){
            var obj = {...this.singleItem};
            this.bom.items.push(obj);
        },
        changeEstimatedProfit(){
            //console.log("Be good ss ", this.bom.estimatedProfit)
            //console.log(this.bom.estimatedProfit)
            return this.bom.estimatedProfit
            //console.log(this.bom.estimatedProfit)
        },
        changeInvoiceTotal(val){
            this.bom.subTotal = val
            //console.log("totalss val", val)
            //this.bom.invoice_total = val;
        },
        async createBom() {
            console.log('createBom:', this.bom)
            try {
                const hasAllElems = this.bom.items.every(elem => Object.values(this.products).some(product => product.sku === elem.sku));
                console.log('hasAllElems', hasAllElems);
                if(hasAllElems) {
                    await axios.post(`/api/boms`, this.bom).
                            then((res) => {
                                console.log('res:', res, this.$router);
                                this.$router.push('/boms');
                            });
                } else {
                    ElNotification({
                        type: 'error',
                        title: 'Error',
                        message: 'Some bom items doesn\'t exist in product list',
                    });
                }
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateBom() {
            this.bom.deletedItemsID = this.deletedItemsID;
            // console.log('updateBom:', this.bom)
            try {
                var hasAllElems = this.bom.items.every(elem => {
                    // console.log('elem', elem, this.products);
                    return Object.values(this.products).some(product => product.sku === elem.sku)
                });
                if(hasAllElems) {
                    await axios.post(`/api/boms/`+this.bom.id, this.bom).
                            then((res) => {
                                console.log('res:', res, this.$router);
                                this.$router.push('/boms');
                            });
                } else {
                    ElNotification({
                        type: 'error',
                        title: 'Error',
                        message: 'Some bom items doesn\'t exist in product list',
                    });
                }
            } catch (error) {
                showErrors(error);
                console.error(error);
            }
        },
    },
    computed: {
        formattedTotal() {
            this.bom.invoice_total = this.bom.subTotal + ((this.bom.estimatedProfit * this.bom.subTotal) / 100)
            return this.bom.subTotal + ((this.bom.estimatedProfit * this.bom.subTotal) / 100)
            //const invoiceTotal =
            //return  invoiceTotal; // Apply precision formatting
        },

    },
};
</script>


<style scoped>
    .demo-bom {
        padding: 10px;
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
