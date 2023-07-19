<template>
    <tr>
        <td>
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
            <el-text class="mx-1 text-danger" v-if="isNewItem">This will create a new item</el-text>
        </td>
        <td>
            <el-input v-model="item.name" type="text" placeholder="Name" :disabled="operation === 'view'"/>
        </td>
        <td>
            <el-input v-model="item.description" type="text" placeholder="Description" :disabled="operation === 'view'"/>
        </td>
        <td>
            <!-- <el-input v-model="formattedRate" type="number" placeholder="Rate" :disabled="operation === 'view'"
                        @blur="calculateTotal" /> -->
            <el-input v-model="formattedRate" type="number" placeholder="Rate" :disabled="operation === 'view'"
                        :controls="false"
                        :step="0.01"
                        @keyup="calculateTotal" />
        </td>
        <td>
            <el-input v-model="item.unit" type="text" placeholder="Unit" :disabled="operation === 'view'"/>
        </td>
        <td>
            <el-input 
                v-model="item.quantity" 
                type="number" 
                placeholder="Quantity" 
                :disabled="operation === 'view'"
                :step="0.01"
                @keyup="calculateTotal" />
        </td>
        <td>
            <el-input v-model="formattedTotal" type="text" placeholder="Total" disabled style="color: #000000" />
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
    name:'BillItem',
    props: ['item', 'bill', 'operation', 'deletedItemsID', 'index', 'billItems', 'products'],
    data() {
        return {
            myCustomClass: 'text-start',
            isNewItem: false,
        };
    },
    methods: {
        skuChangeHandler() {
            console.log('skuChangeHandler');
        },
        calculateTotal() {
            this.item.total = parseFloat(this.item.rate) * parseFloat(this.item.quantity);
            let summation = this.bill.items.reduce((total, element) => total + element.total, 0);
            console.log('summation:', summation, this.item.total);
            this.$emit('changeInvoiceTotal', summation);
        },
        getDeletedItemsId(index, id) {
            if(id) {
                this.deletedItemsID.push(id);
            }
            this.deleteRow(index);
            this.calculateTotal();
            // console.log('deletedItemsID', id, this.deletedItemsID);
        },
        deleteRow(index) {
            this.billItems.splice(index, 1);
        },

        querySearch(queryString, cb) {
            if(this.item.sku){
                this.isNewItem = true;

                this.item.name = '';
                this.item.rate = 0.00;
                this.item.unit = '';
                this.item.description = '';
                this.item.quantity = 0;
            }else{
                this.isNewItem = false;
            }
            // var productArray = Object.values(this.products)
            const products = Object.values(this.products);
            const results = queryString
                ? products.filter(this.createFilter(queryString))
                : products;
            console.log('results', results);
            console.log('queryString', queryString);
            console.log('skuuuuu', this.item.sku);
            // call callback function to return suggestions
            cb(results);
        },
        createFilter(queryString) {
            return (product) => {
                return product.sku.toLowerCase().includes( queryString.toLowerCase());
            };
        },
        handleSelect(product) {
            this.isNewItem = false;
            // console.log('handleSelect:', product, this.billItems);
            if (this.billItems &&  this.itemExist(product)) {
                ElNotification({
                    type: 'error',
                    title: 'Error',
                    message: 'Item already exists in the list',
                });
                // this.billItems.forEach((item) => {
                //     // console.log('item in forech', item)

                //     if (item && item.sku === product.sku) {
                //         item.quantity++;
                //     }
                // });
            } else {
                this.item.sku = product.sku;
                this.item.name = product.name;
                this.item.rate = product.rate;
                this.item.description = product.description;
            }
            this.calculateTotal();
        },
        itemExist(product) {
            // console.log('itemExist', product);
            return this.billItems.some(el => {
                return el.sku === product.sku;
            });
        },
    },
    computed: {
        formattedRate: {
            get() {
                return this.item.rate.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 4}); // Apply precision formatting when retrieving the value
            },
            set(value) {
                this.item.rate = Number(value); // Convert the input value back to a number
            },
        },
        formattedTotal() {
            return this.item.total ? this.item.total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 4}) : '0.00'; // Apply precision formatting
        },
    }
};
</script>

<style scoped>
.el-autocomplete.inline-input {
  width: 100;
  width: 100%;
}
td {
  vertical-align: top;
}


.my-autocomplete li {
  line-height: normal;
  padding: 7px;
}
.my-autocomplete li .name {
  text-overflow: ellipsis;
  overflow: hidden;
}
.my-autocomplete li .addr {
  font-size: 12px;
  color: #b4b4b4;
}
.my-autocomplete li .highlighted .addr {
  color: #ddd;
}
td{
    padding-left: 0 !important;
    padding-top: 0 !important;
}
.myCustomClass {
    text-align: 'left';
}

/* input {
    text-align: 'left';
    width: '100%';
} */
.el-input-number .el-input__inner {
    text-align: left !important;
}

.input-group-text {
    text-align:  left !important;
}
</style>
