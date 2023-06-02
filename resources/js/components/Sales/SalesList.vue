<template>
    <div style="padding: 10px;">
        <h1>
            Sales List
            <el-button type="primary" v-if="logged_in_user && logged_in_user.role === 'admin'" style="float: right;">
                <router-link to="/users/create" style="text-decoration: none; color: inherit;">Create</router-link>
            </el-button>
        </h1>

        <el-table :data="sales">
            <el-table-column prop="bom.name" label="Bom"/>
            <el-table-column prop="amount" label="Amount"/>
            <el-table-column prop="date" label="Date"/>
<!--            <el-table-column prop="id" label="Operations">-->
<!--                <template #default="scope">-->
<!--                    <router-link :to="'/users/edit/'+scope.row.id"-->
<!--                                 v-if="logged_in_user && logged_in_user.role === 'admin'">-->
<!--                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">-->
<!--                            <Edit/>-->
<!--                        </el-icon>-->
<!--                    </router-link>-->
<!--                    <router-link :to="'/users/view/'+scope.row.id">-->
<!--                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">-->
<!--                            <View/>-->
<!--                        </el-icon>-->
<!--                    </router-link>-->
<!--                    <el-icon :size="20" :color="'red'"-->
<!--                             style="width: 1em; height: 1em; margin-right: 8px"-->
<!--                             @click="handleDelete(scope.row.id);"-->
<!--                             v-if="logged_in_user && logged_in_user.role === 'admin'"-->
<!--                    >-->
<!--                        <Delete/>-->
<!--                    </el-icon>-->
<!--                </template>-->
<!--            </el-table-column>-->
        </el-table>

    </div>
</template>

<script>
export default {
    name: 'SalesList',
    data() {
        return {
            sales: []
        }
    },

    async mounted() {
        try {
            await axios.get(`/api/sales`).then(({data}) => {
                this.sales = data
            });
        } catch (error) {
            console.error(error);
        }
    }
}
</script>
<style scoped>

</style>
