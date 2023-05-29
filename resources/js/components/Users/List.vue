
<template>
    <div style="padding: 10px;">
        <h1>User List</h1>
        <el-button type="primary">
            <router-link to="/users/create" style="text-decoration: none; color: inherit;">Create</router-link>
        </el-button>

        <el-table :data="users">
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="email" label="Email" />
            <el-table-column prop="role" label="Role" />
            <el-table-column prop="id" label="Operations" >
                <template  #default="scope">
                    <router-link :to="'/users/edit/'+scope.row.id">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <Edit />
                        </el-icon>
                    </router-link>
                    <router-link :to="'/users/view/'+scope.row.id">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <View />
                        </el-icon>
                    </router-link>
                    <el-icon :size="20" :color="'red'"
                            style="width: 1em; height: 1em; margin-right: 8px"
                            @click="handleDelete(scope.row.id);"
                    >
                        <Delete />
                    </el-icon>
                </template>
            </el-table-column>
        </el-table>

    </div>
</template>



<script >
// import axios from 'axios';

import { ElMessage, ElMessageBox } from 'element-plus'

export default {
    name: 'UserList',
    data() {
        return {
            users: [],
        };
    },
    async mounted() {
        try {
            await axios.get(`/api/users`).
                    then((res) => {
                        console.log('res:', res);
                        this.users = res.data;
                    });
        } catch (error) {
            console.error(error);
        }

    },
    methods: {
        handleDelete(id){
            console.log(id);

            ElMessageBox.confirm(
                'proxy will permanently delete the file. Continue?',
                'Warning',
                {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }
            ).then(() => {
                axios.delete(`/api/users/`+id).
                    then((res) => {
                        console.log('res:', res);
                        this.users = res.data;
                        ElMessage({
                            type: 'success',
                            message: 'Delete completed',
                        })
                    });
            }).catch(() => {
                ElMessage({
                    type: 'info',
                    message: 'Delete canceled',
                })
            })

        }

    },
};
</script>

<style>
</style>
