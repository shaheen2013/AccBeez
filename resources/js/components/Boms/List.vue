
<template>
    <div style="padding: 10px;">
        <h1>BOM List</h1>
        <el-button type="primary" size="medium">
            <!-- <router-link to="/boms/create">Create</router-link> -->
            <router-link to="/boms/create" style="text-decoration: none; color: inherit;">Create</router-link>
        </el-button>

        <el-table :data="boms">
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="id" label="Operations" >

                <template  #default="scope">
                    <router-link :to="'/boms/edit/'+scope.row.id">
                        <el-button
                            type="primary"
                            size="small"
                            icon="el-icon-edit"
                        >
                            Edit
                        </el-button>
                    </router-link>
                    <router-link :to="'/boms/view/'+scope.row.id">
                        <el-button
                            type="warning"
                            size="small"
                            icon="el-icon-view"
                        >
                            View
                        </el-button>
                    </router-link>
                    <el-button
                        type="danger"
                        size="small"
                        icon="el-icon-delete"
                        @click="handleDelete(scope.row.id);"
                    >
                        Delete
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

    </div>
</template>



<script >

import BomItem from "./Item.vue";
export default {
    name: 'BomList',
    data() {
        return {
            boms: []
        };
    },
    components:{
        BomItem
    },
    async mounted() {
        try {
            await axios.get(`/api/boms`).
                    then((res) => {
                        console.log('res:', res);
                        this.boms = res.data;
                    });
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        handleDelete(id){
            console.log(id);
        }

    },
};
</script>

<style>
a href{
    text-decoration: none;
    color: inherit;
}
</style>
