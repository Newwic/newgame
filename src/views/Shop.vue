<template>
  <div class="shop-container">
    <h1 class="shop-title">ร้านข้าวมันไก่ออนไลน์</h1>
    <div class="shop-list">
      <div class="shop-item" v-for="item in items" :key="item.id">
        <img :src="item.img" :alt="item.name" class="shop-img" />
        <h2>{{ item.name }}</h2>
        <p class="shop-desc">{{ item.desc }}</p>
        <p class="shop-price">ราคา {{ item.price }} บาท</p>
        <button class="shop-btn">สั่งซื้อ</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ShopView',
  data() {
    return {
      items: [
        {
          id: 1,
          name: 'ข้าวมันไก่สุดแสนจะอร่อย',
          desc: 'ข้าวหอม ไก่นุ่ม น้ำจิ้มสูตรเด็ด เสิร์ฟพร้อมน้ำซุป',
          price: 50,
          img: require('../assets/now1.png')
        },
        {
          id: 2,
          name: 'ข้าวมันไก่แห่งสวรรค์',
          desc: 'ไก่ทอดกรอบ ข้าวมันหอม น้ำจิ้มรสแซ่บ',
          price: 55,
          img: require('../assets/now.png')
        },
        {
          id: 3,
          name: 'ข้าวมันไก่แห่งนรก',
          desc: 'ไก่ต้ม+ไก่ทอด ครบจบในจานเดียว',
          price: 60,
          img: require('../assets/now2.png')
        }
      ]
    }
  }
}
</script>

<style scoped>
.shop-container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 32px 24px 40px 24px;
  background: linear-gradient(135deg, #fffbe6 0%, #f0f9ff 100%);
  border-radius: 22px;
  box-shadow: 0 6px 32px rgba(44,62,80,0.10);
}
.shop-title {
  text-align: center;
  color: #e67e22;
  font-size: 2.2rem;
  font-weight: bold;
  margin-bottom: 32px;
  letter-spacing: 2px;
}
.shop-list {
  display: flex;
  gap: 32px;
  flex-wrap: wrap;
  justify-content: center;
}
.shop-item {
  background: #fff3e0;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(230, 126, 34, 0.10);
  padding: 28px 24px 20px 24px;
  min-width: 260px;
  max-width: 280px;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform 0.2s, box-shadow 0.2s;
}
.shop-item:hover {
  transform: translateY(-6px) scale(1.03);
  box-shadow: 0 8px 24px rgba(230, 126, 34, 0.18);
}
.shop-img {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 50%;
  margin-bottom: 16px;
  background: #fff;
  box-shadow: 0 2px 8px rgba(230,126,34,0.08);
}
.shop-desc {
  color: #555;
  font-size: 1rem;
  margin: 8px 0 8px 0;
}
.shop-price {
  color: #e67e22;
  font-size: 1.1rem;
  font-weight: bold;
  margin-bottom: 12px;
}
.shop-btn {
  background: #e67e22;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 24px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}
.shop-btn:hover {
  background: #f39c12;
}
</style>
