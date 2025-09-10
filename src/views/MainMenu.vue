<template>
  <div class="main-menu-wrapper">
    <div class="main-menu-container">
      <div v-if="isAuthenticated" class="authenticated-view">
        <div class="hero-section">
          <h1>ยินดีต้อนรับสู่ร้านอาหารของเรา</h1>
          <p>ค้นพบเมนูแสนอร่อยและข้อเสนอสุดพิเศษของเรา</p>
        </div>
        <div class="menu-grid">
          <router-link class="menu-card" to="/shop">
            <i class="fas fa-store-alt menu-icon"></i>
            <span class="menu-title">Shop</span>
          </router-link>
          <router-link class="menu-card" to="/news">
            <i class="fas fa-newspaper menu-icon"></i>
            <span class="menu-title">News</span>
          </router-link>
          <router-link class="menu-card" to="/contact">
            <i class="fas fa-address-book menu-icon"></i>
            <span class="menu-title">Contact</span>
          </router-link>
          <router-link class="menu-card" to="/about">
            <i class="fas fa-info-circle menu-icon"></i>
            <span class="menu-title">About</span>
          </router-link>
        </div>
        <button class="logout-btn" @click="logout">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </button>
      </div>
      <div v-else class="unauthenticated-view">
        <div class="login-prompt">
          <i class="fas fa-lock login-icon"></i>
          <h2>Please Log In</h2>
          <p>You need to be logged in to access the main menu.</p>
          <div class="button-group">
            <router-link to="/login" class="btn btn-primary">Login</router-link>
            <router-link to="/register" class="btn btn-secondary">Register</router-link>
          </div>
        </div>
      </div>
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
    ...mapActions('auth', { vuexLogout: 'logout' }),
    logout() {
      this.vuexLogout();
      this.$router.push('/login');
    }
  }
}
</script>

<style scoped>
.main-menu-wrapper {
  min-height: 100vh;
  background: #f4f7f6;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Montserrat', sans-serif;
}

.main-menu-container {
  width: 100%;
  max-width: 900px;
  margin: 2rem;
}

/* Authenticated View */
.authenticated-view {
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  padding: 3rem;
  text-align: center;
}

.hero-section {
  margin-bottom: 3rem;
}

.hero-section h1 {
  font-size: 2.8rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 0.5rem;
}

.hero-section p {
  font-size: 1.1rem;
  color: #777;
}

.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.menu-card {
  background: #f9f9f9;
  border-radius: 15px;
  padding: 2rem;
  text-align: center;
  text-decoration: none;
  color: #444;
  transition: transform 0.3s, box-shadow 0.3s;
  border: 1px solid #eee;
}

.menu-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.menu-icon {
  font-size: 2.5rem;
  color: #e67e22;
  margin-bottom: 1rem;
  display: block;
}

.menu-title {
  font-size: 1.1rem;
  font-weight: 600;
}

.logout-btn {
  background: #e74c3c;
  color: #fff;
  border: none;
  padding: 12px 30px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.logout-btn:hover {
  background: #c0392b;
}

/* Unauthenticated View */
.unauthenticated-view {
  text-align: center;
}

.login-prompt {
  background: #fff;
  border-radius: 20px;
  padding: 4rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.login-icon {
  font-size: 4rem;
  color: #e67e22;
  margin-bottom: 1.5rem;
}

.login-prompt h2 {
  font-size: 2rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 0.5rem;
}

.login-prompt p {
  font-size: 1.1rem;
  color: #777;
  margin-bottom: 2rem;
}

.button-group {
  display: flex;
  justify-content: center;
  gap: 1rem;
}

.btn {
  padding: 12px 30px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 1rem;
  text-decoration: none;
  transition: background 0.3s;
}

.btn-primary {
  background: #e67e22;
  color: #fff;
}

.btn-primary:hover {
  background: #d35400;
}

.btn-secondary {
  background: #3498db;
  color: #fff;
}

.btn-secondary:hover {
  background: #2980b9;
}
</style>
