<template>
  <div class="admin-login-container">
    <h1>เข้าสู่ระบบแอดมิน</h1>
    <form @submit.prevent="login">
      <input v-model="username" placeholder="ชื่อผู้ใช้" required />
      <input v-model="password" type="password" placeholder="รหัสผ่าน" required />
      <button type="submit">เข้าสู่ระบบ</button>
    </form>
  </div>
</template>

<script>
export default {
  name: 'AdminLoginView',
  data() {
    return {
      username: '',
      password: ''
    }
  },
  methods: {
    login() {
      alert('เข้าสู่ระบบแอดมินสำเร็จ!');
    }
  }
}
</script>

<style scoped>
.admin-login-container {
  max-width: 400px;
  margin: 40px auto;
  padding: 32px 24px;
  background: #ffe0b2;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(230,126,34,0.10);
}
.admin-login-container h1 {
  color: #e67e22;
  margin-bottom: 18px;
}
.admin-login-container input {
  display: block;
  width: 100%;
  margin-bottom: 14px;
  padding: 10px;
  border-radius: 8px;
  border: 1.5px solid #e67e22;
}
.admin-login-container button {
  background: #e67e22;
  color: #fff;
  border: none;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
}
</style>
