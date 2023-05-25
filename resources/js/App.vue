<template>
    <div class="common-layout">
        <el-container class="layout-container-demo" style="height: 500px">
            <el-aside width="200px">
                <el-scrollbar>
                    <el-menu :default-openeds="['1', '3']">
                        <el-sub-menu index="1">
                            <template #title>
                                <el-icon>
                                    <icon-menu />
                                </el-icon>Boms
                            </template>
                            <el-menu-item-group>
                                <el-menu-item index="1-1">
                                    <!-- Bills -->
                                    <router-link to="/boms">List</router-link>
                                </el-menu-item>
                            </el-menu-item-group>
                        </el-sub-menu>
                        <el-sub-menu index="2">
                            <template #title>
                                <el-icon>
                                    <icon-menu />
                                </el-icon>Bills
                            </template>
                            <el-menu-item-group>
                                <el-menu-item index="2-1">
                                    <!-- Bills -->
                                    <router-link to="/bills">Bills</router-link>
                                </el-menu-item>
                                <!-- <el-menu-item index="2-2">
                                    <router-link to="/bills/create">Bill Create</router-link>
                                </el-menu-item> -->
                                <el-menu-item index="2-3">
                                    <router-link to="/register">Register</router-link>
                                </el-menu-item>
                            </el-menu-item-group>
                        </el-sub-menu>
                    </el-menu>
                </el-scrollbar>
            </el-aside>

            <el-main>
                <router-view></router-view>
            </el-main>

        </el-container>
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

</script>

<style >
    .layout-container-demo .el-header {
        position: relative;
        background-color: var(--el-color-primary-light-7);
        color: var(--el-text-color-primary);
    }

    .layout-container-demo .el-aside {
        color: var(--el-text-color-primary);
        background: var(--el-color-primary-light-8);
    }

    .layout-container-demo .el-menu {
        border-right: none;
    }

    .layout-container-demo .el-main {
        padding: 0;
    }

    .layout-container-demo .toolbar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        right: 20px;
    }

    .el-form-item__label {
        font-weight:bold !important;
        color: #212529;
    }
    .el-form-item__label {
        font-weight:bold !important;
        color: #212529;
    }
    .el-input__wrapper {
        width: 100%;
    }
</style>
