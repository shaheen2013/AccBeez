<template>
    <div class="mt-2">
        <h1>Assign Users</h1>
        <el-form ref="ruleFormRef" 
                label-position="top"
                status-icon>
            
            <div>
                <el-form-item label="Select Admin" prop="admin">
                    <el-select v-model="formData.admin" class="w-50">
                        <el-option 
                            v-for="item in admins"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select>
                </el-form-item>
            </div>
            <div>
                <el-form-item label="Select User" prop="user">
                    <el-select v-model="formData.user" class="w-50" multiple>
                        <el-option 
                            v-for="item in users"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select>
                </el-form-item>
            </div>
            <el-row>
                <el-col>
                    <el-button type="primary" @click="createAssignUser" class="me-2">Submit</el-button>
                </el-col>
            </el-row>
        </el-form>
    </div>
</template>

<script>

export default {
    data() {
        return {
            formData: {
                admin: '',
                user: ''
            },
            users: [],
            admins: []
        }
    },
    async mounted() {
        try {
            await axios.get(`/api/get-users-by-role`).
                    then((res) => {
                        console.log('res:', res);
                        this.users = res.data.users;
                        this.admins = res.data.admins;
                    });
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        async createAssignUser() {
            const postData = {
                admin: this.formData.admin,
                user: this.formData.user
            }
            console.log('postData=========', postData);
            try{
                await axios.post('/api/assign-user', postData)
                        .then(res => {
                            console.log('assignUser_res', res);
                        })
            } catch (error) {
                console.error(error);
            }
        }
    }
}

</script>