<template>
  <div class="el-table-wrapper">
    <h3 class="text-center mb-3 font-weight-bold">Sugar</h3>




      <div class="table-body" style="max-height: 75vh;">
        <el-table :data="tableData" style="width: 100%" >
          <el-table-column prop="date" label="Date" />
          <el-table-column    v-for="column in dynamicColumns"
                              :key="column.prop"
                              :label="column.label">
            <el-table-column prop="rate" label="Rate" width="100%"/>
            <el-table-column prop="unit" label="Unit" width="100%"/>
            <el-table-column prop="qty" label="Qty" width="100%"/>
            <el-table-column prop="value" label="Value" width="100%"/>
          </el-table-column>
          <el-table-column  label="Invoice Number">
            <el-table-column prop="value" label="200" />
          </el-table-column>
          <el-table-column  label="Remarks">
            <el-table-column prop="value" label="200" />
          </el-table-column>
        </el-table>
      </div>
      <div class="table-footer">
        <!-- Table footer content -->
      </div>
    </div>



</template>

<script lang="ts" setup>



const tableData = [
  {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  }, {
    date: '2016-05-03',
    rate: 'Tom',
    unit: 'California',
    qty: 'Los Angeles',
    value: 'test',

  },
]
const dynamicColumns= [
  { prop: 'name', label: 'Opening  Inventory' },
  { prop: 'age', label: 'Purchase ' },
  { prop: 'profession', label: 'Closing Inventory ' },

]

</script>
<style>
.el-table-wrapper .cell{
  text-align: center;
  overflow: auto;
}
.el-table-wrapper .el-table__cell{
 padding: 0;
}

.table-body {
  overflow-y: auto;
}
.el-table-wrapper .el-table__header th{
  position: sticky !important;
  top: 0 !important;
  z-index: 1;
  /*background-color: green !important;*/
}

</style>