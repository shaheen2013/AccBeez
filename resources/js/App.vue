<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { Menu as IconMenu, Message, Setting } from '@element-plus/icons-vue'



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
