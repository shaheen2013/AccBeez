
<template>
    <div>
        <h1>Bill List</h1>

            <el-scrollbar>
                <el-table :data="tableData">
                    <el-table-column prop="date" label="Date" width="140" />
                    <el-table-column prop="name" label="Name" width="120" />
                    <el-table-column prop="address" label="Address" />
                </el-table>
            </el-scrollbar>
    </div>
</template>

<script lang="ts" setup>
    import {
        ref
    } from 'vue'
    import {
        Menu as IconMenu,
        Message,
        Setting
    } from '@element-plus/icons-vue'

    const item = {
        date: '2016-05-02',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
    }
    const tableData = ref(Array.from({
        length: 20
    }).fill(item))
</script>

<style>

</style>
