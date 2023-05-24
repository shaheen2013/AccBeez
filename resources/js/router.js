import { createWebHistory, createRouter } from "vue-router";
import BillCreate from './components/Bills/Create.vue';
import BillList from './components/Bills/List.vue';

const routes = [
    { path: '/bills', component: BillList },
    { path: '/bills/create', component: BillCreate },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
