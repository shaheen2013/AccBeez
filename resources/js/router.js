import { createWebHistory, createRouter } from "vue-router";
import BillCreate from './components/Bills/Create.vue';
import BillList from './components/Bills/List.vue';
import Register from './components/Register/List.vue';

const routes = [
    {
        path: '/bills',
        name: 'BillList',
        component: BillList
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    // { path: '/bills/create', component: BillCreate },
    {
        path: '/bills/create',
        name: 'BillCreate',
        component: BillCreate
    },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
