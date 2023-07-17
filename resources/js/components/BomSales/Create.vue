<template>
    <el-form ref="ruleFormRef" :model="bomSale" class="demo-bomSale"
        label-position="top"
        status-icon
    >
        <el-text tag="b" v-if="operation === 'view'" type="primary" size="large">View BomSale</el-text>
        <el-text tag="b" v-if="operation === 'edit'" type="primary" size="large">Edit BomSale</el-text>
        <el-text tag="b" v-if="operation === 'create'" type="primary" size="large">Create BomSale</el-text>


        <el-card class="box-card">
            <el-form-item label="Date" required>
                <el-date-picker v-model="bomSale.date" type="date" label="Pick a date" placeholder="Pick a date"
                    format="YYYY-MM-DD"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                    :disabled="operation === 'view'" />
            </el-form-item>
            <el-form-item label="Description" prop="description">
                <el-input v-model="bomSale.description" type="textarea" :disabled="operation === 'view'" />
            </el-form-item>


            <el-row>
                <el-col :span="24">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th width="15%">
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
                            <bomSaleItem
                                v-for="(item, index) in bomSale.items"
                                :key="index"
                                :index="index"
                                :item="item"
                                :bomSaleItems="bomSale.items"
                                :bomSale="bomSale"
                                :operation="operation"
                                :boms="boms"
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
                <el-input v-model="formattedTotal" type="number" placeholder="Invoice Total" disabled />
            </el-form-item>



            <el-row>
                <el-col>
                    <el-button v-if="operation === 'create'" type="primary" @click="createBomSale" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateBomSale" class="me-2">Update</el-button>
                    <router-link :to="'/' + $route.params.slug + '/bomSales'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>

                    <el-button v-if="operation === 'view'" type="primary" @click="downloadPdf" class="me-2">Download PDF</el-button>
                </el-col>
            </el-row>
        </el-card>
    </el-form>
</template>

<script >

import BomSaleItem from "./Item.vue";
import Show from "./Show.vue";
import { showErrors } from '@/utils/helper.js'

export default {
    name: 'BomSaleCreate',
    data() {
        return {
            routeName: '',
            operation: 'create',
            singleItem: {
                name: '',
                rate: 0,
                unit: '',
                quantity: 0,
                total: 0,
            },
            bomSale : {
                id: null,
                description: '',
                invoice_total: 0,
                invoice_number: 0,
                date: '',
                items: [{
                    name: null,
                    rate: 0,
                    unit: '',
                    quantity: 0,
                    total: 0,
                }]
            },
            deletedItemsID: [],
            boms: [],
        };
    },
    components:{
        BomSaleItem,
        Show
    },
    async created() {
        if(this.$route.name == 'BomSaleCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'BomSaleEdit'){
            this.operation = 'edit';
            this.bomSale.id = this.$route.params.id;
        } else {
            this.operation = 'view';
            this.bomSale.id = this.$route.params.id;
        }
        console.log('Route Name: ', this.$route.name);
        if(this.bomSale.id){
            axios.get(`/api/bomSales/edit/`+this.bomSale.id).
                    then((res) => {
                        console.log('res:', res);
                        this.bomSale.id = res.data.id;
                        this.bomSale.description = res.data.description;
                        this.bomSale.invoice_total = res.data.invoice_total;
                        this.bomSale.date = res.data.date;
                        this.bomSale.items = res.data.bom_sale_items;
                    });
            console.log('BomSale edit', this.bomSale)
        }


        await axios.get(`/api/boms/get-all-boms?slug=` + this.$route.params.slug).
                then((res) => {
                    this.boms = res.data;
                    console.log('boms:', res, this.boms);
                });
    },
    methods: {

        addItem(){
            var obj = {...this.singleItem};
            this.bomSale.items.push(obj);
        },
        changeInvoiceTotal(val){
            console.log('changeInvoiceTotal:', val);
            this.bomSale.invoice_total = val;
        },
        submitForm(){
            console.log(this.bomSale)
        },
        async createBomSale() {
            console.log('createBomSale:', this.bomSale)
            try {
                this.bomSale.slug = this.$route.params.slug;
                await axios.post(`/api/bomSales`, this.bomSale).
                        then((res) => {
                            this.$router.push('/' + this.$route.params.slug + '/bomSales');
                        });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateBomSale() {
            this.bomSale.deletedItemsID = this.deletedItemsID;
            
            try {
                await axios.post(`/api/bomSales/`+this.bomSale.id, this.bomSale).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/' + this.$route.params.slug + '/bomSales');
                        });
            } catch (error) {
                showErrors(error);
                console.error(error);
            }
        },

        downloadPdf(){
            window.location.href = `/bomSales/download-pdf/`+this.bomSale.id;
        },
    },
    computed: {
        formattedTotal() {
            return this.bomSale.invoice_total.toFixed(2); // Apply precision formatting
        },
    },
};
</script>


<style scoped>
    .demo-bomSale {
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
