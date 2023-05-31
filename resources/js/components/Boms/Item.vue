<template>
    <tr>
        <td>
            <!-- <el-input v-model="item.sku" type="text" placeholder="SKU" :disabled="operation === 'view'" /> -->
            <el-autocomplete
                v-model="item.sku"
                :fetch-suggestions="querySearch"
                popper-class="my-autocomplete"
                placeholder="SKU"
                @select="handleSelect"
                style="width:100%"
                :disabled="operation === 'view'"
            >
                <template #default="{ item }">
                    <div class="value">{{ item.sku }}</div>
                </template>
            </el-autocomplete>
        </td>
        <td>
            <el-input v-model="item.rate" type="text" placeholder="Rate" :disabled="operation === 'view'"
                        @blur="calculateTotal"  />
        </td>
        <td>
            <el-input v-model="item.quantity" type="text" placeholder="Quantity" :disabled="operation === 'view'"
                        @blur="calculateTotal" />
        </td>
        <td>
            <el-input v-model="item.total" type="text" placeholder="Total" disabled style="color: #000000" />
        </td>
        <td v-if="operation !== 'view'">
            <el-button type="danger" @click="getDeletedItemsId(index, item.id)" style="width:100%; padding-right:0;">
                Delete
            </el-button>
        </td>
    </tr>
</template>


<script>

export default {
    name:'BomItem',
    props: ['item', 'bom', 'operation', 'deletedItemsID', 'index', 'bomItems', 'products'],
    data() {
        return {
        };
    },
    mounted() {
    },
    created() {  },
    methods: {
        calculateTotal() {
            this.item.total = parseFloat(this.item.rate) * parseFloat(this.item.quantity);
            let summation = this.bom.items.reduce((total, element) => total + element.total, 0);
            console.log('summation:', summation, this.item.total);
            this.$emit('changeInvoiceTotal', summation);
        },
        getDeletedItemsId(index, id) {
            if(id) {
                this.deletedItemsID.push(id);
            }
            this.deleteRow(index);
            console.log('deletedItemsID', id, this.deletedItemsID);
        },
        deleteRow(index) {
            this.bomItems.splice(index, 1);
        },

        querySearch(queryString, cb) {
            // var productArray = Object.values(this.products)
            const products = Object.values(this.products);
            const results = queryString
                ? products.filter(this.createFilter(queryString))
                : products;
            console.log('results', results, queryString);
            // call callback function to return suggestions
            cb(results);
        },
        createFilter(queryString) {
            return (product) => {
                return product.sku.toLowerCase().includes( queryString.toLowerCase());
            };
        },
        handleSelect(product) {
            // console.log('handleSelect:', product, this.bomItems);
            if (this.bomItems &&  this.itemExist(product)) {
                ElNotification({
                    type: 'error',
                    title: 'Error',
                    message: 'Item already exists in the list',
                });
            } else {
                this.item.sku = product.sku;
            }
        },
        itemExist(product) {
            // console.log('itemExist', product);
            return this.bomItems.some(el => {
                return el.sku === product.sku;
            });
        },
    },
};
</script>

<style scoped>
.el-autocomplete.inline-input {
  width: 100;
  width: 100%;
}
td{
  vertical-align: top;
    padding-left: 0 !important;
    padding-top: 0 !important;
}
</style>
