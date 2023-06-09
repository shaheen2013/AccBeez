
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
        </div>


        <el-table :data="registers">
            <el-table-column fixed prop="name" label="SKU" />
            <el-table-column fixed prop="sku" label="Name" />
            <template v-for="month in months">
                <!-- <el-table-column :prop="`month-${month}`" :label="original_months[month-1]" :key='`month-${month}`' /> -->
                <el-table-column :prop="`month-${month}`" :label="original_months[month-1]" />
            </template>

            <!-- <el-table-column label="Row Key">
                <template v-slot="{ row }">
                    <span>{{ row.key }}</span>
                </template>
            </el-table-column> -->

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

    </div>
</template>



<script >

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
                limit: 5,
                keyword: '',
            },
            total: 10,
            totalPages: null,
            pageSize: 5,
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
            }
            console.log('params', params);
            await axios.get(`/api/registers`, {params}).
                    then((res) => {
                        console.log('response in register list:', res.data);
                        this.registers = res.data.register_list;
                        this.months = res.data.distinct_months;
                        // this.query.page = res.data.current_page;
                        // this.total = res.data.total;
                        // this.totalPages = Math.ceil(res.data.total / this.pageSize);
                    });
        },
        handleFilter() {
            this.query.page = 1;
            this.getList();
        },

    },
};
</script>

<style>

</style>
