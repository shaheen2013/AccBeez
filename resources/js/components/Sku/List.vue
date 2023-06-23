<template></template>
<script>
import {mapActions} from "vuex";

export default {
    methods: {
        getAllSkus(){
            try {
                axios()
            }catch (e) {

            }
        }
      //...mapActions('sku', ["getAllSkus"])
    },
    mounted() {
        this.getAllSkus()
    }
}
</script>
