import './bootstrap';

import { createApp } from 'vue';
import Lliga from './components/Lliga.vue';
import Lligues from './components/Lligues.vue';
import Chat from './components/Chat.vue';

const app = createApp({});

app.component('lliga', Lliga);
app.component('lligues', Lligues);
app.component('chat', Chat);

app.mount('#app');
