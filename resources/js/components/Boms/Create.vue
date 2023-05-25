<template>
    <el-form ref="ruleFormRef" :model="bom" :rules="rules" class="demo-bom"
        label-position="top"
        status-icon
    >
        <el-text tag="b"  v-if="operation === 'view'" type="primary" size="large">View BOM</el-text>
        <el-text tag="b"  v-if="operation === 'edit'" type="primary" size="large">Edit BOM</el-text>
        <el-text tag="b"  v-if="operation === 'create'" type="primary" size="large">Create BOM</el-text>

        <el-form-item label="Name" prop="name">
            <el-input v-model="bom.name" type="text" :disabled="operation === 'view'" />
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
                            <th width="50%">SKU</th>
                            <th width="50%">Quantity</th>
                            <!-- <th width="10%">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <bomItem
                            v-for="(item, index) in bom.items"
                            :key="index"
                            :item="item"
                            :bom="bom"
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
        <el-form-item>
            <el-button v-if="operation === 'create'" type="primary" @click="createBom">Create</el-button>
            <el-button v-if="operation === 'edit'" type="primary" @click="updateBom">Update</el-button>
            <router-link :to="'/boms'">
                <el-button type="info">Back</el-button>
            </router-link>
        </el-form-item>
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
            },
            bom : {
                id: null,
                name: '',
                items: [{
                    'sku': null,
                    'quantity': 0,
                }]
            }
        };
    },
    components:{
        BomItem
    },
    created() {
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
        console.log('Route Name: ', this.$route.name);
        if(this.bom.id){
            axios.get(`/api/boms/edit/`+this.bom.id).
                    then((res) => {
                        console.log('res:', res);
                        this.bom.id = res.data.id;
                        this.bom.name = res.data.name;
                        this.bom.items = res.data.bom_items;
                    });
            console.log('BOM edit', this.bom)
        }
    },
    methods: {

        addItem(){
            var obj = {...this.singleItem};
            this.bom.items.push(obj);
        },
        changeInvoiceTotal(val){
            console.log('changeInvoiceTotal:', val);
            this.bom.invoice_total = val;
        },
        submitForm(){
            console.log(this.bom)
        },
        async createBom() {
            console.log('createBom:', this.bom)
            try {
                await axios.post(`/api/boms`, this.bom).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/boms');
                        });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateBom() {
            console.log('updateBom:', this.bom)
            try {
                await axios.post(`/api/boms/`+this.bom.id, this.bom).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/boms');
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
    .demo-bom {
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
