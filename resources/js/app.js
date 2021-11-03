require('./bootstrap');

import { createApp } from 'vue';

// Vuex
import store from './store';

// Vue Router
import router from './router'

// App Component
import AppComponent from './components/App.vue'

const app = createApp(AppComponent)
app.use(router)
    .use(store)
    .mount("#feeder-app")