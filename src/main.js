import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store' // Import the store
import http from './http' // Import the http.js file

Vue.config.productionTip = false

Vue.prototype.$http = http // Attach http to Vue prototype

new Vue({
  router,
  store, // Add the store to the Vue instance
  render: h => h(App),
}).$mount('#app')