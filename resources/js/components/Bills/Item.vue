<template>
    <tr>
        <td>
            <el-input v-model="item.product_id" type="text" placeholder="Product" />
        </td>
        <td>
            <el-input v-model="item.rate" type="text" placeholder="Rate" />
        </td>
        <td>
            <el-input v-model="item.quantity" type="text" placeholder="Quantity" />
        </td>
        <td>
            <el-input v-model="item.item_total" type="text" placeholder="Item Total" />
        </td>
    </tr>
</template>



<script>

export default {
    name:'BillItem',
    props: {
        item: {
            type: Object,
            required: true
        }
    },
//   props: ['item', 'items'],
  data() {
    return {
        routeName: null,
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
