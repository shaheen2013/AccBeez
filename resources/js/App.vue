<template>
    <div class="common-layout">
        <el-container class="layout-container-demo">
            <el-aside width="200px">
                <el-scrollbar>

                    <el-menu>
                        <el-menu-item index="1">
                            <!-- <el-icon><icon-menu /></el-icon> -->
                            <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                <List />
                            </el-icon>
                            <router-link to="/bills" style="text-decoration: none;"
                                        :style="[$route.path === '/bills' ? {color: 'royalblue'} : {color: 'black'}]">
                                    Bills
                            </router-link>
                        </el-menu-item>
                        <el-menu-item index="2">
                            <!-- <el-icon><icon-menu /></el-icon> -->
                            <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                <List />
                            </el-icon>
                            <router-link to="/register" style="text-decoration: none;"
                                        :style="[$route.path === '/register' ? {color: 'royalblue'} : {color: 'black'}]">
                                    Register
                            </router-link>
                        </el-menu-item>
                        <el-menu-item index="3">
                            <!-- <el-icon><icon-menu /></el-icon> -->
                            <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                <List />
                            </el-icon>
                            <router-link to="/boms" style="text-decoration: none;"
                                        :style="[$route.path === '/boms' ? {color: 'royalblue'} : {color: 'black'}]">
                                    BOM
                            </router-link>
                        </el-menu-item>
                        <el-menu-item index="4">
                            <!-- <el-icon><icon-menu /></el-icon> -->
                            <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                <List />
                            </el-icon>
                            <router-link to="/users" style="text-decoration: none;"
                                        :style="[$route.path === '/users' ? {color: 'royalblue'} : {color: 'black'}]">
                                    Users
                            </router-link>
                        </el-menu-item>

                        <el-menu-item index="4">
                            <!-- <el-icon><icon-menu /></el-icon> -->
                            <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                <List />
                            </el-icon>
                            <router-link to="/sales" style="text-decoration: none;"
                                        :style="[$route.path === '/sales' ? {color: 'royalblue'} : {color: 'black'}]">
                                    Sales
                            </router-link>
                        </el-menu-item>
                    </el-menu>


                    <!-- <el-menu :default-openeds="['1', '3']">
                        <el-sub-menu index="1">
                            <template #title>
                                <el-icon>
                                    <icon-menu />
                                </el-icon>Boms
                            </template>
                            <el-menu-item-group>
                                <el-menu-item index="1-1">
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
                                    <router-link to="/bills">Bills</router-link>
                                </el-menu-item>
                                <el-menu-item index="2-3">
                                    <router-link to="/register">Register</router-link>
                                </el-menu-item>
                            </el-menu-item-group>
                        </el-sub-menu>
                    </el-menu> -->

                </el-scrollbar>
            </el-aside>

            <el-main>
                <router-view></router-view>
            </el-main>

        </el-container>
    </div>
</template>

<script lang="ts" setup>
    import { ref } from 'vue'
    import { Menu as IconMenu, Message, Setting } from '@element-plus/icons-vue'

    // export default {
    //     props:['logged_in_user'],
    //     data() {
    //         return {
    //             bills: [],
    //         };
    //     },
    //     mounted() {
    //         console.log(this.logged_in_user)
    //     },
    //     methods: {

    //     },
    // };
    // const props = defineProps({
    //     loggedInUser: {
    //         type: String,
    //         required: true
    //     }
    // });

    // // You can still use the created() lifecycle hook like this
    // const created = () => {
    //     console.log('user:', props.loggedInUser);
    // };

</script>

<style >
    .layout-container-demo .el-header {
        position: relative;
        background-color: var(--el-color-primary-light-7);
        color: var(--el-text-color-primary);
    }

    .layout-container-demo .el-aside {
        color: var(--el-text-color-primary);
        /* background: var(--el-color-primary-light-8); */
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
