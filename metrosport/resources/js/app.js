import './bootstrap';

import { createApp } from 'vue';
import LeagueList from './components/LeagueList.vue';

const app = createApp({});
app.component('league-list', LeagueList);
app.mount('#app');
