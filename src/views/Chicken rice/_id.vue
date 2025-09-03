<template>
  <div class="student-detail chicken-detail">
    <div v-if="$route.params.id === '1'" class="chicken-card">
      <h2>ข้าวมันไก่สุดแสนจะอร่อย</h2>
      <ul>
        <img src="@/assets/now1.png" alt="Vue logo" class="home-img" />
        <li>ข้าวหอมมะลิคุณภาพดี</li>
        <li>ไก่เนื้อนุ่ม สดใหม่ทุกวัน</li>
        <li>น้ำจิ้มสูตรเด็ดเฉพาะร้าน</li>
        <li>เสิร์ฟพร้อมน้ำซุปกลมกล่อม</li>
      </ul>
    </div>
    <div v-else-if="$route.params.id === '2'" class="chicken-card">
      <h2>ข้าวมันไก่แห่งสวรรค์</h2>
      <ul>
        <img src="@/assets/now1.png" alt="Vue logo" class="home-img" />
        <li>ข้าวมันหอมกรุ่น</li>
        <li>ไก่เนื้อแน่นเต็มคำ</li>
        <li>น้ำจิ้มรสกลมกล่อม</li>
        <li>บริการด้วยใจ</li>
      </ul>
    </div>
    <div v-else-if="$route.params.id === '3'" class="chicken-card">
      <h2>ข้าวมันไก่แห่งนรก</h2>
      <ul>
        <img src="@/assets/now2.png" alt="Vue logo" class="home-img" />
        <li>ข้าวมันไก่รสเผ็ดไฟลุก</li>
        <li>ไก่หมักพริกสูตรพิเศษ</li>
        <li>น้ำจิ้มสุดแซ่บ</li>
        <li>ท้าทายความเผ็ด!</li>
      </ul>
    </div>
    <div v-else class="chicken-card">
      <p>ข้าวมันไก่สุดแสนจะอร่อย</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ChickenRiceDetailView'
}
</script>

<style scoped>
.chicken-detail {
  text-align: center;
  margin-top: 40px;
  padding: 32px 0 0 0;
  background: linear-gradient(120deg, #fffbe6 0%, #f0f9ff 100%);
  min-height: 80vh;
}
.chicken-title {
  font-size: 2.2rem;
  color: #e67e22;
  font-weight: bold;
  margin-bottom: 18px;
  letter-spacing: 1px;
}
.chicken-card {
  background: #fff3e0;
  border-radius: 14px;
  box-shadow: 0 2px 8px rgba(230,126,34,0.08);
  display: inline-block;
  padding: 28px 36px;
  margin-bottom: 18px;
  margin-top: 24px;
}
.chicken-card h2 {
  color: #e67e22;
  margin-bottom: 10px;
  font-size: 1.4rem;
  font-weight: bold;
}
.chicken-card ul {
  list-style: none;
  padding: 0;
  margin: 16px 0;
}
.chicken-card li {
  margin-bottom: 6px;
  color: #444;
  font-size: 1.08rem;
}
.home-img {
  width: 300px;
  height: 300px;
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 8px;
}
</style>

