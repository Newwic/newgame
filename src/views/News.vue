<template>
  <div class="news">
    <h1 class="news-title">ข่าวข้าวมันไก่</h1>
    <p class="news-promo">โปรโมชั่นพิเศษ! ซื้อข้าวมันไก่วันนี้แถมฟรีน้ำซุป</p>
    <ul class="news-list">
      <li>ข้าวมันไก่เปิดสาขาใหม่ที่สยาม</li>
      <li>แจกสูตรน้ำจิ้มข้าวมันไก่สุดลับ</li>
      <li>กิจกรรมชิงรางวัลข้าวมันไก่ฟรี 1 เดือน</li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'NewsView'
}
</script>

<style scoped>
.news {
  text-align: center;
  margin-top: 40px;
  padding: 32px 0 0 0;
  background: linear-gradient(120deg, #fffbe6 0%, #f0f9ff 100%);
  min-height: 80vh;
}
.news-title {
  font-size: 2.2rem;
  color: #e67e22;
  font-weight: bold;
  margin-bottom: 10px;
  letter-spacing: 1px;
}
.news-promo {
  color: #2980b9;
  font-size: 1.1rem;
  margin-bottom: 18px;
  font-weight: bold;
}
.news-list {
  margin: 24px auto 0 auto;
  padding: 0;
  list-style: none;
  max-width: 400px;
  background: #fff3e0;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(230,126,34,0.08);
}
.news-list li {
  padding: 16px 0;
  border-bottom: 1px solid #ffe0b2;
  color: #444;
  font-size: 1.08rem;
}
.news-list li:last-child {
  border-bottom: none;
}
</style>
