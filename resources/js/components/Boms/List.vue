
<template>
  <div style="padding: 10px">
    <h1>
      BOM List
      <router-link
        :to="'/' + $route.params.slug + '/boms/create'"
        style="text-decoration: none; color: inherit"
      >
        <el-button
          type="primary"
          v-if="logged_in_user && logged_in_user.role === 'Super-Admin'"
          style="float: right"
        >
          Create
        </el-button>
      </router-link>
    </h1>

    <div class="filter-container">
      <el-input
        v-model="query.keyword"
        placeholder="Keyword"
        style="width: 200px"
      />
      <el-button type="primary" @click="handleFilter">
        <el-icon style="vertical-align: middle">
          <Search />
        </el-icon>
        <span style="vertical-align: middle"> Search </span>
      </el-button>
      <el-button type="primary" @click="exportData('xls')">
        <el-icon style="vertical-align: middle">
          <Download />
        </el-icon>
        <span style="vertical-align: middle"> Export to Excel </span>
      </el-button>

      <el-button type="primary" @click="exportData('csv')">
        <el-icon style="vertical-align: middle">
          <Download />
        </el-icon>
        <span style="vertical-align: middle"> Export to CSV </span>
      </el-button>

      <el-button type="danger" @click="handleBulkDelete">
        <el-icon style="vertical-align: middle">
          <Delete />
        </el-icon>
        <span style="vertical-align: middle">Delete Selecteds</span>
      </el-button>
      <el-upload
        v-model:file-list="fileList"
        class="upload-demo"
        action="/api/bom/import"
        multiple
        :on-preview="handlePreview"
        :on-remove="handleRemove"
        :on-success="handleSuccess"
        :before-remove="beforeRemove"
        :limit="3"
        :on-exceed="handleExceed"
      >
        <el-button type="primary">Import to CSV</el-button>
      </el-upload>
    </div>

    <el-table :data="boms" @selection-change="handleSelectionChange">
      <el-table-column type="selection" width="55" />
      <el-table-column prop="name" label="Name" />
      <el-table-column prop="invoice_total" label="Invoice Total">
        <template #default="scope">
          {{ formatValue(scope.row.sub_total) }}
        </template>
      </el-table-column>
      <el-table-column prop="sub_total" label="Total Estimated Profit">
        <template #default="scope">
          {{ formatValue(scope.row.invoice_total) }}
        </template>
      </el-table-column>
      <!-- <el-table-column prop="invoice_total" label="Invoice Total" /> -->
      <el-table-column prop="id" label="Operations">
        <template #default="scope">
          <router-link
            :to="'/' + $route.params.slug + '/boms/edit/' + scope.row.id"
          >
            <el-icon
              :size="20"
              :color="color"
              style="width: 1em; height: 1em; margin-right: 8px"
              v-if="logged_in_user && logged_in_user.role === 'Super-Admin'"
            >
              <Edit />
            </el-icon>
          </router-link>
          <router-link
            :to="'/' + $route.params.slug + '/boms/view/' + scope.row.id"
          >
            <el-icon
              :size="20"
              :color="color"
              style="width: 1em; height: 1em; margin-right: 8px"
            >
              <View />
            </el-icon>
          </router-link>
          <el-icon
            :size="20"
            :color="'red'"
            style="width: 1em; height: 1em; margin-right: 8px"
            @click="handleDelete(scope.row.id)"
            v-if="logged_in_user && logged_in_user.role === 'Super-Admin'"
          >
            <Delete />
          </el-icon>
        </template>
      </el-table-column>
    </el-table>

    <div class="demo-pagination-block">
      <el-pagination
        v-show="total > 0"
        :page-size="query.limit"
        layout="total, sizes, prev, pager, next"
        :total="total"
        :page-count="totalPages"
        :page-sizes="[1, 2, 5, 10, 20, 50]"
        @size-change="handleSizeChange"
        @current-change="handlePageChange"
      />
    </div>
  </div>
</template>



<script >
import * as XLSX from "xlsx";
import { saveAs } from "file-saver";
import { ElMessage, ElMessageBox } from "element-plus";
import { excelParser } from "../../utils/excel-parser";
import helper from "../../helper";

export default {
  name: "BomList",
  data() {
    return {
      boms: [],
      logged_in_user: null,
      loading: false,
      query: {
        page: 1,
        limit: 5,
        keyword: "",
      },
      total: 10,
      totalPages: null,
      pageSize: 5,
      multipleSelection: [],
      bulkDeleteIds: [],
    };
  },
  async mounted() {
    try {
      await this.getList();
      await axios.get(`/logged_in_user`).then((res) => {
        this.logged_in_user = res.data;
      });
    } catch (error) {
      console.error(error);
    }
  },
  methods: {
    handleDelete(id) {
      console.log(id);
      ElMessageBox.confirm(
        "Are you sure you want to delete the BOM?",
        "Warning",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Cancel",
          type: "warning",
        }
      )
        .then(() => {
          axios.delete(`/api/boms/` + id).then((res) => {
            this.boms = res.data;
            this.boms.forEach((element) => {
              element.invoice_total = element.invoice_total.toLocaleString(
                "en-US",
                { minimumFractionDigits: 2, maximumFractionDigits: 2 }
              );
              return element;
            });
            ElMessage({
              type: "success",
              message: "Delete completed",
            });
          });
        })
        .catch(() => {
          ElMessage({
            type: "info",
            message: "Delete canceled",
          });
        });
    },
    handleBulkDelete() {
      // var multipleSelectionRaw = { ...this.multipleSelection }
      // const multipleSelectionRaw = Object.values(this.multipleSelection);
      const multipleSelectionArray = Array.from(
        this.multipleSelection,
        (obj) => ({ ...obj })
      );
      console.log("multipleSelectionArray:", multipleSelectionArray);
      ElMessageBox.confirm(
        "Are you sure you want to bulkdelete the selected Bills?",
        "Warning",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Cancel",
          type: "warning",
        }
      )
        .then(() => {
          this.multipleSelection.forEach((element) => {
            this.bulkDeleteIds.push(element.id);
            console.log(element.id, this.bulkDeleteIds);
          });
          axios.post(`/api/boms/bulkdelete`, this.bulkDeleteIds).then((res) => {
            console.log("res:", res);
            this.bulkDeleteIds = [];
            this.getList();
            ElMessage({
              type: "success",
              message: "Delete completed",
            });
          });
        })
        .catch(() => {
          ElMessage({
            type: "info",
            message: "Delete canceled",
          });
          this.bulkDeleteIds = [];
        });
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    async getList() {
      this.loading = true;
      let params = {
        limit: this.pageSize,
        keyword: this.query.keyword,
        page: this.query.page,
        slug: this.$route.params.slug,
      };
      // console.log('params', params);
      await axios.get(`/api/boms`, { params }).then((res) => {
        // console.log('res:', res);
        this.boms = res.data.data;
        this.query.page = res.data.current_page;
        this.total = res.data.total;
        this.totalPages = Math.ceil(res.data.total / this.pageSize); // Calculate the total number of pages
      });
      this.loading = false;
    },
    handleSizeChange(val) {
      this.pageSize = val;
      this.getList();
    },
    handlePageChange(currentPage) {
      this.query.page = currentPage;
      this.getList();
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },

    async exportData(format) {
      try {
        await axios.get(`/api/boms/exported-data`).then(({ data }) => {
          excelParser().exportDataFromJSON(data.data, "bom-list", format);
        });
      } catch (error) {
        console.error(error);
      }
    },

    // Export Excel file
    exportToExcel() {
      console.log("filteredRegisters", this.boms);
      const data = this.boms.map((bom) => {
        const {
          id,
          invoice_number,
          client_id,
          created_at,
          updated_at,
          ...filteredData
        } = bom;
        return filteredData;
      });

      const worksheet = XLSX.utils.json_to_sheet(data, {
        header: Object.keys(data[0]),
        cellStyles: true,
      });

      // Modify header names and styles
      const headerRange = XLSX.utils.decode_range(worksheet["!ref"]);
      for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({
          r: headerRange.s.r,
          c: col,
        });
        let headerCellValue = worksheet[headerCell].v;
        if (headerCellValue.includes("month")) {
          headerCellValue =
            this.original_months[headerCellValue.split("-")[1] - 1] +
            "-" +
            headerCellValue.split("-")[2];
        }
        console.log("headerCellValue", headerCellValue);
        headerCellValue = this.capitalizeFirstLetter(headerCellValue);
        const modifiedHeader = headerCellValue;
        worksheet[headerCell].v = modifiedHeader;
        worksheet[headerCell].s = {
          font: { bold: true },
        };
      }

      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
      const excelBuffer = XLSX.write(workbook, {
        bookType: "xlsx",
        type: "array",
      });
      const dataBlob = new Blob([excelBuffer], {
        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      });
      saveAs(dataBlob, "exported_data.xlsx");
    },
    capitalizeFirstLetter(str) {
      const words = str.split("_");
      const capitalizedWords = words.map(
        (word) => word.charAt(0).toUpperCase() + word.slice(1)
      );
      return capitalizedWords.join(" ");
    },
    handleSuccess(res) {
      ElMessage.success("File has already imported!");
    },
    formatValue(value) {
      return helper.formatNumberToFraction(value);
    },
  },
};
</script>

<style>
a href {
  text-decoration: none;
  color: inherit;
}
.filter-container {
  padding-bottom: 10px;
}
.upload-demo {
  display: inline;
  margin-left: 12px;
}
</style>
