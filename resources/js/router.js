import { createWebHistory, createRouter } from "vue-router";
import BillCreate from './components/Bills/Create.vue';
import BillList from './components/Bills/List.vue';
import Register from './components/Register/List.vue';
import BomCreate from './components/Boms/Create.vue';
import BomList from './components/Boms/List.vue';
import UserCreate from './components/Users/Create.vue';
import UserList from './components/Users/List.vue';

const routes = [
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    {
        path: '/bills',
        name: 'BillList',
        component: BillList
    },
    {
        path: '/bills/create',
        name: 'BillCreate',
        component: BillCreate
    },
    {
        path: '/bills/edit/:id',
        name: 'BillEdit',
        component: BillCreate
    },
    {
        path: '/bills/view/:id',
        name: 'BillView',
        component: BillCreate
    },
    {
        path: '/boms',
        name: 'BomList',
        component: BomList
    },
    {
        path: '/boms/create',
        name: 'BomCreate',
        component: BomCreate
    },
    {
        path: '/boms/edit/:id',
        name: 'BomEdit',
        component: BomCreate
    },
    {
        path: '/boms/view/:id',
        name: 'BomView',
        component: BomCreate
    },



    {
        path: '/users',
        name: 'UserList',
        component: UserList
    },
    {
        path: '/users/create',
        name: 'UserCreate',
        component: UserCreate
    },
    {
        path: '/users/edit/:id',
        name: 'UserEdit',
        component: UserCreate
    },
    {
        path: '/users/view/:id',
        name: 'UserView',
        component: UserCreate
    },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
