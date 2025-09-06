<template>
  <div class="register-bg">
    <div class="register-box">
      <h2 class="register-title">ลงทะเบียนผู้ใช้งาน</h2>

      <form @submit.prevent="onSubmit" novalidate>
        <div class="field">
          <label class="label">ชื่อ <span class="required">*</span></label>
          <input v-model.trim="form.name" type="text" class="input" placeholder="ชื่อ-นามสกุล" />
          <p v-if="errors.name" class="error">{{ errors.name }}</p>
        </div>

        <div class="field">
          <label class="label">อีเมล <span class="required">*</span></label>
          <input v-model.trim="form.email" type="email" class="input" placeholder="example@mail.com" />
          <p v-if="errors.email" class="error">{{ errors.email }}</p>
        </div>

        <div class="field password-field">
          <label class="label">รหัสผ่าน <span class="required">*</span></label>
          <div class="password-row">
            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" class="input" placeholder="อย่างน้อย 6 ตัว" />
            <button type="button" class="toggle-btn" @click="showPassword = !showPassword">{{ showPassword ? 'ซ่อน' : 'แสดง' }}</button>
          </div>
          <p v-if="errors.password" class="error">{{ errors.password }}</p>
        </div>

        <div class="field">
          <label class="label">ยืนยันรหัสผ่าน <span class="required">*</span></label>
          <input :type="showPassword ? 'text' : 'password'" v-model="form.confirmPassword" class="input" placeholder="ยืนยันรหัสผ่าน" />
          <p v-if="errors.confirmPassword" class="error">{{ errors.confirmPassword }}</p>
        </div>

        <div class="field">
          <label class="label">เพศ <span class="required">*</span></label>
          <div class="radio-row">
            <label><input type="radio" value="ชาย" v-model="form.gender" /> ชาย</label>
            <label><input type="radio" value="หญิง" v-model="form.gender" /> หญิง</label>
            <label><input type="radio" value="อื่น ๆ" v-model="form.gender" /> อื่น ๆ</label>
          </div>
          <p v-if="errors.gender" class="error">{{ errors.gender }}</p>
        </div>

        <div class="field">
          <label class="label">ความสนใจ (เลือกได้หลายค่า)</label>
          <div class="checkbox-grid">
            <label v-for="opt in interestOptions" :key="opt" class="checkbox-item">
              <input type="checkbox" :value="opt" v-model="form.interests" /> {{ opt }}
            </label>
          </div>
        </div>

        <div class="actions">
          <button type="submit" class="register-btn">ลงทะเบียน</button>
          <button type="button" class="reset-btn" @click="onReset">รีเซ็ต</button>
        </div>
      </form>

      <p v-if="errors.general" class="error">{{ errors.general }}</p>

      <div v-if="success" class="success-box">
        ลงทะเบียนสำเร็จ
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from '../http.js';

export default {
  name: 'RegisterView',
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
        gender: null,
        interests: []
      },
      interestOptions: ['กีฬา', 'ดนตรี', 'อ่านหนังสือ', 'ท่องเที่ยว', 'เทคโนโลยี'],
      errors: {},
      success: false,
      showPassword: false
    }
  },
  methods: {
    validate() {
      this.errors = {}

      if (!this.form.name) this.errors.name = 'กรุณากรอกชื่อ'

      if (!this.form.email) {
        this.errors.email = 'กรุณากรอกอีเมล'
      } else {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if (!re.test(this.form.email)) this.errors.email = 'รูปแบบอีเมลไม่ถูกต้อง'
      }

      if (!this.form.password) {
        this.errors.password = 'กรุณากรอกรหัสผ่าน'
      } else if (this.form.password.length < 6) {
        this.errors.password = 'รหัสผ่านต้องอย่างน้อย 6 ตัว'
      }

      if (!this.form.confirmPassword) {
        this.errors.confirmPassword = 'กรุณายืนยันรหัสผ่าน'
      } else if (this.form.confirmPassword !== this.form.password) {
        this.errors.confirmPassword = 'รหัสผ่านไม่ตรงกัน'
      }

      if (!this.form.gender) this.errors.gender = 'กรุณาเลือกเพศ'

      // return true if no errors
      return Object.keys(this.errors).length === 0
    },
    async onSubmit() {
      this.success = false;
      if (this.validate()) {
        try {
          const payload = { ...this.form };
          delete payload.confirmPassword;

          // The backend endpoint should be '/register.php' or similar
          await apiClient.post('/register.php', payload);

          this.success = true;
          this.onReset(); // Clear form on success

          // Redirect to login page after a short delay
          setTimeout(() => {
            this.$router.push('/login');
          }, 2000);

        } catch (err) {
          if (err.response && err.response.data) {
            const responseData = err.response.data;
            if (responseData.errors) {
              // Handle validation errors (e.g., { name: 'Name is required' })
              this.errors = responseData.errors;
            } else if (responseData.message) {
              // Handle general errors (e.g., 'This email is already registered.')
              // Display it as a general error or specifically for the email field
              this.errors.general = responseData.message;
            } else {
              this.errors.general = 'เกิดข้อผิดพลาดที่ไม่รู้จัก';
            }
          } else if (err.request) {
            // The request was made but no response was received
            this.errors.general = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้';
          } else {
            // Something happened in setting up the request that triggered an Error
            this.errors.general = 'เกิดข้อผิดพลาดบางอย่าง';
          }
          console.error('Registration failed:', err);
        }
      } else {
        this.$nextTick(() => {
          const firstError = this.$el.querySelector('.error');
          if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth' });
          }
        });
      }
    },
    onReset() {
      this.form = {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
        gender: null,
        interests: []
      }
      this.errors = {}
      this.success = false
      this.showPassword = false
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
  padding: 32px;
}
.register-box {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.08);
  padding: 28px;
  width: 420px;
  max-width: 100%;
  border: 1px solid #eee;
}
.register-title {
  color: #333;
  font-size: 1.25rem;
  margin-bottom: 18px;
  text-align: center;
}
.field { margin-bottom: 12px }
.label { display:block; font-weight:600; margin-bottom:6px }
.required { color: #e74c3c }
.input { width:100%; padding:10px 12px; border-radius:6px; border:1px solid #ddd }
.password-row { display:flex; gap:8px }
.toggle-btn { background:#f0f0f0; border:1px solid #ddd; padding:6px 8px; border-radius:6px; cursor:pointer }
.radio-row { display:flex; gap:12px; align-items:center }
.checkbox-grid { display:flex; flex-wrap:wrap; gap:8px }
.checkbox-item { padding:6px 8px; background:#fafafa; border:1px solid #eee; border-radius:6px }
.actions { display:flex; gap:8px; margin-top:10px }
.register-btn { flex:1; background:linear-gradient(90deg,#e67e22,#f39c12); color:#fff; padding:10px 12px; border-radius:8px; border:none; cursor:pointer }
.reset-btn { padding:10px 12px; border-radius:8px; border:1px solid #ddd; background:#fff; cursor:pointer }
.error { color:#e74c3c; font-size:0.875rem; margin-top:6px }
.success-box { margin-top:14px; padding:10px; background:#e8f8f5; color:#1b7f6a; border-radius:8px; text-align:center; font-weight:600 }

@media (max-width:480px){
  .register-box{ width:100%; padding:18px }
}
</style>