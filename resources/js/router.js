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
import getLogedInUser from "./helper";

import { useAuthUserStore } from "@/stores/AuthUser";


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
                path: '/:slug/dashboard',
                component: Dashboard,
            },
            {
                path: '/:slug/registers',
                name: 'registers',
                component: Register,
            },
            {
                path: '/:slug/registers/view/:id',
                name: 'RegisterShow',
                component: RegisterShow,
            },
            {
                path: '/:slug/bills',
                name: 'BillList',
                component: BillList,
                meta: { requiresAuth: true },
            },
            {
                path: '/:slug/bills/create',
                name: 'BillCreate',
                component: BillCreate
            },
            {
                path: '/:slug/bills/edit/:id',
                name: 'BillEdit',
                component: BillCreate
            },
            {
                path: '/:slug/bills/view/:id',
                name: 'BillShow',
                component: BillShow
            },

            {
                path: '/:slug/boms',
                name: 'BomList',
                component: BomList
            },
            {
                path: '/:slug/boms/create',
                name: 'BomCreate',
                component: BomCreate
            },
            {
                path: '/:slug/boms/edit/:id',
                name: 'BomEdit',
                component: BomCreate
            },
            {
                path: '/:slug/boms/view/:id',
                name: 'BomView',
                component: BomShow
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
                path: '/:slug/sales',
                name: 'SaleList',
                component: SaleList
            }, {
                path: '/exl-table',
                name: 'ExlTable',
                component: ExlTable
            },
            {
                path: '/:slug/sales/create',
                name: 'SaleCreate',
                component: SaleCreate
            },
            {
                path: '/:slug/sales/edit/:id',
                name: 'SaleEdit',
                component: SaleCreate
            },
            {
                path: '/:slug/sales/view/:id',
                name: 'SaleShow',
                component: SaleShow
            },

            {
                path: '/:slug/bomSales',
                name: 'BomSaleList',
                component: BomSaleList
            },
            {
                path: '/:slug/bomSales/create',
                name: 'BomSaleCreate',
                component: BomSaleCreate
            },
            {
                path: '/:slug/bomSales/edit/:id',
                name: 'BomSaleEdit',
                component: BomSaleCreate
            },
            {
                path: '/:slug/bomSales/view/:id',
                name: 'BomSaleShow',
                component: BomSaleShow
            },
            {
                path: '/:slug/cogs',
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
router.beforeEach((to, from) => {
    const loggedUser = useAuthUserStore();
    loggedUser.loggedUser();


    const user = getLogedInUser();
    console.log('user:', user);
    console.log('to:', to);
    if (to.meta.requiresAuth) {

    //  window.location.href = '/login';
     return;
    //   return {
    //     path: '/login',
    //   }
    }
  });


export default router;
