import './bootstrap';
import 'vuetify/styles';
import { createApp } from "vue";
import { createVuetify } from 'vuetify';
import '@mdi/font/css/materialdesignicons.css';
import App from '@/App.vue';
import router from './router';
import axios from 'axios';
import VueQrcode from '@chenfengyuan/vue-qrcode';
import { VueQrcodeReader } from 'vue-qrcode-reader';

const app = createApp(App);
const vuetify = createVuetify();

export const APP_URL = "http://localhost";

const apiClient = axios.create({
    baseURL: APP_URL,
});

app.config.globalProperties.$api = apiClient;

app.component(VueQrcode.name, VueQrcode);
app.use(VueQrcodeReader)
app.use(router);
app.use(vuetify);
app.mount('#app');