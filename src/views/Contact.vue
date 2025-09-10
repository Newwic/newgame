<template>
  <div class="contact-wrapper">
    <div class="contact-container">
      <div class="hero-section">
        <h1>Get in Touch</h1>
        <p>We'd love to hear from you. Here's how you can reach us.</p>
      </div>

      <div class="contact-layout">
        <!-- Contact Info & Form -->
        <div class="contact-form-section">
          <h3>Send us a Message</h3>
          <form @submit.prevent="sendMessage">
            <div class="input-group">
              <input type="text" placeholder="Your Name" v-model="form.name">
            </div>
            <div class="input-group">
              <input type="email" placeholder="Your Email" v-model="form.email">
            </div>
            <div class="input-group">
              <textarea placeholder="Your Message" rows="5" v-model="form.message" required></textarea>
            </div>
            <button type="submit" class="submit-btn" :disabled="sending">{{ sending ? 'Sending...' : 'Send Message' }}</button>
            <div v-if="sent" style="color:#1b7f6a; font-weight:700; margin-top:8px">ขอบคุณสำหรับข้อความ! เราจะติดต่อกลับเร็วๆ นี้</div>
            <div v-if="error" style="color:#c0392b; font-weight:700; margin-top:8px">{{ error }}</div>
          </form>

          <div class="contact-details">
            <h3>Contact Information</h3>
            <ul>
              <li><i class="fas fa-phone-alt"></i> 02-123-4567</li>
              <li><i class="fab fa-line"></i> @kaomankai</li>
              <li><i class="fab fa-facebook-f"></i> facebook.com/kaomankai</li>
              <li><i class="fas fa-map-marker-alt"></i> 123 Food Street, Bangkok, Thailand</li>
            </ul>
          </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.551369123595!2d100.50176511532857!3d13.75199500100196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e2990f0c0b1d6f%3A0x40100189d30b9e0!2sBangkok!5e0!3m2!1sen!2sth!4v1620112900875!5m2!1sen!2sth"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
          ></iframe>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactView',
  data() {
    return {
      form: { name: '', email: '', message: '' },
      sending: false,
      sent: false,
      error: null
    };
  },
  methods: {
    async sendMessage() {
      this.error = null;
      if (!this.form.message || this.form.message.trim() === '') {
        this.error = 'โปรดกรอกข้อความ';
        return;
      }
      this.sending = true;
      try {
        const res = await this.$http.post('/contact.php', this.form);
        if (res && res.data && res.data.success) {
          this.sent = true;
          this.form = { name: '', email: '', message: '' };
        } else {
          this.error = (res && res.data && res.data.message) || 'เกิดข้อผิดพลาด';
        }
      } catch (e) {
        if (e && e.response && e.response.data && e.response.data.message) this.error = e.response.data.message;
        else if (e && e.message) this.error = e.message;
        else this.error = 'เกิดข้อผิดพลาดไม่ทราบสาเหตุ';
      } finally {
        this.sending = false;
      }
    }
  }
}
</script>

<style scoped>
.contact-wrapper {
  min-height: 100vh;
  background: #f4f7f6;
  padding: 2rem;
  font-family: 'Montserrat', sans-serif;
}

.contact-container {
  max-width: 1200px;
  margin: 0 auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.hero-section {
  text-align: center;
  padding: 3rem 2rem;
  background: linear-gradient(to right, #e67e22, #f39c12);
  color: #fff;
}

.hero-section h1 {
  font-size: 2.8rem;
  font-weight: 700;
}

.hero-section p {
  font-size: 1.1rem;
  opacity: 0.9;
}

.contact-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
}

/* Form Section */
.contact-form-section {
  padding: 3rem;
}

.contact-form-section h3 {
  font-size: 1.8rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 2rem;
}

.input-group {
  margin-bottom: 1.5rem;
}

.input-group input,
.input-group textarea {
  width: 100%;
  padding: 12px 15px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-size: 1rem;
  font-family: 'Montserrat', sans-serif;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.input-group input:focus,
.input-group textarea:focus {
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

/* Contact Details */
.contact-details {
  margin-top: 3rem;
}

.contact-details h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 1.5rem;
}

.contact-details ul {
  list-style: none;
  padding: 0;
}

.contact-details li {
  display: flex;
  align-items: center;
  font-size: 1rem;
  color: #555;
  margin-bottom: 1rem;
}

.contact-details i {
  font-size: 1.2rem;
  color: #e67e22;
  width: 30px;
  text-align: center;
  margin-right: 1rem;
}

/* Map Section */
.map-section {
  min-height: 400px;
}

@media (max-width: 992px) {
  .contact-layout {
    grid-template-columns: 1fr;
  }
  .map-section {
    order: -1; /* Move map to top on smaller screens */
  }
}
</style>