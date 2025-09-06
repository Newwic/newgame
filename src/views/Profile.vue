<template>
  <div class="profile-view">
    <div class="profile-box">
      <h2>แก้ไขข้อมูลส่วนตัว</h2>
      <form @submit.prevent="handleProfileUpdate">
        <div class="field">
          <label class="label">ชื่อ</label>
          <input v-model.trim="form.name" type="text" class="input" />
          <p v-if="errors.name" class="error">{{ errors.name }}</p>
        </div>
        <div class="field">
          <label class="label">อีเมล</label>
          <input :value="currentUser.email" type="email" class="input" disabled />
        </div>

        <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
        <p v-if="errors.general" class="error">{{ errors.general }}</p>

        <button type="submit" class="submit-btn">บันทึกการเปลี่ยนแปลง</button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'ProfileView',
  data() {
    return {
      form: {
        name: ''
      },
      errors: {},
      successMessage: ''
    };
  },
  computed: {
    ...mapGetters('auth', ['currentUser'])
  },
  methods: {
    ...mapActions('auth', ['updateProfile']), // Directly map the action
    
    async handleProfileUpdate() {
      this.errors = {};
      this.successMessage = '';

      try {
        // Call the mapped action directly
        const response = await this.updateProfile(this.form);
        this.successMessage = response.data.message || 'อัปเดตโปรไฟล์สำเร็จ!';
      } catch (err) {
        if (err.response && err.response.data && err.response.data.message) {
          this.errors.general = err.response.data.message;
        } else {
          this.errors.general = 'เกิดข้อผิดพลาดในการอัปเดตโปรไฟล์';
        }
        console.error('Profile update failed:', err);
      }
    }
  },
  created() {
    // Pre-fill the form with the current user's name
    if (this.currentUser) {
      this.form.name = this.currentUser.name;
    }
  },
  watch: {
    // Watch for changes in currentUser and update the form
    currentUser(newUser) {
      if (newUser) {
        this.form.name = newUser.name;
      }
    }
  }
}
</script>

<style scoped>
.profile-view {
  display: flex;
  justify-content: center;
  padding-top: 40px;
}
.profile-box {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.08);
  padding: 28px;
  width: 450px;
  max-width: 100%;
}
h2 { margin-bottom: 20px; }
.field { margin-bottom: 15px; text-align: left; }
.label { display: block; font-weight: 600; margin-bottom: 6px; }
.input { width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #ddd; }
.input:disabled { background: #f5f5f5; color: #777; }
.submit-btn {
  background: #3498db;
  color: white;
  padding: 12px 15px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  font-size: 1rem;
}
.error { color: #e74c3c; font-size: 0.875rem; margin-top: 6px; }
.success-message { color: #27ae60; margin-bottom: 15px; }
</style>
