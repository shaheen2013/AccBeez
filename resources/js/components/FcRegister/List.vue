<template>
  <div style="padding:20px;">
    <h1>FG Register List </h1>

    <div class="filter-container">
      <el-input
          v-model="query.keyword"
          placeholder="Keyword"
          style="width: 200px;"
          class="mb-xl-0 mb-2"
      />
      <el-button class="mb-xl-0 mb-2" type="primary" @click="handleFilter">
        <el-icon style="vertical-align: middle">
          <Search/>
        </el-icon>
        <span style="vertical-align: middle"> Search </span>
      </el-button>

      <el-button class="mb-xl-0 mb-2" type="primary" @click="exportData('xls')">
        <el-icon style="vertical-align: middle">
          <Download/>
        </el-icon>
        <span style="vertical-align: middle"> Export to Excel </span>
      </el-button>

      <el-button class="mb-xl-0 mb-2" type="primary" @click="exportData('csv')">
        <el-icon style="vertical-align: middle">
          <Download/>
        </el-icon>
        <span style="vertical-align: middle"> Export to csv </span>
      </el-button>

      <el-button class="mb-xl-0 mb-2" type="primary" @click="exportData('pdf')">
        <el-icon style="vertical-align: middle">
          <Download/>
        </el-icon>
        <span style="vertical-align: middle"> Download Pdf </span>
      </el-button>


      <div style="float: right; margin-top: 16px; margin-bottom: 16px;">
        <span style="vertical-align: middle;  font-size:16px; margin-right: 8px;">Year</span>
        <el-date-picker
            v-model="year"
            type="year"
            placeholder="Pick a year"
            @change="handleYearFilter"
        />
      </div>


    </div>


    <el-table :data="registers" class="small-font-table" v-loading="loading">
      <el-table-column fixed prop="name" label="Name"/>
      <template v-for="(month, index) in months" :key="index">
        <el-table-column :label="original_months[month.split('-')[1] - 1]+'-'+month.split('-')[0]">


          <template #default="scope" >
            <div class="d-flex  justify-content-center  ">
              <div class=" pe-2  ps-2 border-end fw-bold fs-6 text-success text-nowrap">
                {{ getqty(scope.row[`month-${month}`]) }} {{ scope.row[`month-${month}`].unit }}
              </div>
              <div class="ps-2 pe-2 fw-bold fs-6 text-nowrap">

                {{ getvalue(scope.row[`month-${month}`]) }}
              </div>
            </div>


          </template>

        </el-table-column>
      </template>


      <el-table-column fixed='right' prop="bill_item_id" label="Operations">
        <template #default="scope">
          <router-link :to="'/' + $route.params.slug + '/fc-registers/view/'+scope.row.bill_item_id">
            <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
              <View/>
            </el-icon>
          </router-link>
        </template>
      </el-table-column>
    </el-table>


    <div class="demo-pagination-block mt-3">
      <el-pagination
          v-show="total>0"
          :page-size="query.limit"
          layout="total, sizes, prev, pager, next"
          :total="total"
          :page-count="totalPages"
          :page-sizes="[10, 20, 50, 100, 500]"
          @size-change="handleSizeChange"
          @current-change="handlePageChange"
      />
    </div>

  </div>
</template>


<script>

export default {
  name: 'Register',
  data() {
    return {
      registers: [],
      loading:true,
      months: [],

      original_months: [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
      ],
      query          : {
        page   : 1,
        limit  : 10,
        keyword: '',
        year   : new Date().getFullYear(),
      },
      total          : null,
      totalPages     : null,
      pageSize       : 10,
      year           : null,
      selectedYear   : null,

    };


  },
  async mounted() {
    try {
      await this.getList();
      if (this.registers.length > 0){
        this.loading=false
      }
    } catch (error) {
      console.error(error);
    }
  },
  methods: {
    async getList() {
      let params = {
        limit  : this.pageSize,
        keyword: this.query.keyword,
        page   : this.query.page,
        year   : this.query.year,
        slug   : this.$route.params.slug
      }
      console.log('params', params, this.query.year);
      await axios.get(`/api/fc-registers`, {params}).then((res) => {
        console.log(res.data)
        //console.log('response in register list:', res);
        this.registers  = res.data.register_list;
        this.months     = res.data.distinct_months;
        // this.query.page = res.data.current_page;
        this.total      = res.data.total;
        this.totalPages = Math.ceil(res.data.total / this.pageSize);
      });
    },
    handleYearFilter() {
      this.query.year = new Date(this.year).getFullYear();
      this.getList();
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleSizeChange(val) {
      this.pageSize = val;
      this.getList();
    },
    handlePageChange(currentPage) {
      this.query.page = currentPage;
      this.getList();
    },

    formattedAverage(value) {
      if (value !== null) {
        return [
          value.total_cost.toLocaleString('en-US', {minimumFractionDigits: 4, maximumFractionDigits: 4}),
          value.total_items.toLocaleString('en-US', {minimumFractionDigits: 4, maximumFractionDigits: 4}),
        ]

        // return value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }
    },

    getqty(value) {
      let qty = this.formattedAverage(value);
      if (qty) {
        console.log(qty[1])
        return qty[1] ?? 1;
      }
    },

    getvalue(value) {
      let qty = this.formattedAverage(value);
      if (qty) {
        return qty[0];
      }
    },


    // Export Excel file
    exportData(format) {
      window.location.href = `/api/register/exported-data/${format}`;
      // try {
      //     await axios.get(`/api/register/exported-data`).
      //     then(({data}) => {
      //
      //         const bills = data.data.map((bill)=>{
      //             return {
      //                 Date: bill.date,
      //                 Description: bill.description,
      //                 Invoice_Total: bill.invoice_total,
      //             }
      //         })
      //
      //         excelParser().exportDataFromJSON(bills, 'Bill list', format);
      //     });
      // } catch (error) {
      //     console.error(error);
      // }


      // console.log('filteredRegisters', this.registers);
      // const data = this.registers.map(register => {
      //     const { bill_item_id, ...filteredData } = register;
      //     return filteredData;
      // });
      //
      // const worksheet = XLSX.utils.json_to_sheet(data, {
      //     header: Object.keys(data[0]),
      //     cellStyles: true
      // });
      //
      // // Modify header names and styles
      // const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
      // for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
      //     const headerCell = XLSX.utils.encode_cell({ r: headerRange.s.r, c: col });
      //     let headerCellValue = worksheet[headerCell].v;
      //     if(headerCellValue.includes('month')){
      //         headerCellValue = this.original_months[headerCellValue.split('-')[2] - 1]+'-'+headerCellValue.split('-')[1]
      //     }
      //     console.log('headerCellValue', headerCellValue)
      //     headerCellValue = this.capitalizeFirstLetter(headerCellValue);
      //     const modifiedHeader = headerCellValue;
      //     worksheet[headerCell].v = modifiedHeader;
      //     worksheet[headerCell].s = {
      //         font: { bold: true }
      //     };
      // }
      //
      // const workbook = XLSX.utils.book_new();
      // XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
      // const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
      // const dataBlob = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
      // saveAs(dataBlob, 'exported_data.xlsx');
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

  },
};
</script>

<style>
.small-font-table .el-table__header,
.small-font-table .el-table__body {
  font-size : 10px; /* Adjust the font size as needed */
}

.cell {
  text-align : center !important;
}

.table-padding{
  padding: 10px 0;
}
</style>
