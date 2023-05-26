<template>
    <tr>
        <td>
            <el-input v-model="item.sku" type="text" placeholder="SKU" :disabled="operation === 'view'" />
        </td>
        <td>
            <el-input v-model="item.quantity" type="text" placeholder="Quantity" :disabled="operation === 'view'" />
        </td>
        <td v-if="operation !== 'view'">
            <el-button type="danger" @click="getDeletedItemsId(index, item.id)">
                Delete
            </el-button>
        </td>
    </tr>
</template>



<script>

export default {
    name:'BomItem',
    props: ['item', 'bom', 'operation', 'deletedItemsID', 'index', 'bomItems'],
    data() {
        return {
        };
    },
    mounted() {
    },
    created() {  },
    methods: {
        getDeletedItemsId(index, id) {
            if(id) {
                this.deletedItemsID.push(id);
            }
            this.deleteRow(index);
            console.log('deletedItemsID', id, this.deletedItemsID);
        },
        deleteRow(index) {
            this.bomItems.splice(index, 1);
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
