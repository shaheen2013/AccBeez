<template>
    <tr>
        <td>
            <el-autocomplete
                v-model="item.name"
                :fetch-suggestions="querySearch"
                popper-class="my-autocomplete"
                placeholder="Name"
                @select="handleSelect"
                style="width:100%"
            >
                <template #default="{ item }">
                    <div class="value">{{ item.name }}</div>
                </template>
            </el-autocomplete>
        </td>
        <!-- <td>
            <el-input v-model="item.name" type="text" placeholder="Name" :disabled="operation === 'view'"/>
        </td> -->
        <td>
            <el-input-number v-model="item.rate" type="text" placeholder="Rate" :disabled="operation === 'view'"
                        :className="text-start"
                        :controls="false"
                        :precision="2"
                        @blur="calculateTotal" />
        </td>
        <td>
            <el-input v-model="item.unit" type="text" placeholder="Unit" :disabled="operation === 'view'"/>
        </td>
        <td>
            <el-input v-model="item.quantity" type="number" placeholder="Quantity" :disabled="operation === 'view'"
                        @blur="calculateTotal" />
        </td>
        <td>
            <el-input-number v-model="item.total" type="text" placeholder="Total" disabled
                        :className="text-start"
                        :controls="false"
                        :precision="2" />
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
    name:'BomSaleItem',
    props: ['item', 'bomSale', 'operation', 'deletedItemsID', 'index', 'bomSaleItems', 'boms'],
    data() {
        return {

        };
    },
    mounted() {
        this.item.total = this.item.total.toFixed(2);
    },
    created() {  },
    methods: {
        calculateTotal() {
            this.item.total = parseFloat(this.item.rate) * parseFloat(this.item.quantity);
            let summation = this.bomSale.items.reduce((total, element) => total + element.total, 0);
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
            this.bomSaleItems.splice(index, 1);
        },

        querySearch(queryString, cb) {
            // var bomArray = Object.values(this.boms)
            const boms = Object.values(this.boms);
            const results = queryString
                ? boms.filter(this.createFilter(queryString))
                : boms;
            // console.log('results', results, queryString);
            // call callback function to return suggestions
            cb(results);
        },
        createFilter(queryString) {
            return (bom) => {
                // console.log('bom in createFilter', bom);
                return bom.name.toLowerCase().includes( queryString.toLowerCase());
            };
        },
        handleSelect(bom) {
            console.log('handleSelect:', bom, this.bomSaleItems);
            if (this.bomSaleItems &&  this.itemExist(bom)) {
                ElNotification({
                    type: 'error',
                    title: 'Error',
                    message: 'Item already exists in the list',
                });
                // this.bomSaleItems.forEach((item) => {
                //     // console.log('item in forech', item)

                //     if (item && item.name === bom.name) {
                //         item.quantity++;
                //     }
                // });
            } else {
                this.item.name = bom.name;
                this.item.rate = bom.invoice_total;
            }
            this.calculateTotal();
        },
        itemExist(bom) {
            // console.log('itemExist', bom);
            return this.bomSaleItems.some(el => {
                return el.name === bom.name;
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
