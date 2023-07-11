<template>
  <!-- <el-button text @click="dialogFormVisible = true">
        Create company
    </el-button> -->

  <h1>
    <el-button
      type="primary"
      style="float: right"
      @click="dialogFormVisible = true"
    >
      Create company
    </el-button>
  </h1>

  <el-dialog v-model="dialogFormVisible" title="Create company" width="30%">
    <el-form :model="form">
      <el-form-item label="Company name">
        <el-input v-model="company.name" placeholder="Company name" />
      </el-form-item>
    </el-form>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button type="primary" @click="submitData">Create</el-button>
      </span>
    </template>
  </el-dialog>
</template>
  
<script setup>
import { reactive, ref } from "vue";

import { useCompanyStore } from "@/stores/CompanyStore";
import { storeToRefs } from "pinia";

const { company } = storeToRefs(useCompanyStore());

const dialogTableVisible = ref(false);
const dialogFormVisible = ref(false);
const formLabelWidth = "140px";
</script>

<script>
export default {
  methods: {
    submitData() {
      const createCompany = useCompanyStore();
      createCompany.createCompany();
      window.location.reload();
    },
  },
};
</script>

<style scoped>
.el-button--text {
  margin-right: 15px;
}

.dialog-footer button:first-child {
  margin-right: 10px;
}
</style>
  