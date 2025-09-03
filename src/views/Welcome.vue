<template>
  <div class="welcome-container">
    <h1>ยินดีต้อนรับ!</h1>
    <p>กรุณาสมัครสมาชิกเพื่อเข้าชมข้อมูลร้านข้าวมันไก่</p>
    <router-link to="/register">
      <button class="register-btn">สมัครสมาชิก</button>
    </router-link>
    <p class="login-link">มีบัญชีอยู่แล้ว? <router-link to="/login">เข้าสู่ระบบ</router-link></p>
  </div>
</template>

<script>
export default {
  name: 'WelcomeView'
}
</script>

<style scoped>
.welcome-container {
  max-width: 400px;
  margin: 60px auto;
  padding: 36px 24px;
  background: #f0f9ff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(44,62,80,0.10);
  text-align: center;
}
.welcome-container h1 {
  color: #e67e22;
  margin-bottom: 18px;
}
.register-btn {
  background: #e67e22;
  color: #fff;
  border: none;
  padding: 12px 32px;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1.1rem;
  cursor: pointer;
  margin-top: 18px;
}
.login-link {
  margin-top: 18px;
  color: #2980b9;
}
.login-link a {
  color: #e67e22;
  font-weight: bold;
  text-decoration: underline;
}
</style>
