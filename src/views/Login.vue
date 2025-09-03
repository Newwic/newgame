<template>
  <div class="login-bg">
    <div class="login-box">
      <!-- Login Mode Toggle -->
      <div class="login-toggle">
        <button @click="setLoginMode('user')" :class="{ active: loginMode === 'user' }" class="toggle-btn">สมาชิก</button>
        <button @click="setLoginMode('admin')" :class="{ active: loginMode === 'admin' }" class="toggle-btn">แอดมิน</button>
      </div>

      <!-- User Login Form -->
      <div v-if="loginMode === 'user'">
        <h2 class="login-title">เข้าสู่ระบบร้านข้าวมันไก่</h2>
        <form @submit.prevent="loginUser">
          <div class="input-group">
            <span class="icon"><i class="fa fa-user"></i></span>
            <input v-model="email" type="email" placeholder="อีเมล" required />
          </div>
          <div class="input-group">
            <span class="icon"><i class="fa fa-lock"></i></span>
            <input v-model="password" type="password" placeholder="รหัสผ่าน" required />
          </div>
          <button type="submit" class="login-btn">เข้าสู่ระบบ</button>
          <div class="login-options">
            <a href="#" class="forgot">ลืมรหัสผ่าน? คลิกที่นี่</a>
          </div>
          <router-link to="/register">
            <button type="button" class="register-btn">สมัครสมาชิก</button>
          </router-link>
        </form>
      </div>

      <!-- Admin Login Form -->
      <div v-if="loginMode === 'admin'">
        <h2 class="login-title">เข้าสู่ระบบแอดมิน</h2>
        <form @submit.prevent="loginAdmin">
           <div v-if="adminError" class="error-message">{{ adminError }}</div>
          <div class="input-group">
            <span class="icon"><i class="fa fa-user-shield"></i></span>
            <input v-model="adminUsername" placeholder="ชื่อผู้ใช้แอดมิน" required />
          </div>
          <div class="input-group">
            <span class="icon"><i class="fa fa-lock"></i></span>
            <input v-model="adminPassword" type="password" placeholder="รหัสผ่านแอดมิน" required />
          </div>
          <button type="submit" class="login-btn admin-btn">เข้าสู่ระบบแอดมิน</button>
        </form>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return {
      loginMode: 'user', // 'user' or 'admin'
      
      // User login fields
      email: '',
      password: '',

      // Admin login fields
      adminUsername: '',
      adminPassword: '',
      adminError: null,
    }
  },
  methods: {
    setLoginMode(mode) {
      this.loginMode = mode;
      this.adminError = null; // Reset error on mode switch
    },
    loginUser() {
      // Mock user login
      localStorage.setItem('isLoggedIn', 'true');
      alert('เข้าสู่ระบบสมาชิกสำเร็จ!');
      this.$router.push('/main-menu');
    },
    loginAdmin() {
      this.adminError = null;
      const ADMIN_USER = 'admin';
      const ADMIN_PASS = 'password123';

      if (this.adminUsername === ADMIN_USER && this.adminPassword === ADMIN_PASS) {
        localStorage.setItem('isAdminLoggedIn', 'true');
        alert('เข้าสู่ระบบแอดมินสำเร็จ!');
        this.$router.push('/main-menu'); // or an admin dashboard
      } else {
        this.adminError = 'ชื่อผู้ใช้หรือรหัสผ่านแอดมินไม่ถูกต้อง';
        this.adminPassword = '';
      }
    }
  }
}
</script>

<style scoped>
.login-bg {
  min-height: 100vh;
  background: linear-gradient(135deg, #fffbe6 0%, #f0f9ff 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}
.login-box {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 4px 32px rgba(230,126,34,0.18);
  padding: 38px 32px 28px 32px;
  width: 340px;
  text-align: center;
  border: 2.5px solid #e67e22;
}

.login-toggle {
  display: flex;
  justify-content: center;
  margin-bottom: 24px;
  background-color: #f0f0f0;
  border-radius: 8px;
  padding: 4px;
}
.toggle-btn {
  flex: 1;
  padding: 8px;
  border: none;
  background: transparent;
  cursor: pointer;
  font-weight: bold;
  color: #aaa;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}
.toggle-btn.active {
  background-color: #e67e22;
  color: white;
  box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.login-title {
  color: #e67e22;
  font-size: 1.3rem;
  letter-spacing: 2px;
  margin-bottom: 28px;
  font-weight: bold;
}
.input-group {
  display: flex;
  align-items: center;
  background: #fffbe6;
  border-radius: 8px;
  margin-bottom: 18px;
  padding: 0 12px;
  border: 1.5px solid #e67e22;
}
.input-group .icon {
  color: #e67e22;
  margin-right: 8px;
  font-size: 1.1rem;
}
.input-group input {
  background: transparent;
  border: none;
  outline: none;
  color: #444;
  padding: 12px 0;
  flex: 1;
  font-size: 1.08rem;
}
.login-btn {
  width: 100%;
  background: linear-gradient(90deg, #e67e22 60%, #f39c12 100%);
  color: #fff;
  border: none;
  padding: 12px 0;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1.08rem;
  cursor: pointer;
  transition: background 0.2s;
  margin-top: 8px;
  letter-spacing: 1px;
  box-shadow: 0 2px 8px rgba(230,126,34,0.10);
}
.login-btn:hover {
  background: #e67e22;
  color: #fff;
}
.admin-btn {
    background: linear-gradient(90deg, #3498db 60%, #5dade2 100%);
}
.admin-btn:hover {
    background: #2980b9;
}
.register-btn {
  width: 100%;
  background: linear-gradient(90deg, #f39c12 60%, #e67e22 100%);
  color: #fff;
  border: none;
  padding: 12px 0;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1.08rem;
  cursor: pointer;
  transition: background 0.2s;
  margin-top: 18px;
  letter-spacing: 1px;
  box-shadow: 0 2px 8px rgba(230,126,34,0.10);
}
.register-btn:hover {
  background: #f39c12;
  color: #fff;
}
.login-options {
  margin-top: 12px;
  margin-bottom: 0;
}
.forgot {
  color: #e67e22;
  text-decoration: underline;
  font-size: 1rem;
}
.error-message {
  background-color: #fdd;
  color: #c00;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 15px;
  text-align: center;
}
</style>