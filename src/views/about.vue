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
.about-wrapper {
  min-height: 100vh;
  background: #f4f7f6;
  padding: 2rem;
  font-family: 'Montserrat', sans-serif;
}

.about-container {
  max-width: 800px;
  margin: 0 auto;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  padding: 3rem;
  position: relative;
  overflow: hidden;
}

.about-ribbon {
  position: absolute;
  top: 25px;
  left: -30px;
  background: #e67e22;
  color: #fff;
  font-weight: 700;
  font-size: 1rem;
  padding: 8px 40px;
  transform: rotate(-45deg);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  z-index: 2;
}

.about-hero {
  text-align: center;
  margin-bottom: 3rem;
}

.about-hero h1 {
  font-size: 2.8rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 0.5rem;
}

.about-hero p {
  font-size: 1.1rem;
  color: #777;
}

.about-input {
  margin: 0 auto 2.5rem auto;
  max-width: 500px;
  background: #f9f9f9;
  border-radius: 12px;
  padding: 1.5rem;
  border: 1px solid #eee;
}

.about-input input {
  width: 100%;
  padding: 12px 15px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-size: 1rem;
  margin-bottom: 1rem;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.about-input input:focus {
  outline: none;
  border-color: #e67e22;
  box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.2);
}

.about-input-preview {
  color: #888;
  font-size: 0.9rem;
}

.about-input-preview span {
  color: #e67e22;
  font-weight: 600;
}

.about-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

.about-card {
  background: #f9f9f9;
  border-radius: 15px;
  padding: 2rem;
  border: 1px solid #eee;
  transition: transform 0.3s, box-shadow 0.3s;
}

.about-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
}

.about-card h2 {
  color: #e67e22;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.about-card p {
  color: #666;
  font-size: 1rem;
  line-height: 1.6;
}

@media (max-width: 768px) {
  .about-content {
    grid-template-columns: 1fr;
  }
}
</style>
