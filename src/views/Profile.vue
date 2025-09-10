<template>
  <div class="profile-wrapper">
    <div class="profile-container">
      <div class="profile-header">
        <div class="avatar-container" @click="triggerAvatarUpload">
          <img :src="avatarPreview || (currentUser.avatar_url && `${currentUser.avatar_url}?${cacheBuster}`) || `https://i.pravatar.cc/150?u=${currentUser.email}`" alt="User Avatar" class="avatar" />
          <div class="avatar-upload-overlay">Change</div>
        </div>
        <input type="file" ref="avatarInput" @change="handleAvatarChange" accept="image/*" style="display: none" />
        <h2>{{ currentUser.name }}</h2>
        <p>{{ currentUser.email }}</p>
      </div>

      <div class="profile-form-container">
        <h3>Edit Profile</h3>
        <form @submit.prevent="handleProfileUpdate">
          <div class="input-group">
            <label for="name">Name</label>
            <input id="name" v-model.trim="form.name" type="text" />
            <p v-if="errors.name" class="error-text">{{ errors.name }}</p>
          </div>

          <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
          <p v-if="errors.general" class="error-text">{{ errors.general }}</p>

          <button type="submit" class="submit-btn" :disabled="isSubmitting">
            {{ isSubmitting ? 'Saving...' : (form.avatar ? 'Save Picture & Changes' : 'Save Changes') }}
          </button>
        </form>
      </div>
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
        name: '',
        avatar: null
      },
      avatarPreview: null,
      errors: {},
      successMessage: '',
      isSubmitting: false,
      cacheBuster: Date.now()
    };
  },
  computed: {
    ...mapGetters('auth', ['currentUser'])
  },
  methods: {
    ...mapActions('auth', ['updateProfile']),
    triggerAvatarUpload() {
      this.$refs.avatarInput.click();
    },
    handleAvatarChange(event) {
      const file = event.target.files[0];
      if (!file) return;

      this.form.avatar = file;

      // Create a preview
      const reader = new FileReader();
      reader.onload = (e) => {
        this.avatarPreview = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    async handleProfileUpdate() {
      this.errors = {};
      this.successMessage = '';
      this.isSubmitting = true;

      const formData = new FormData();
      formData.append('name', this.form.name);
  // include email so server can identify the user
  formData.append('email', this.currentUser && this.currentUser.email ? this.currentUser.email : '');
      if (this.form.avatar) {
        formData.append('avatar', this.form.avatar);
      }

      try {
        const response = await this.updateProfile(formData);
        this.successMessage = response.data.message || 'Profile updated successfully!';
        this.form.avatar = null; // Reset avatar after successful upload
        this.avatarPreview = null; // Clear preview
        // Force the image to reload by updating the cache-buster
        this.cacheBuster = Date.now();
      } catch (err) {
        console.error('Profile update failed:', err);
        if (err.response && err.response.data && err.response.data.message) {
          this.errors.general = err.response.data.message;
        } else if (err.request) {
          this.errors.general = 'Could not connect to the server. Please check your connection and try again.';
        } else {
          this.errors.general = 'An unexpected error occurred while preparing the request.';
        }
      } finally {
        this.isSubmitting = false;
      }
    }
  },
  created() {
    if (this.currentUser) {
      this.form.name = this.currentUser.name;
    }
  },
  watch: {
    currentUser(newUser) {
      if (newUser) {
        this.form.name = newUser.name;
      }
    }
  }
}
</script>

<style scoped>
.profile-wrapper {
  min-height: 100vh;
  background: #f4f7f6;
  padding: 2rem;
  font-family: 'Montserrat', sans-serif;
  display: flex;
  align-items: center;
  justify-content: center;
}

.profile-container {
  width: 100%;
  max-width: 500px;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.profile-header {
  background: linear-gradient(to right, #e67e22, #f39c12);
  color: #fff;
  padding: 2.5rem;
  text-align: center;
}

.avatar-container {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto 1rem;
  cursor: pointer;
}

.avatar {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 4px solid #fff;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  object-fit: cover;
}

.avatar-upload-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  opacity: 0;
  transition: opacity 0.3s;
  font-weight: 600;
}

.avatar-container:hover .avatar-upload-overlay {
  opacity: 1;
}

.profile-header h2 {
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0.5rem 0;
}

.profile-header p {
  font-size: 1rem;
  opacity: 0.9;
}

.profile-form-container {
  padding: 2.5rem;
}

.profile-form-container h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 1.5rem;
}

.input-group {
  margin-bottom: 1.5rem;
}

.input-group label {
  display: block;
  font-weight: 600;
  color: #555;
  margin-bottom: 0.5rem;
}

.input-group input {
  width: 100%;
  padding: 12px 15px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-size: 1rem;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.input-group input:focus {
  outline: none;
  border-color: #e67e22;
  box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.2);
}

.submit-btn {
  width: 100%;
  background: #e67e22;
  color: #fff;
  border: none;
  padding: 14px 0;
  border-radius: 8px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s;
}

.submit-btn:hover {
  background: #d35400;
}

.submit-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.error-text {
  color: #e74c3c;
  font-size: 0.9rem;
  margin-top: 0.5rem;
}

.success-message {
  color: #27ae60;
  background-color: rgba(39, 174, 96, 0.1);
  border: 1px solid #27ae60;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 1.5rem;
  text-align: center;
}
</style>