<template>
  <div class="news-wrapper">
    <div class="news-container">
      <div class="hero-section">
        <h1>รีวิว ข้าวมันไก่</h1>
        <p>ให้คะแนนเเรกเพื่อช่วยปรับปรุงรสชาติของเรา</p>
      </div>

      <div class="review-area">
        <div class="product-card">
          <img :src="productImage" alt="ข้าวมันไก่" class="news-image">
          <div class="news-content">
            <h3>ข้าวมันไก่ - ให้คะแนนจาก 1 ถึง 5</h3>
            <div class="rating-row">
              <button v-for="n in 5" :key="n" :class="['star', { active: rating>=n }]" @click="rating = n">{{ n }} ★</button>
            </div>
            <input v-model="name" placeholder="ชื่อ (ไม่บังคับ)" class="input-name" />
            <textarea v-model="comment" placeholder="ความเห็น (ไม่บังคับ)" class="input-comment"></textarea>
            <div class="actions">
              <button class="submit-btn" @click="submitReview" :disabled="submitting || rating<1">ส่งรีวิว</button>
              <div v-if="submitted" class="success">ขอบคุณสำหรับรีวิว!</div>
              <div v-if="errorMessage" class="error" style="color:#c0392b; font-weight:600; margin-left:8px">{{ errorMessage }}</div>
            </div>
          </div>
        </div>

        <div class="stats-card">
          <h4>คะแนนเฉลี่ย: <strong>{{ avgScore }}</strong> / 5</h4>
          <div>จาก {{ reviewCount }} รีวิว</div>
          <hr />
          <h5>รีวิวล่าสุด</h5>
          <ul class="review-list">
            <li v-for="r in reviews" :key="r.id">
              <div class="r-head"><strong>{{ r.name || 'ลูกค้า' }}</strong> — {{ r.rating }} ★ <span class="r-time">{{ r.created_at }}</span></div>
              <div class="r-comment">{{ r.comment }}</div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from '@/http.js';

export default {
  name: 'ReviewView',
  data() {
    return {
      productImage: require('@/assets/now.png'),
      rating: 0,
      name: '',
      comment: '',
      submitting: false,
      submitted: false,
  errorMessage: null,
      avgScore: 0,
      reviewCount: 0,
      reviews: []
    };
  },
  mounted() {
    this.loadReviews();
  },
  methods: {
    loadReviews() {
      const product = encodeURIComponent('ข้าวมันไก่');
      apiClient.get(`/get_reviews.php?product=${product}`).then(res => {
        if (res && res.data && res.data.success) {
          this.avgScore = res.data.avg || 0;
          this.reviewCount = res.data.count || 0;
          this.reviews = res.data.reviews || [];
        }
      }).catch(e => {
        console.warn('loadReviews error', e);
      });
    },
    submitReview() {
      if (this.rating < 1) return;
      this.submitting = true;
      this.errorMessage = null;
      const payload = { product: 'ข้าวมันไก่', name: this.name || null, rating: this.rating, comment: this.comment || null };
      apiClient.post('/reviews.php', payload).then(res => {
        if (res && res.data) {
          if (res.data.success) {
            this.submitted = true;
            this.name = '';
            this.comment = '';
            this.rating = 0;
            this.loadReviews();
          } else {
            // server returned success:false with message
            this.errorMessage = res.data.message || 'Server rejected review';
          }
        } else {
          this.errorMessage = 'No response from server';
        }
      }).catch(e => {
        // try to show server response if available
        if (e && e.response && e.response.data && e.response.data.message) {
          this.errorMessage = e.response.data.message;
        } else if (e && e.message) {
          this.errorMessage = e.message;
        } else {
          this.errorMessage = 'Unknown error submitting review';
        }
        console.error('submitReview error', e);
      }).finally(() => { this.submitting = false; });
    }
  }
}
</script>

<style scoped>
.news-wrapper { min-height:100vh; background:#f4f7f6; padding:2rem; font-family:'Montserrat',sans-serif }
.news-container { max-width:1000px; margin:0 auto }
.hero-section { text-align:center; padding:2rem 0; margin-bottom:1.5rem }
.review-area { display:grid; grid-template-columns: 1fr 360px; gap:1.25rem }
.product-card { background:#fff; border-radius:12px; padding:1rem; box-shadow:0 6px 20px rgba(0,0,0,0.06); display:flex; gap:1rem }
.news-image { width:180px; height:120px; object-fit:cover; border-radius:8px }
.news-content h3 { margin:0 0 8px 0 }
.rating-row { display:flex; gap:8px; margin-bottom:10px }
.star { padding:8px 12px; background:#eee; border-radius:8px; cursor:pointer }
.star.active { background:#f1c40f; color:#222; font-weight:700 }
.input-name, .input-comment { width:100%; padding:8px; margin-bottom:8px; border:1px solid #ddd; border-radius:6px }
.input-comment { min-height:80px }
.actions { display:flex; align-items:center; gap:10px }
.submit-btn { background:#27ae60; color:#fff; padding:8px 12px; border:none; border-radius:8px; cursor:pointer }
.success { color:#1b7f6a; font-weight:700 }
.stats-card { background:#fff; border-radius:12px; padding:1rem; box-shadow:0 6px 20px rgba(0,0,0,0.06) }
.review-list { list-style:none; padding:0; margin:0 }
.review-list li { padding:8px 0; border-bottom:1px dashed #eee }
.r-head { font-size:0.95rem; color:#333 }
.r-comment { font-size:0.9rem; color:#555; margin-top:6px }
.r-time { color:#999; font-size:0.8rem; margin-left:8px }

@media (max-width:900px) {
  .review-area { grid-template-columns: 1fr }
}
</style>