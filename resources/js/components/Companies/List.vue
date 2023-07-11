<template>
  <el-row :gutter="12">
    <el-col :span="12">
      <h3>Comapnies</h3>
      <p class="text-muted">{{ getCompanyList.length }} companies</p>
    </el-col>

    <el-col :span="12">
      <div v-if="logged_in_user && logged_in_user.role === 'Super-Admin'">
        <create></create>
      </div>
      <h1>
        <!-- <router-link :to="{ name: 'companyCreate'}" style="text-decoration: none; color: inherit;">
                    <el-button type="primary" style="float: right;">
                        Create company
                    </el-button>
                </router-link> -->
      </h1>
    </el-col>
  </el-row>

  <el-row :gutter="12">
    <el-col
      :span="8"
      v-for="company in getCompanyList"
      class="pb-2"
      :key="company.id"
    >
      <router-link
        :to="'/' + company.slug + '/dashboard'"
        style="text-decoration: none"
      >
        <el-card shadow="always">
          <div class="card-subtitle">
            <p class="card-subtitle-font">{{ company.name }}</p>
          </div>

          <div class="card-box-section">
            <div>
              <p>
                <span style="font-weight: bold; font-size: large;"> Users: </span>
                <span v-if="company.company_users.length !== 0">
                  <span v-for="(user, index) in company.company_users" :key="user.id" class="text-warning">
                    {{ index !== 0 ? ',' : '' }} {{ displayUser(user.user) }} 
                  </span>
                </span>
                <span v-else class="text-danger">
                  Not Assigned Yet
                </span>
              </p>
            </div>
            <el-divider content-position="center">
              Overview
            </el-divider>
            <div class="card-bottom-content">
              <div>
                <h5 style="font-weight: 700; margin-bottom: 0px">Bills</h5>
                <span style="font-size: 13px">{{ company.bills_count }}</span>
              </div>
              <div>
                <h5 style="font-weight: 700; margin-bottom: 0px">Boms</h5>
                <span style="font-size: 13px">{{ company.boms_count }}</span>
              </div>
              <div>
                <h5 style="font-weight: 700; margin-bottom: 0px">Sales</h5>
                <span style="font-size: 13px">{{ company.sales_count }}</span>
              </div>
            </div>
            <div class="card-bottom-content">
              <div>
                <h5 style="font-weight: 700; margin-bottom: 0px">Bom Sales</h5>
                <span style="font-size: 13px">{{
                  company.bom_sales_count
                }}</span>
              </div>
              <div>
                <h5 style="font-weight: 700; margin-bottom: 0px">Users</h5>
                <span style="font-size: 13px">{{
                  company.company_users_count
                }}</span>
              </div>
            </div>
          </div>
        </el-card>
      </router-link>
    </el-col>
  </el-row>
</template>



<script >
// import { useCompanyStore } from "@/stores/CompanyStore"
import create from "./Create.vue";
import { mapState } from "pinia";
import { useCompanyStore } from "@/stores/CompanyStore";

//const companyData = useCompanyStore();

export default {
  name: "CompanyList",
  data() {
    return {
      logged_in_user: null,
    };
  },
  components: {
    create,
  },

  computed: {
    ...mapState(useCompanyStore, { getCompanyList: "getCompanyList" }),
  },
  async created() {
    try {
      await axios.get(`/logged_in_user`).then((res) => {
        this.logged_in_user = res.data;
        console.log("logged_in_user:", this.logged_in_user);
      });
    } catch (error) {
      console.error(error);
    }
  },
  mounted() {
    const getAllCompanies = useCompanyStore();
    getAllCompanies.fetchCompanies();
  },
  methods: {
    displayUser(user) {
      return user.name;
    }
  },
};
</script>

<style>
.compaines-card-wrapper {
  background-color: #fff;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  padding-bottom: 1px;
  border-radius: 10px;
}
.card-subtitle {
  align-items: center;
  gap: 16px;
  margin-top: 24px;
  padding: 0 10px;
}
.card-subtitle-font {
  font-size: 20px;
  font-weight: bold;
}
.card-box-section {
  background-color: #393838;
  color: white;
  padding: 18px;
  border-radius: 10px;
  position: relative;
}
.card-bottom-content {
  display: flex;
  align-items: center;
  gap: 24px;
  justify-content: space-between;
  margin-top: 5px;
}
</style>

