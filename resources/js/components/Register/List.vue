
<template>
    <div style="padding:20px;">
        <h1>Register List</h1>

        <div class="filter-container">
            <el-input
                v-model="query.keyword"
                placeholder="Keyword"
                style="width: 200px;"
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
                <span style="vertical-align: middle"> Export to csv </span>
            </el-button>

            <el-button type="primary" @click="exportData('pdf')">
                <el-icon style="vertical-align: middle">
                    <Download />
                </el-icon>
                <span style="vertical-align: middle"> Download Pdf </span>
            </el-button>


            <div style="float: right;">
                <span style="vertical-align: middle;  font-size:16px;">Year</span>
                <el-date-picker
                    v-model="year"
                    type="year"
                    placeholder="Pick a year"
                    @change="handleYearFilter"
                />
            </div>


        </div>


        <el-table :data="registers" class="small-font-table">
            <el-table-column fixed prop="name" label="Name" />
            <el-table-column fixed prop="sku" label="SKU" />
            <template v-for="month in months">
                <el-table-column :prop="`month-${month}`" :label="original_months[month.split('-')[1] - 1]+'-'+month.split('-')[0]">
                    <template #default="scope">
                            <!-- {{ month.split('-')[1] }}  -->
                            <!-- {{original_months[month.split('-')[1] - 1]}} -->
                        {{ formattedAverage(scope.row[`month-${month}`]) }}
                    </template>
                </el-table-column>
            </template>


            <el-table-column fixed='right' prop="bill_item_id" label="Operations" >
                <template  #default="scope">
                    <router-link :to="'/registers/view/'+scope.row.bill_item_id">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <View />
                        </el-icon>
                    </router-link>
                </template>
            </el-table-column>
        </el-table>


        <div class="demo-pagination-block">
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



<script >
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import {excelParser} from "../../utils/excel-parser.js";

export default {
    name: 'Register',
    data() {
        return {
            registers: [],
            months: [],
            original_months: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ],
            query: {
                page: 1,
                limit: 10,
                keyword: '',
                year: new Date().getFullYear(),
            },
            total: null,
            totalPages: null,
            pageSize: 10,
            year: null,
            selectedYear: null,
        };
    },
    async mounted() {
        try {
            await this.getList();
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        async getList() {
            let params = {
                limit: this.pageSize,
                keyword: this.query.keyword,
                page: this.query.page,
                year: this.query.year,
            }
            console.log('params', params, this.query.year);
            await axios.get(`/api/registers`, {params}).
                    then((res) => {
                console.log(res.data)
                        //console.log('response in register list:', res);
                        this.registers = res.data.register_list;
                        this.months = res.data.distinct_months;
                        // this.query.page = res.data.current_page;
                        this.total = res.data.total;
                        this.totalPages = Math.ceil(res.data.total / this.pageSize);
                    });
        },
        handleYearFilter(){
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
                return value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
  font-size: 10px; /* Adjust the font size as needed */
}
</style>
