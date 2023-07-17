<template>
    <div v-if="isCreated">
        <div v-if="(logged_in_user && logged_in_user.role === 'Super-Admin') || operation === 'view'">
            <el-form ref="ruleFormRef" :model="user" class="demo-user"
                label-position="top"
                status-icon
            >
                <el-text tag="b"  v-if="operation === 'view'" type="primary" size="large">View User</el-text>
                <el-text tag="b"  v-if="operation === 'edit'" type="primary" size="large">Edit User</el-text>
                <el-text tag="b"  v-if="operation === 'create'" type="primary" size="large">Create User</el-text>

                <el-form-item label="Name" prop="name">
                    <el-input class="w-50" v-model="user.name" type="text" :disabled="operation === 'view'" />
                </el-form-item>
                <el-form-item label="Email" prop="email">
                    <el-input class="w-50" v-model="user.email" type="email" :disabled="operation === 'view'" />
                </el-form-item>
                <el-form-item label="Select User Type">
                    <el-select v-model="user.user_type" placeholder="Select" class="mt-2 w-50" @change="userTypeHandler">
                        <el-option 
                            v-for="item in userTypes"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        />
                    </el-select>
                </el-form-item>

                <div v-if="isUserType">
                    <el-form-item label="Select Company">
                        <el-select v-model="user.company_id" placeholder="Select Company" class="mb-3 w-50">
                            <el-option
                                v-for="item in companies"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id"
                            >
                            </el-option>
                        </el-select>
                    </el-form-item>
                    
                </div>
                

                <el-row>
                    <el-col>
                        <el-button v-if="operation === 'create'" type="primary" @click="createUser" :loading="isLoading" class="me-2" >Create</el-button>
                        <el-button v-if="operation === 'edit'" type="primary" @click="updateUser" class="me-2">Update</el-button>
                        <router-link :to="'/users'">
                            <el-button type="info" class="me-2">Back</el-button>
                        </router-link>
                    </el-col>
                </el-row>
            </el-form>

        </div>
        <div v-else>
            <NotFoundPage />
        </div>
    </div>
</template>

<script >

import { showErrors } from '@/utils/helper.js';
import NotFoundPage from "../NotFoundPage.vue";

export default {
    name: 'UserCreate',
    data() {
        return {
            routeName: '',
            operation: 'create',
            isLoading: false,
            user : {
                id: null,
                name: '',
                email: '',
                user_type: '',
                company_id:'',
            },
            userTypes: [
                {
                    value: 'Admin',
                    label: 'Admin'
                },
                {
                    value: 'User',
                    label: 'User'
                }
            ],
            selectedCompany: '',
            companies: null,
            logged_in_user: null,
            isCreated: false,
            isUserType: false,
        };
    },
    components:{
        NotFoundPage
    },
    async created() {
        if(this.$route.name == 'UserCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'UserEdit'){
            this.operation = 'edit';
            this.user.id = this.$route.params.id;
        } else {
            this.operation = 'view';
            this.user.id = this.$route.params.id;
        }
        if(this.user.id){
            axios.get(`/api/users/edit/`+this.user.id).
                    then((res) => {
                        console.log('res:', res);
                        this.user.id = res.data.user.id;
                        this.user.name = res.data.user.name;
                        this.user.email = res.data.user.email;
                        this.user.user_type = res.data.roles[0];
                    });
            console.log('User edit', this.user)
        }

        await axios.get(`/logged_in_user`).
                then((res) => {
                    this.logged_in_user = res.data;
                    console.log('logged_in_user:', this.logged_in_user);
                });
        this.isCreated = true;

        await axios.get('/api/companies')
                    .then(res => {
                        this.companies = res.data.data
                    })
    },
    methods: {
        submitForm(){
            console.log(this.user)
        },
        userTypeHandler(value){
            console.log('userTypeHandler_value', value);
            if(value === 'User'){
                this.isUserType = true
            }else{
                this.isUserType = false
            }
        },
        async createUser() {
            console.log('createUser:', this.user)

            try {
                this.isLoading = true;
                await axios.post(`/api/users`, this.user).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            const query = { message: 'User Created Successfully!' }
                            this.$router.push({path:'/users', query : query});
                            this.isLoading = false;
                        });
            } catch (error) {
                console.log(error);
                showErrors(error);
                this.isLoading = false;
                console.error('error in response:', error.response.data);
            }
        },
        async updateUser() {
            console.log('updateUser:', this.user)
            try {
                await axios.post(`/api/users/`+this.user.id, this.user).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/users');
                        });
            } catch (error) {
                showErrors(error);
                console.error(error);
            }
        },
    },
};
</script>


<style >
    .demo-user {
        padding: 10px;
    }
    .el-form-item__label {
        font-weight:bold !important;
        color: #212529;
    }
    .el-icon.is-loading {
     font-size: 24px;
      color: black;
    }
</style>
