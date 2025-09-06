<template>
  <nav class="main-nav">
    <div class="nav-content">
      <router-link to="/" class="brand">ข้าวมันไก่สุดมัน</router-link>
      <div class="nav-links">
        <!-- Links for Authenticated Users -->
        <template v-if="isAuthenticated">
          <router-link to="/main-menu">เมนูหลัก</router-link>
          <router-link to="/shop">ร้านค้า</router-link>
          <router-link to="/profile" v-if="currentUser">โปรไฟล์ ({{ currentUser.name }})</router-link>
          <a href="#" @click.prevent="handleLogout" class="logout-btn">ออกจากระบบ</a>
        </template>
        <!-- Links for Guests -->
        <template v-else>
          <router-link to="/login">เข้าสู่ระบบ</router-link>
          <router-link to="/register">สมัครสมาชิก</router-link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'BaseNavigation',
  computed: {
    // If currentUser is null, we provide a default object to prevent errors
    ...mapGetters('auth', {
      isAuthenticated: 'isAuthenticated',
      currentUser: 'currentUser'
    })
  },
  methods: {
    ...mapActions('auth', ['logout']),
    handleLogout() {
      this.logout(); // This calls the 'logout' action from Vuex
      this.$router.push('/login');
    }
  }
}
</script>

<style scoped>
.main-nav {
  background: linear-gradient(90deg, #e67e22, #f39c12);
  padding: 12px 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
}
.nav-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}
.brand {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 20px;
}
.nav-links a {
  color: white;
  text-decoration: none;
  font-weight: 600;
  padding: 8px 12px;
  border-radius: 6px;
  transition: background-color 0.2s;
}
.nav-links a:hover, .nav-links a.router-link-exact-active {
  background-color: rgba(255, 255, 255, 0.2);
}
.logout-btn {
  background-color: #c0392b;
  border: none;
  cursor: pointer;
}
.logout-btn:hover {
  background-color: #e74c3c;
}
</style>