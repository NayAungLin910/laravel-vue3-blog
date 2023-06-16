import './bootstrap';
import { createApp } from 'vue';
import App from "../js/App.vue";
import '../css/app.css';
import './router';
import router from './router';

const app = createApp(App)

// use router
app.use(router);

app.mount('#app')
