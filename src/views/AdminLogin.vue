<template>
  <div class="admin-login-container">
    <h1>เข้าสู่ระบบแอดมิน</h1>
    <form @submit.prevent="login">
      <div v-if="error" class="error-message">{{ error }}</div>
      <input v-model="username" placeholder="ชื่อผู้ใช้" required />
      <input v-model="password" type="password" placeholder="รหัสผ่าน" required />
      <button type="submit">เข้าสู่ระบบ</button>
    </form>
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
  error.value = null; // Reset error message

  // --- ตัวอย่างการตรวจสอบข้อมูล (ในระบบจริงควรเรียก API) ---
  const ADMIN_USER = 'neww';
  const ADMIN_PASS = 'admin';

  if (username.value === ADMIN_USER && password.value === ADMIN_PASS) {
    // Login สำเร็จ
    localStorage.setItem('isAdminLoggedIn', 'true');
    alert('เข้าสู่ระบบแอดมินสำเร็จ!');
    
    // Redirect ไปยังหน้า Dashboard ของ Admin (ตัวอย่าง)
    // คุณต้องสร้าง Route และ Component สำหรับ '/admin/dashboard' ด้วย
    // router.push('/admin/dashboard'); 

    // หรือกลับไปหน้าหลัก
    router.push('/main-menu');

  } else {
    // Login ไม่สำเร็จ
    error.value = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
    password.value = ''; // Clear password field for security
  }
};
</script>

<style scoped>
.admin-login-container {
  max-width: 400px;
  margin: 40px auto;
  padding: 32px 24px;
  background: #f2f2f2; /* Changed background for differentiation */
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.10);
  border: 1px solid #ccc;
}
.admin-login-container h1 {
  color: #333;
  margin-bottom: 18px;
  text-align: center;
}
.admin-login-container input {
  display: block;
  width: 100%;
  box-sizing: border-box;
  margin-bottom: 14px;
  padding: 10px;
  border-radius: 8px;
  border: 1.5px solid #ccc;
}
.admin-login-container input:focus {
  border-color: #3498db;
  outline: none;
}
.admin-login-container button {
  width: 100%;
  background: #3498db;
  color: #fff;
  border: none;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}
.admin-login-container button:hover {
  background: #2980b9;
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
