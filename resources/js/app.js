import './bootstrap';
import { createApp } from 'vue';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import router from './router'
import Welcome from './Welcome.vue';
import App from './App.vue';




const app = createApp(App)
            .use(router)
            .use(ElementPlus);

app.mount('#app');
