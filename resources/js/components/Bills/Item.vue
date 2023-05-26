<template>
    <tr>
        <td>
            <el-input v-model="item.sku" type="text" placeholder="SKU" :disabled="operation === 'view'" />
        </td>
        <td>
            <el-input v-model="item.rate" type="text" placeholder="Rate" :disabled="operation === 'view'"
                        @blur="calculateTotal" />
        </td>
        <td>
            <el-input v-model="item.quantity" type="text" placeholder="Quantity" :disabled="operation === 'view'"
                        @blur="calculateTotal" />
        </td>
        <td>
            <el-input v-model="item.total" type="text" placeholder="Total" disabled style="color: #000000" />
        </td>
    </tr>
</template>



<script>

export default {
    name:'BillItem',
    props: ['item', 'bill', 'operation'],
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
            // console.log('calculateTotal', this.item.total);
            let summation = this.bill.items.reduce((total, element) => total + element.total, 0);
            console.log('summation:', summation);
            this.$emit('changeInvoiceTotal', summation);
        }
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
</style>
