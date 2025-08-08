<template>
  <div class="login-bg">
    <div class="login-box">
      <h2 class="login-title">เข้าสู่ระบบร้านข้าวมันไก่</h2>
      <form @submit.prevent="login">
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
        <router-link to="/admin-login">
          <button type="button" class="admin-btn">เข้าสู่ระบบผู้ดูแล</button>
        </router-link>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login() {
      localStorage.setItem('isLoggedIn', 'true');
      alert('เข้าสู่ระบบสมาชิกสำเร็จ!');
      this.$router.push('/main-menu');
    }
  }
}
</script>

<style scoped>
.login-bg {
  min-height: 40ch;
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
.admin-btn {
  width: 100%;
  background: linear-gradient(90deg, #b71c1c 60%, #e67e22 100%);
  color: #fff;
  border: none;
  padding: 12px 0;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1.08rem;
  cursor: pointer;
  transition: background 0.2s;
  margin-top: 12px;
  letter-spacing: 1px;
  box-shadow: 0 2px 8px rgba(230,126,34,0.10);
}
.admin-btn:hover {
  background: #b71c1c;
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
</style>
