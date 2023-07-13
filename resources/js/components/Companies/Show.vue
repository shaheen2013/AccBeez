<template>
  <div >
    <h1>
     Company List
    </h1>
    <div class="border p-3 rounded mt-3">
    <div class="d-flex align-items-center justify-content-between ">
     <h3 class="fs-5">Company Name</h3>
      <h3 class="fs-5">Action</h3>

    </div>
      <pre>
        {{companyList}}

      </pre>
      =======================================
      <pre>
        {{deletedCompanyList}}
      </pre>

      <ul class="mt-3 list-unstyled ">
        <li class="d-flex align-items-center justify-content-between mb-3" v-for="(list,i) in companyList" key={i}>
          <p class="fw-bold fs-6">{{list.name}}</p>
          <button class="btn btn-danger fw-bolder"   @click=handleDelete(list.id) >Delete</button>
        </li>
      </ul>
    </div>

  </div>
</template>

<script>
export default {
  name: 'Companies',
  data() {
    return {
      companyList: [],
      deletedCompanyList:[],
    };

  },
  async mounted() {
    try {
      await this.getCompanyList();
    } catch (error) {
      console.error(error);
    }
  },
  methods: {


    async getCompanyList() {
      await axios.get(`/api/company/list`).then((res) => {
        this.companyList=res.data.companies;
        this.deletedCompanyList=res.data.deletedCompanies;

      });
    },
    // async handleDelete(companyId) {
    //   console.log('company',companyId)
    //   await axios.delete(` /api/company/delete/${companyId}`).then((res) => {
    //   this.getCompanyList();
    //     console.log(res.data)
    //
    //   });
    // },
    async handleDelete(companyId) {
      console.log('company', companyId);
      try {
        const response = await axios.delete(`/api/company/delete/${companyId}`);
        console.log(response.data);
        this.getCompanyList();
      } catch (error) {
        console.error(error);
        // Handle any error that occurred during the DELETE request
      }
    },


  },
};
</script>