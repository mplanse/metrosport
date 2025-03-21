import './bootstrap';

import { createApp } from 'vue';
import Lliga from './components/Lliga.vue';    // Importem el component

const app = createApp({});
app.component('lliga', Lliga);
app.mount('#app');
