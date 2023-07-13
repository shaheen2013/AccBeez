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
      <ul class="mt-3 list-unstyled ">
        <li class="d-flex align-items-center justify-content-between">
          <p class="fw-bold fs-6">New company</p>
          <button class="btn btn-danger fw-bolder">Delete</button>
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
      registers: [],



    };


  },
  async mounted() {
    try {
      await this.getList();
    } catch (error) {
      console.error(error);
    }
  },
  methods: {

    getqty(value) {
      let qty = this.formattedAverage(value);
      if (qty) {
        console.log(qty[1])
        return qty[1] ?? 1;
      }
    },

    getvalue(value) {
      let qty = this.formattedAverage(value);
      if (qty) {
        return qty[0];
      }
    },


  },
};
</script>