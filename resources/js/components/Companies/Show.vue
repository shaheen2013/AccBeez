<template>
  <div >


    <h1>
     Company List
    </h1>
    <div class="border  rounded mt-3 border-bottom-0 ">
    <div class="d-flex align-items-center justify-content-between p-3 border-bottom header-bg">
     <h3 class="fs-5 fw-bold mb-0">Company Name</h3>
      <h3 class="fs-5 fw-bold mb-0">Action</h3>
    </div>
      <ul class=" list-unstyled ">
        <li class="d-flex align-items-center justify-content-between  border-bottom pb-3 pt-3" v-for="(list,i) in companyList" key={i}>
          <p class="fw-bold fs-6 mb-0 ms-3">{{list.name}}</p>
          <div class="dropdown me-3">
            <button class="btn btn-info dropdown-toggle fw-bold" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              Actions
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="#">    <button class="btn btn-warning fw-bolder   me-3 w-100" @click=confirmDelete(list.id) >
                <svg fill="" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="16px" height="16px"><path d="M 21 0 C 19.355469 0 18 1.355469 18 3 L 18 5 L 10.1875 5 C 10.0625 4.976563 9.9375 4.976563 9.8125 5 L 8 5 C 7.96875 5 7.9375 5 7.90625 5 C 7.355469 5.027344 6.925781 5.496094 6.953125 6.046875 C 6.980469 6.597656 7.449219 7.027344 8 7 L 9.09375 7 L 12.6875 47.5 C 12.8125 48.898438 14.003906 50 15.40625 50 L 34.59375 50 C 35.996094 50 37.1875 48.898438 37.3125 47.5 L 40.90625 7 L 42 7 C 42.359375 7.003906 42.695313 6.816406 42.878906 6.503906 C 43.058594 6.191406 43.058594 5.808594 42.878906 5.496094 C 42.695313 5.183594 42.359375 4.996094 42 5 L 32 5 L 32 3 C 32 1.355469 30.644531 0 29 0 Z M 21 2 L 29 2 C 29.5625 2 30 2.4375 30 3 L 30 5 L 20 5 L 20 3 C 20 2.4375 20.4375 2 21 2 Z M 11.09375 7 L 38.90625 7 L 35.3125 47.34375 C 35.28125 47.691406 34.910156 48 34.59375 48 L 15.40625 48 C 15.089844 48 14.71875 47.691406 14.6875 47.34375 Z M 18.90625 9.96875 C 18.863281 9.976563 18.820313 9.988281 18.78125 10 C 18.316406 10.105469 17.988281 10.523438 18 11 L 18 44 C 17.996094 44.359375 18.183594 44.695313 18.496094 44.878906 C 18.808594 45.058594 19.191406 45.058594 19.503906 44.878906 C 19.816406 44.695313 20.003906 44.359375 20 44 L 20 11 C 20.011719 10.710938 19.894531 10.433594 19.6875 10.238281 C 19.476563 10.039063 19.191406 9.941406 18.90625 9.96875 Z M 24.90625 9.96875 C 24.863281 9.976563 24.820313 9.988281 24.78125 10 C 24.316406 10.105469 23.988281 10.523438 24 11 L 24 44 C 23.996094 44.359375 24.183594 44.695313 24.496094 44.878906 C 24.808594 45.058594 25.191406 45.058594 25.503906 44.878906 C 25.816406 44.695313 26.003906 44.359375 26 44 L 26 11 C 26.011719 10.710938 25.894531 10.433594 25.6875 10.238281 C 25.476563 10.039063 25.191406 9.941406 24.90625 9.96875 Z M 30.90625 9.96875 C 30.863281 9.976563 30.820313 9.988281 30.78125 10 C 30.316406 10.105469 29.988281 10.523438 30 11 L 30 44 C 29.996094 44.359375 30.183594 44.695313 30.496094 44.878906 C 30.808594 45.058594 31.191406 45.058594 31.503906 44.878906 C 31.816406 44.695313 32.003906 44.359375 32 44 L 32 11 C 32.011719 10.710938 31.894531 10.433594 31.6875 10.238281 C 31.476563 10.039063 31.191406 9.941406 30.90625 9.96875 Z"/></svg>
                Delete</button></a></li>
              <li><a class="dropdown-item" href="#">    <button class="btn btn-danger fw-bolder   me-3 w-100" @click=confirmDelete(list.id) >
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="16px" height="16px"><path d="M 21 0 C 19.355469 0 18 1.355469 18 3 L 18 5 L 10.1875 5 C 10.0625 4.976563 9.9375 4.976563 9.8125 5 L 8 5 C 7.96875 5 7.9375 5 7.90625 5 C 7.355469 5.027344 6.925781 5.496094 6.953125 6.046875 C 6.980469 6.597656 7.449219 7.027344 8 7 L 9.09375 7 L 12.6875 47.5 C 12.8125 48.898438 14.003906 50 15.40625 50 L 34.59375 50 C 35.996094 50 37.1875 48.898438 37.3125 47.5 L 40.90625 7 L 42 7 C 42.359375 7.003906 42.695313 6.816406 42.878906 6.503906 C 43.058594 6.191406 43.058594 5.808594 42.878906 5.496094 C 42.695313 5.183594 42.359375 4.996094 42 5 L 32 5 L 32 3 C 32 1.355469 30.644531 0 29 0 Z M 21 2 L 29 2 C 29.5625 2 30 2.4375 30 3 L 30 5 L 20 5 L 20 3 C 20 2.4375 20.4375 2 21 2 Z M 11.09375 7 L 38.90625 7 L 35.3125 47.34375 C 35.28125 47.691406 34.910156 48 34.59375 48 L 15.40625 48 C 15.089844 48 14.71875 47.691406 14.6875 47.34375 Z M 18.90625 9.96875 C 18.863281 9.976563 18.820313 9.988281 18.78125 10 C 18.316406 10.105469 17.988281 10.523438 18 11 L 18 44 C 17.996094 44.359375 18.183594 44.695313 18.496094 44.878906 C 18.808594 45.058594 19.191406 45.058594 19.503906 44.878906 C 19.816406 44.695313 20.003906 44.359375 20 44 L 20 11 C 20.011719 10.710938 19.894531 10.433594 19.6875 10.238281 C 19.476563 10.039063 19.191406 9.941406 18.90625 9.96875 Z M 24.90625 9.96875 C 24.863281 9.976563 24.820313 9.988281 24.78125 10 C 24.316406 10.105469 23.988281 10.523438 24 11 L 24 44 C 23.996094 44.359375 24.183594 44.695313 24.496094 44.878906 C 24.808594 45.058594 25.191406 45.058594 25.503906 44.878906 C 25.816406 44.695313 26.003906 44.359375 26 44 L 26 11 C 26.011719 10.710938 25.894531 10.433594 25.6875 10.238281 C 25.476563 10.039063 25.191406 9.941406 24.90625 9.96875 Z M 30.90625 9.96875 C 30.863281 9.976563 30.820313 9.988281 30.78125 10 C 30.316406 10.105469 29.988281 10.523438 30 11 L 30 44 C 29.996094 44.359375 30.183594 44.695313 30.496094 44.878906 C 30.808594 45.058594 31.191406 45.058594 31.503906 44.878906 C 31.816406 44.695313 32.003906 44.359375 32 44 L 32 11 C 32.011719 10.710938 31.894531 10.433594 31.6875 10.238281 C 31.476563 10.039063 31.191406 9.941406 30.90625 9.96875 Z"/></svg>
                Permanently delete</button></a></li>

            </ul>
          </div>
        </li>

        <li class="d-flex align-items-center justify-content-between pb-3 pt-3 border-bottom" v-for="(list,i) in deletedCompanyList" key={i}>
          <p class="fw-bold fs-6 mb-0 ms-3">{{list.name}}</p>
          <button class="btn btn-success  me-3" @click=confirmRestore(list.id)>
            <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" width="24px" height="24px" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="16px" y="16px" viewBox="0 0 100 125" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M61.832,49.584c0-4.814-3.902-8.717-8.717-8.717s-8.717,3.902-8.717,8.717s3.902,8.717,8.717,8.717  S61.832,54.398,61.832,49.584z M53.115,13.5c-20.127,0-36.5,16.374-36.5,36.5h-6.23l10.564,13.22L31.514,50h-7.898  c0-16.267,13.232-29.5,29.5-29.5c16.266,0,29.5,13.233,29.5,29.5s-13.234,29.5-29.5,29.5c-7.939,0-15.389-3.108-20.973-8.754  l-4.977,4.924c6.91,6.983,16.125,10.83,25.949,10.83c20.125,0,36.5-16.374,36.5-36.5S73.24,13.5,53.115,13.5z"/></svg>  Restore</button>
        </li>

      </ul>

    </div>

  </div>
</template>

<script>
import Swal from 'sweetalert2';
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

    async handleDelete(companyId) {
      console.log('company', companyId);
      try {
        const response = await axios.delete(`/api/company/delete/${companyId}`);
        this.getCompanyList();
      } catch (error) {
        console.error(error);

      }
    },
    async handleRestore(companyId) {
      try {
        const response = await axios.put(` /api/company/restore/${companyId}`);
        console.log(response.data);
        this.getCompanyList();
      } catch (error) {
        console.error(error);
        // Handle any error that occurred during the DELETE request
      }
    },


    confirmDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: 'Once deleted, you will be able to recover this data!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.handleDelete(id);
        }
      });
    },
    confirmRestore(id){
      Swal.fire({
        title: 'Are you sure?',
        text: 'Company Restore!',
        icon: 'restore',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: ' #d33',
        confirmButtonText: 'Restore'
      }).then((result) => {
        if (result.isConfirmed) {
          this.handleRestore(id);
        }
      });
    }


  },
};
</script>
<style>
.header-bg{
  background: #eeefee;
}
</style>