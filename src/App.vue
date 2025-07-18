<template>
  <div id="app" class="app-bg">
    <BaseNavigation />
    <div class="app-content">
      <router-view />
    </div>
  </div>
</template>

<script>
import BaseNavigation from './components/Navigation.vue'
export default {
  name: 'App',
  components: { BaseNavigation }
}
</script>

<style>
body {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: rgb(0, 0, 0);
  margin-top: 60px;
  background: linear-gradient(120deg, #fffbe6 0%, #f0f9ff 100%);
  min-height: 100vh;
}
.app-bg {
  min-height: 100vh;
  background: transparent;
}
.app-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 24px 0 40px 0;
}
</style>

