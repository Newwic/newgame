<template>
  <div class="login-view-wrapper">
    <div class="login-container">
      <!-- Left Panel: Welcome Message and Image -->
      <div class="welcome-panel">
        <img src="../assets/now.png" alt="NewGame Welcome" class="welcome-image"/>
        <h1 class="welcome-title">ยินดีต้อนรับสู่ NewGame</h1>
        <p class="welcome-subtitle">จักรวาลแห่งใหม่ของเหล่าเกมเมอร์</p>
      </div>

      <!-- Right Panel: Login Form -->
      <div class="login-panel">
        <div class="login-box">
          <!-- Login Mode Toggle -->
          <div class="login-toggle">
            <button @click="setLoginMode('user')" :class="{ active: loginMode === 'user' }" class="toggle-btn">สมาชิก</button>
            <button @click="setLoginMode('admin')" :class="{ active: loginMode === 'admin' }" class="toggle-btn">แอดมิน</button>
          </div>

          <!-- User Login Form -->
          <div v-if="loginMode === 'user'">
            <h2 class="login-title">เข้าสู่ระบบ</h2>
            <form @submit.prevent="loginUser">
              <div class="input-group">
                <span class="icon"><i class="fa fa-user"></i></span>
                <input v-model="email" type="email" placeholder="อีเมล" required />
              </div>
              <div class="input-group">
                <span class="icon"><i class="fa fa-lock"></i></span>
                <input v-model="password" type="password" placeholder="รหัสผ่าน" required />
              </div>
              <div v-if="error" class="error-message">{{ error }}</div>
              <div v-if="showRegisterPrompt" class="register-prompt">
                ยังไม่มีบัญชีสำหรับอีเมลนี้หรือไม่? <router-link to="/register"><strong>สมัครสมาชิกที่นี่</strong></router-link>
              </div>
              <button type="submit" class="login-btn">เข้าสู่ระบบ</button>
              <div class="login-options">
                <a href="#" class="forgot">ลืมรหัสผ่าน?</a>
              </div>
              <router-link to="/register" class="register-link">ยังไม่มีบัญชี? <strong>สมัครสมาชิกที่นี่</strong></router-link>
            </form>
          </div>

          <!-- Admin Login Form -->
          <div v-if="loginMode === 'admin'">
            <h2 class="login-title">แอดมิน</h2>
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
    </div>
  </div>
</template>

<script>
// The script content remains the same as before
export default {
  name: 'LoginView',
  data() {
    return {
      loginMode: 'user', // 'user' or 'admin'
      
      // User login fields
      email: '',
      password: '',
      error: null, // for user login errors
  showRegisterPrompt: false,

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
      this.error = null;
    },
    async loginUser() {
      this.error = null;
      this.showRegisterPrompt = false;
      try {
        await this.$store.dispatch('auth/login', {
          email: this.email,
          password: this.password
        });
        this.$router.push('/main-menu');
      } catch (err) {
        this.showRegisterPrompt = false;
        if (err.response && err.response.data && err.response.data.message) {
          this.error = err.response.data.message;
          // If backend says user not found, show quick register prompt
          if (typeof this.error === 'string' && this.error.includes('ไม่พบผู้ใช้งาน')) {
            this.showRegisterPrompt = true;
          }
        } else {
          this.error = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้';
        }
        console.error('Login failed:', err);
      }
    },
    loginAdmin() {
      this.adminError = null;
      const ADMIN_USER = 'ew';
      const ADMIN_PASS = 'eww';

      if (this.adminUsername === ADMIN_USER && this.adminPassword === ADMIN_PASS) {
        // Dispatch the Vuex action for admin login
        this.$store.dispatch('auth/loginAdmin');
        this.$router.push('/main-menu');
      } else {
        this.adminError = 'ชื่อผู้ใช้หรือรหัสผ่านแอดมินไม่ถูกต้อง';
        this.adminPassword = '';
      }
    }
  }
}
</script>

<style scoped>
.login-view-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.login-container {
  display: flex;
  width: 100%;
  max-width: 900px;
  min-height: 550px;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  overflow: hidden;
}

/* Left Panel Styling */
.welcome-panel {
  flex-basis: 50%;
  background: linear-gradient(to bottom, #e67e22, #f39c12);
  color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  text-align: center;
}

.welcome-image {
  max-width: 200px;
  margin-bottom: 1.5rem;
}

.welcome-title {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.welcome-subtitle {
  font-size: 1.1rem;
  opacity: 0.9;
}

/* Right Panel Styling */
.login-panel {
  flex-basis: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.login-box {
  width: 100%;
  max-width: 340px;
}

.login-toggle {
  display: flex;
  justify-content: center;
  margin-bottom: 1.5rem;
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
  color: #333;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  font-weight: bold;
}

.input-group {
  display: flex;
  align-items: center;
  background: #f9f9f9;
  border-radius: 8px;
  margin-bottom: 1rem;
  padding: 0 12px;
  border: 1.5px solid #ddd;
}

.input-group .icon {
  color: #999;
  margin-right: 8px;
}

.input-group input {
  background: transparent;
  border: none;
  outline: none;
  color: #444;
  padding: 12px 0;
  flex: 1;
  font-size: 1rem;
}

.login-btn {
  width: 100%;
  background: #e67e22;
  color: #fff;
  border: none;
  padding: 12px 0;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
  margin-top: 0.5rem;
}

.login-btn:hover {
  background: #d35400;
}

.admin-btn { background: #2980b9; }
.admin-btn:hover { background: #2c3e50; }

.login-options {
  margin-top: 1rem;
  font-size: 0.9rem;
}

.forgot { color: #e67e22; text-decoration: none; }
.forgot:hover { text-decoration: underline; }

.register-link {
  display: block;
  margin-top: 1.5rem;
  color: #555;
  text-decoration: none;
}

.register-link:hover strong {
  text-decoration: underline;
}

.error-message {
  background-color: #fdd;
  color: #c00;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 1rem;
  text-align: center;
  font-size: 0.9rem;
}

.register-prompt { text-align:center; margin-top:8px; color:#444 }
.register-prompt a { color:#e67e22 }

/* Responsive */
@media (max-width: 768px) {
  .login-container {
    flex-direction: column;
    min-height: auto;
  }
  .welcome-panel {
    display: none; /* Hide the welcome panel on smaller screens to save space */
  }
  .login-panel {
    padding: 2rem 1rem;
  }
}
</style>
