<template>
  <div class="register-bg">
    <div class="register-box">
      <h2 class="register-title">สมัครสมาชิก</h2>
      <form @submit.prevent="register">
        <div class="input-group">
          <span class="icon"><i class="fa fa-user"></i></span>
          <input v-model="name" placeholder="ชื่อ" required class="register-input" />
        </div>
        <div class="input-group">
          <span class="icon"><i class="fa fa-envelope"></i></span>
          <input v-model="email" type="email" placeholder="Email ID" required class="register-input" />
        </div>
        <div class="input-group">
          <span class="icon"><i class="fa fa-lock"></i></span>
          <input v-model="password" type="password" placeholder="Password" required class="register-input" />
        </div>
        <button type="submit" class="register-btn">สมัครสมาชิก</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterView',
  data() {
    return {
      name: '',
      email: '',
      password: ''
    }
  },
  methods: {
    register() {
      localStorage.setItem('isLoggedIn', 'true');
      alert('สมัครสมาชิกสำเร็จ!');
      this.$router.push('/main-menu');
    }
  }
}
</script>

<style scoped>
.register-bg {
  min-height: 100vh;
  background: linear-gradient(135deg, #fffbe6 0%, #f0f9ff 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}
.register-box {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 4px 32px rgba(230,126,34,0.18);
  padding: 38px 32px 28px 32px;
  width: 340px;
  text-align: center;
  border: 2.5px solid #e67e22;
}
.register-title {
  color: #e67e22;
  font-size: 1.5rem;
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
.register-input {
  background: transparent;
  border: none;
  outline: none;
  color: #444;
  padding: 12px 0;
  flex: 1;
  font-size: 1.08rem;
}
.register-btn {
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
.register-btn:hover {
  background: #e67e22;
  color: #fff;
}
</style>
