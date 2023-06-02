import { createWebHistory, createRouter } from "vue-router";
import BillCreate from './components/Bills/Create.vue';
import BillList from './components/Bills/List.vue';
import Register from './components/Register/List.vue';
import BomCreate from './components/Boms/Create.vue';
import BomList from './components/Boms/List.vue';
import UserCreate from './components/Users/Create.vue';
import UserList from './components/Users/List.vue';
import NotFoundPage from './components/NotFoundPage.vue';
import Dashboard from './components/Dashboard.vue';
import SalesList from "@/components/Sales/SalesList.vue";

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
    },
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
    {
        path: '/sales',
        name: 'SalesList',
        component: SalesList
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFoundPage
    },

    //   { path: '*', redirect: '/404' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
