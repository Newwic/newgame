<template>
  <div class="home">
    <h1 class="home-title">ข้าวมันไก่</h1>
    <p class="home-desc">ยิ่งดูยิ่งหิวยิ่งอยากนี่แหละคือข้าวมันไก่แห่งสวรรค์</p>
    <img src="@/assets/now.png" alt="Vue logo" class="home-img" />
    <div class="home-links">
      <router-link :to="'/chicken-rice/1'" class="home-link">ข้าวมันไก่สุดแสนจะอร่อย</router-link>
      <router-link :to="'/chicken-rice/2'" class="home-link">ข้าวมันไก่แห่งสวรรค์</router-link>
      <router-link :to="'/chicken-rice/3'" class="home-link">ข้าวมันไก่แห่งนรก</router-link>
    </div>
    <div class="home-highlight">
      <p>ข้าวมันไก่สูตรเด็ด น้ำจิ้มรสแซ่บ ไก่นุ่มข้าวหอม!</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeView'
}
</script>

<style scoped>
.home {
  text-align: center;
  margin-top: 40px;
  padding: 32px 0 0 0;
  background: linear-gradient(120deg, #fffbe6 0%, #f0f9ff 100%);
  min-height: 80vh;
}
.home-title {
  font-size: 2.8rem;
  color: #e67e22;
  font-weight: bold;
  margin-bottom: 10px;
  letter-spacing: 2px;
  text-shadow: 1px 2px 8px #fff3e0;
}
.home-desc {
  color: #555;
  font-size: 1.2rem;
  margin-bottom: 18px;
}
.home-img {
  margin: 18px 0 24px 0;
  border-radius: 16px;
  box-shadow: 0 4px 18px rgba(230,126,34,0.10);
  width: 300px;
  height: 300px;
  object-fit: cover;
}
.home-links {
  margin: 24px 0 0 0;
  display: flex;
  justify-content: center;
  gap: 18px;
}
.home-link {
  background: #e67e22;
  color: #fff;
  padding: 10px 18px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px rgba(230,126,34,0.10);
}
.home-link:hover {
  background: #f39c12;
  box-shadow: 0 4px 16px rgba(230,126,34,0.18);
}
.home-highlight {
  margin-top: 32px;
  background: #fff3e0;
  border-radius: 10px;
  display: inline-block;
  padding: 16px 32px;
  color: #e67e22;
  font-size: 1.1rem;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(230,126,34,0.08);
}
</style>

