<template>
    <el-form ref="ruleFormRef" :model="bill" :rules="rules" label-width="120px" class="demo-bill"
        status-icon>
        <el-form-item label="Date" required>
            <el-col :span="24">
                <el-form-item prop="date1">
                    <el-date-picker v-model="bill.date1" type="date" label="Pick a date" placeholder="Pick a date"
                        style="width: 100%" />
                </el-form-item>
            </el-col>
        </el-form-item>
        <el-form-item label="Activity form" prop="desc">
            <el-input v-model="bill.desc" type="textarea" />
        </el-form-item>


        <el-row>
            <el-col :span="18" :offset="2">
                <h4>Items</h4>
            </el-col>
        </el-row>

        <el-row :gutter="10">
            <el-col :span="22" :offset="2">
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
                        />
                    </tbody>
                </table>
                <el-button type="primary" plain @click="addItem">
                    Add
                </el-button>
            </el-col>

        </el-row>

        <el-form-item>
            <el-button type="primary" @click="submitForm">
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

                    'product_id': null,
                    'quantity': null,
                    'rate': null,
                    'item_total': null,
            },
            bill : {
                desc: '',
                items: [{
                    'product_id': null,
                    'quantity': null,
                    'rate': null,
                    'item_total': null,
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
        remove(index, id) {
            if (this.Item.length > 1) {
                this.$confirm(
                    'This will permanently delete the file. Continue?',
                    'Warning',
                    {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning',
                    }
                )
                .then(() => {
                    if (this.mood === 'create' || id === undefined) {
                    this.Item.splice(index, 1);
                    } else {
                        axios.delete('/api/delete-purchase-order-item/' + id).then((res) => {
                            console.log(res);
                        });
                    }

                    this.$message({
                        type: 'success',
                        message: 'Delete completed',
                    });
                })
                .catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Delete canceled',
                    });
                });
            }
        },
        checkValidation(product_id){
            console.log('Item: ',this.Item);
            let pro = this.Item.find(i => i.product_id === product_id);
            if(pro){
                this.item.product_id = null;
                this.$message({
                    message:
                    'Product is already added. Please increase quantity',
                    type: 'error',
                    duration: 5 * 1000,
                });
            } else {
                this.item.product_id = product_id;
                var item =  this.products.find( p => p.id == product_id );
                this.item["product_name"] = item.name;
                this.item["sell_unit_name"] = item.sell_unit_name;
                this.item["purchase_unit_name"] = item.purchase_unit_name;
                this.item["sell_unit_id"] = item.sell_unit_id;
                this.item["purchase_unit_id"] = item.purchase_unit_id;
                console.log('item: ', this.item);
            }
        },
        getDeletedItemsId(index, id) {
            if(id) {
                this.deletedItemsID.push(id);
            }
            this.deleteRow(index);
            console.log('deletedItemsID',id,this.deletedItemsID);
        },
        deleteRow(index) {
            this.Item.splice(index, 1);
        },


        addItem(){
            // this.bill.items.push(this.singleItem);
            var obj = {...this.singleItem};
            this.bill.items.push(obj);
        },
        submitForm(){
            console.log(this.bill)
        }
    },
};
</script>
