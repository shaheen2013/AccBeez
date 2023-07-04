import { createWebHistory, createRouter } from "vue-router";

import CompanyList from './components/Companies/List.vue'
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

import DashboardLayout from './layout/DashboardLayout.vue'


const routes = [
    {
        path: '/',
        component: CompanyList,
    },
    {
        path: '/dashboard',
        component: DashboardLayout,
        name: 'dashboard',
        children: [
            {
                path: '/company/:comp_id/dashboard',
                component: Dashboard,
            },
            {
                path: '/company/:comp_id/registers',
                name: 'registers',
                component: Register,
            },
            {
                path: '/company/:comp_id/registers/view/:id',
                name: 'RegisterShow',
                component: RegisterShow,
            },
            {
                path: '/company/:comp_id/bills',
                name: 'BillList',
                component: BillList
            },
            {
                path: '/company/:comp_id/bills/create',
                name: 'BillCreate',
                component: BillCreate
            },
            {
                path: '/company/:comp_id/bills/edit/:id',
                name: 'BillEdit',
                component: BillCreate
            },
            {
                path: '/company/:comp_id/bills/view/:id',
                name: 'BillShow',
                component: BillShow
            },

            {
                path: '/company/:comp_id/boms',
                name: 'BomList',
                component: BomList
            },
            {
                path: '/company/:comp_id/boms/create',
                name: 'BomCreate',
                component: BomCreate
            },
            {
                path: '/company/:comp_id/boms/edit/:id',
                name: 'BomEdit',
                component: BomCreate
            },
            {
                path: '/company/:comp_id/boms/view/:id',
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
                path: '/company/:comp_id/sales',
                name: 'SaleList',
                component: SaleList
            }, {
                path: '/exl-table',
                name: 'ExlTable',
                component: ExlTable
            },
            {
                path: '/company/:comp_id/sales/create',
                name: 'SaleCreate',
                component: SaleCreate
            },
            {
                path: '/company/:comp_id/sales/edit/:id',
                name: 'SaleEdit',
                component: SaleCreate
            },
            {
                path: '/company/:comp_id/sales/view/:id',
                name: 'SaleShow',
                component: SaleShow
            },

            {
                path: '/company/:comp_id/bomSales',
                name: 'BomSaleList',
                component: BomSaleList
            },
            {
                path: '/company/:comp_id/bomSales/create',
                name: 'BomSaleCreate',
                component: BomSaleCreate
            },
            {
                path: '/company/:comp_id/bomSales/edit/:id',
                name: 'BomSaleEdit',
                component: BomSaleCreate
            },
            {
                path: '/company/:comp_id/bomSales/view/:id',
                name: 'BomSaleShow',
                component: BomSaleShow
            },
            {
                path: '/company/:comp_id/cogs',
                name: 'CogsList',
                component: CogsList
            },

        ]
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
