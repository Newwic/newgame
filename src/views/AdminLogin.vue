<template>
  <div class="admin-login-wrapper">
    <div class="login-container">
      <div class="login-box">
        <div class="login-header">
          <i class="fas fa-user-shield login-icon"></i>
          <h2 class="login-title">Admin Panel</h2>
          <p class="login-subtitle">Please sign in to continue</p>
        </div>

        <form @submit.prevent="login">
          <div v-if="error" class="error-message">{{ error }}</div>
          <div class="input-group">
            <i class="fas fa-user icon"></i>
            <input v-model="username" placeholder="Username" required />
          </div>
          <div class="input-group">
            <i class="fas fa-lock icon"></i>
            <input v-model="password" type="password" placeholder="Password" required />
          </div>
          <button type="submit" class="login-btn">Login</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const username = ref('');
const password = ref('');
const error = ref(null);
const router = useRouter();

const login = () => {
  error.value = null;
  const ADMIN_USER = 'neww';
  const ADMIN_PASS = 'admin';

  if (username.value === ADMIN_USER && password.value === ADMIN_PASS) {
    localStorage.setItem('isAdminLoggedIn', 'true');
    router.push('/main-menu');
  } else {
    error.value = 'Invalid username or password';
    password.value = '';
  }
};
</script>

<style scoped>
.admin-login-wrapper {
  min-height: 100vh;
  background-color: #2c3e50; /* Dark blue-grey background */
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  font-family: 'Montserrat', sans-serif;
}

.login-container {
  width: 100%;
  max-width: 420px;
  background: #34495e; /* Slightly lighter card background */
  border-radius: 20px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
  overflow: hidden;
}

.login-box {
  padding: 3rem;
}

.login-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.login-icon {
  font-size: 3rem;
  color: #3498db; /* Blue icon */
  margin-bottom: 1rem;
}

.login-title {
  color: #fff;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.login-subtitle {
  color: #bdc3c7; /* Light grey subtitle */
  font-size: 1rem;
}

.input-group {
  display: flex;
  align-items: center;
  background: #2c3e50;
  border-radius: 12px;
  margin-bottom: 1.25rem;
  border: 1px solid #2c3e50;
  transition: border-color 0.3s;
}

.input-group:focus-within {
  border-color: #3498db;
}

.input-group .icon {
  color: #bdc3c7;
  padding: 0 15px;
}

.input-group input {
  flex: 1;
  background: transparent;
  border: none;
  outline: none;
  color: #fff;
  padding: 14px 0;
  font-size: 1rem;
}

.input-group input::placeholder {
  color: #95a5a6;
}

.login-btn {
  width: 100%;
  background: #3498db;
  color: #fff;
  border: none;
  padding: 14px 0;
  border-radius: 12px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s, box-shadow 0.3s;
  margin-top: 1rem;
}

.login-btn:hover {
  background: #2980b9;
  box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
}

.error-message {
  background-color: rgba(231, 76, 60, 0.15);
  color: #e74c3c;
  padding: 12px;
  border-radius: 10px;
  margin-bottom: 1rem;
  text-align: center;
  font-size: 0.9rem;
  border: 1px solid #e74c3c;
}
</style>
