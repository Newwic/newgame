<template>
  <div class="main-menu-container">
    <div class="khao-mun-gai-img-wrapper">
      <img src="@/assets/now.png" alt="" class="khao-mun-gai-img" />
      <img src="@/assets/now1.png" alt="" class="khao-mun-gai-deco deco-left" />
      <img src="@/assets/now2.png" alt="" class="khao-mun-gai-deco deco-right" />
    </div>
    <h1>เมนูหลัก</h1>
    <div v-if="isAuthenticated" class="menu-links">
      <router-link class="menu-link" to="/shop">Shop</router-link>
      <router-link class="menu-link" to="/news">News</router-link>
      <router-link class="menu-link" to="/contact">Contact</router-link>
      <router-link class="menu-link" to="/about">About</router-link>
      <button class="logout-btn" @click="logout">Logout</button>
    </div>
    <div v-else class="menu-login-message">
      <p>กรุณาสมัครสมาชิกหรือเข้าสู่ระบบก่อนเข้าดูเมนู</p>
      <router-link to="/register"><button class="register-btn">สมัครสมาชิก</button></router-link>
      <router-link to="/login"><button class="login-btn">เข้าสู่ระบบ</button></router-link>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'MainMenuView',
  computed: {
    ...mapGetters('auth', ['isAuthenticated'])
  },
  methods: {
    ...mapActions('auth', ['logout']),
    logout() {
      this.logout(); // Call the Vuex logout action
      this.$router.push('/login');
    }
  }
}
</script>

<style scoped>
.main-menu-container {
  max-width: 400px;
  margin: 60px auto;
  padding: 36px 24px;
  background: #f0f9ff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(44,62,80,0.10);
  text-align: center;
}
.khao-mun-gai-img-wrapper {
  position: relative;
  width: 220px;
  height: 200px;
  margin: 0 auto 24px auto;
}
.khao-mun-gai-img {
  display: block;
  margin: 0 auto;
  width: 180px;
  height: 180px;
  object-fit: cover;
  border-radius: 50%;
  border: 6px solid #ffe082;
  box-shadow: 0 4px 24px rgba(230, 103, 34, 0.18);
  background: #fffbe7;
  transition: transform 0.2s;
  position: relative;
  z-index: 2;
}
.khao-mun-gai-img:hover {
  transform: scale(1.05) rotate(-2deg);
  box-shadow: 0 8px 32px rgba(230, 103, 34, 0.28);
}
.khao-mun-gai-deco {
  position: absolute;
  width: 54px;
  height: 54px;
  object-fit: contain;
  opacity: 0.85;
  z-index: 1;
  filter: drop-shadow(0 2px 8px rgba(230, 103, 34, 0.12));
}
.deco-left {
  left: -18px;
  top: 24px;
  transform: rotate(-18deg) scale(0.95);
}
.deco-right {
  right: -18px;
  top: 48px;
  transform: rotate(14deg) scale(1.05);
}
.main-menu-container h1 {
  color: #e67e22;
  margin-bottom: 24px;
}
.menu-links {
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.menu-link {
  background: #e67e22;
  color: #fff;
  border-radius: 8px;
  padding: 14px 0;
  font-size: 1.2rem;
  font-weight: bold;
  text-decoration: none;
  transition: background 0.2s;
}
.menu-link:hover {
  background: #f39c12;
}
.menu-login-message {
  margin-top: 24px;
}
.register-btn, .login-btn {
  background: #e67e22;
  color: #fff;
  border: none;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: bold;
  margin: 8px;
  cursor: pointer;
}
.register-btn:hover, .login-btn:hover {
  background: #f39c12;
}
.logout-btn {
  background: #b71c1c;
  color: #fff;
  border: none;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: bold;
  margin-top: 18px;
  cursor: pointer;
  transition: background 0.2s;
}
.logout-btn:hover {
  background: #e53935;
}
</style>