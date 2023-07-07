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
          <div class="compaines-card-wrapper">
            <div class="card-subtitle">
              <p class="card-subtitle-font">{{ company.name }}</p>
            </div>

            <div class="card-box-section">
              <div class="card-bottom-content">
                <div>
                  <h5 style="font-weight: 700; margin-bottom: 0px">
                    cash available
                  </h5>
                  <p style="font-size: 13px">738248</p>
                </div>
                <div>
                  <h5 style="font-weight: 700; margin-bottom: 0px">
                    cash available
                  </h5>
                  <p style="font-size: 13px">738248</p>
                </div>
                <div>
                  <h5 style="font-weight: 700; margin-bottom: 0px">
                    cash available
                  </h5>
                  <p style="font-size: 13px">738248</p>
                </div>
              </div>
              <div class="card-bottom-content">
                <div>
                  <h5 style="font-weight: 700; margin-bottom: 0px">
                    cash available
                  </h5>
                  <p style="font-size: 13px">738248</p>
                </div>
                <div>
                  <h5 style="font-weight: 700; margin-bottom: 0px">
                    cash available
                  </h5>
                  <p style="font-size: 13px">738248</p>
                </div>
                <div>
                  <h5 style="font-weight: 700; margin-bottom: 0px">
                    cash available
                  </h5>
                  <p style="font-size: 13px">738248</p>
                </div>
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
  methods: {},
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
  margin: 10px;
  border-radius: 10px;
  position: relative;
}
.card-bottom-content {
  display: flex;
  align-items: center;
  gap: 24px;
  justify-content: space-between;
  margin-bottom: 16px;
}
</style>

