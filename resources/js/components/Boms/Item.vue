<template>
    <tr>
        <td>
            <el-input v-model="item.sku" type="text" placeholder="SKU" :disabled="operation === 'view'" />
        </td>
        <td>
            <el-input v-model="item.quantity" type="text" placeholder="Quantity" :disabled="operation === 'view'" />
        </td>
    </tr>
</template>



<script>

export default {
    name:'BillItem',
    props: ['item', 'bom', 'operation'],
    data() {
        return {
        };
    },
    mounted() {
    },
    created() {  },
    methods: {
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
