import { createWebHistory, createRouter } from "vue-router";
import BillList from './components/Bills/List.vue';
import BillCreate from './components/Bills/Create.vue';
import BillShow from './components/Bills/Show.vue';
import Register from './components/Register/List.vue';
import RegisterShow from './components/Register/Show.vue';
import BomList from './components/Boms/List.vue';
import BomCreate from './components/Boms/Create.vue';
import BomShow from './components/Boms/Show.vue';
import UserCreate from './components/Users/Create.vue';
import UserList from './components/Users/List.vue';
import AssignUsers from './components/Users/AssignUsers.vue';
import NotFoundPage from './components/NotFoundPage.vue';
import Dashboard from './components/Dashboard.vue';
import SaleCreate from './components/Sales/Create.vue';
import SaleList from './components/Sales/List.vue';
import SaleShow from './components/Sales/Show.vue';
import ExlTable from "@/components/ExlTable/ExlTable.vue";

import BomSaleList from './components/BomSales/List.vue';
import BomSaleCreate from './components/BomSales/Create.vue';
import BomSaleShow from './components/BomSales/Show.vue';

import CogsList from './components/Cogs/List.vue';


const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/registers',
        name: 'registers',
        component: Register,
    },
    {
        path: '/registers/view/:id',
        name: 'RegisterShow',
        component: RegisterShow,
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
        name: 'BillShow',
        component: BillShow
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
        component: BomShow
    },

    {
        path: '/users',
        name: 'UserList',
        component: UserList
    },
    {
        path: '/assign-users',
        name: 'AssignUsers',
        component: AssignUsers
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
        name: 'SaleList',
        component: SaleList
    },  {
        path: '/exl-table',
        name: 'ExlTable',
        component: ExlTable
    },
    {
        path: '/sales/create',
        name: 'SaleCreate',
        component: SaleCreate
    },
    {
        path: '/sales/edit/:id',
        name: 'SaleEdit',
        component: SaleCreate
    },
    {
        path: '/sales/view/:id',
        name: 'SaleShow',
        component: SaleShow
    },

    {
        path: '/bomSales',
        name: 'BomSaleList',
        component: BomSaleList
    },
    {
        path: '/bomSales/create',
        name: 'BomSaleCreate',
        component: BomSaleCreate
    },
    {
        path: '/bomSales/edit/:id',
        name: 'BomSaleEdit',
        component: BomSaleCreate
    },
    {
        path: '/bomSales/view/:id',
        name: 'BomSaleShow',
        component: BomSaleShow
    },
    {
        path: '/cogs',
        name: 'CogsList',
        component: CogsList
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
