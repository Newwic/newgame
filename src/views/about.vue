<template>
  <div class="about-container">
    <div class="about-ribbon">Best Seller!</div>
    <section class="about-hero">
      <h1>ขายข้าวมันไก่</h1>
      <p>เราได้คิดค้นข้าวมันไก่อัจฉริยะที่จะสามารถส่งไปถึงหน้าบ้านคุณได้</p>
    </section>
    <div class="about-input">
      <input v-model="message" placeholder="พิมพ์ข้อความที่นี่" />
      <p class="about-input-preview">คุณพิมพ์: <span>{{ message }}</span></p>
    </div>
    <section class="about-content">
      <div class="about-card">
        <h2>ข้าวมันไก่มหัศจรรย์</h2>
        <p>พัฒนามาจากเทคโนโลยีบนสวรรค์และสร้างประสบการณ์ที่ดีให้กับผู้ใช้</p>
      </div>
      <div class="about-card">
        <h2>ข้าวมันไก่นรกมหัศจรรย์</h2>
        <p>ประกอบด้วยนักพัฒนามืออาชีพที่มีความเชี่ยวชาญและประสบการณ์สูง</p>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'AboutView',
  data() {
    return {
      message: ''
    }
  }
}
</script>

<style scoped>
.about-container {
  max-width: 400px;
  margin: 40px auto;
  padding: 32px 24px 40px 24px;
  background: linear-gradient(135deg, #fffbe6 0%, #f0f9ff 100%);
  border-radius: 22px;
  box-shadow: 0 6px 32px rgba(44,62,80,0.10);
  position: relative;
}
.about-ribbon {
  position: absolute;
  top: 18px;
  left: -38px;
  background: linear-gradient(90deg, #e67e22 60%, #f39c12 100%);
  color: #fff;
  font-weight: bold;
  font-size: 1.1rem;
  padding: 8px 38px;
  transform: rotate(-28deg);
  box-shadow: 0 2px 8px rgba(230,126,34,0.18);
  border-radius: 8px;
  letter-spacing: 1px;
  z-index: 2;
}

.about-hero {
  text-align: center;
  margin-bottom: 40px;
}

.about-hero h1 {
  font-size: 2.8rem;
  color: #e67e22;
  margin-bottom: 12px;
  letter-spacing: 2px;
  font-weight: bold;
  text-shadow: 1px 2px 8px #fff3e0;
}

.about-hero p {
  font-size: 1.2rem;
  color: #555;
  margin-bottom: 0;
}

.about-input {
  margin: 0 auto 36px auto;
  max-width: 400px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(230, 126, 34, 0.08);
  padding: 24px 20px 18px 20px;
  display: flex;
  flex-direction: column;
  align-items: stretch;
}
.about-input input {
  padding: 10px 14px;
  border: 1.5px solid #e67e22;
  border-radius: 8px;
  font-size: 1.1rem;
  outline: none;
  transition: border 0.2s;
  margin-bottom: 10px;
}
.about-input input:focus {
  border: 2px solid #f39c12;
}
.about-input-preview {
  color: #888;
  font-size: 1rem;
  margin: 0;
}
.about-input-preview span {
  color: #e67e22;
  font-weight: bold;
}

.about-content {
  display: flex;
  gap: 32px;
  flex-wrap: wrap;
  justify-content: center;
  margin-top: 24px;
}

.about-card {
  background: linear-gradient(120deg, #fff 60%, #ffe0b2 100%);
  border-radius: 16px;
  padding: 32px 28px;
  flex: 1 1 320px;
  min-width: 280px;
  box-shadow: 0 2px 12px rgba(230, 126, 34, 0.10);
  transition: transform 0.2s, box-shadow 0.2s;
}
.about-card:hover {
  transform: translateY(-6px) scale(1.03);
  box-shadow: 0 8px 24px rgba(230, 126, 34, 0.18);
}
.about-card h2 {
  color: #e67e22;
  margin-bottom: 10px;
  font-size: 1.4rem;
  font-weight: bold;
}
.about-card p {
  color: #444;
  font-size: 1rem;
}
</style>
