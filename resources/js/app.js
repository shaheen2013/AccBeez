/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import Welcome from './Welcome.vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

// const app = createApp(Welcome);
const app = createApp({});
app.use(ElementPlus)

import ExampleComponent from './components/ExampleComponent.vue';
import App from './App.vue';
import BillCreate from './components/Bills/Create.vue';
import BillList from './components/Bills/List.vue';
// import BillItem from './components/Bills/Item.vue';
// import ExampleComponent from './components/ExampleComponent.vue';
// import ExampleComponent from './components/ExampleComponent.vue';
// import ExampleComponent from './components/ExampleComponent.vue';
// import ExampleComponent from './components/ExampleComponent.vue';


app.component('example-component', ExampleComponent)
    .component('app', App)
    .component('bill-create', BillCreate)
    .component('bill-list', BillList)
    // .component('bill-item', BillItem)
    ;


app.mount('#app');
