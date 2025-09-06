<template>
  <div class="shop-page">
    <h1>สินค้า</h1>
    <div class="product-list">
      <div v-for="product in products" :key="product.id" class="product-card">
        <img :src="product.image" :alt="product.name" class="product-image">
        <h3>{{ product.name }}</h3>
        <p class="price">{{ formatCurrency(product.price) }}</p>
        <button @click="addToCart(product)">เพิ่มลงตะกร้า</button>
      </div>
    </div>

    <hr class="divider">

    <h1>ตะกร้าสินค้า</h1>
    <div class="cart-card">
      <div v-if="cart.length === 0" class="cart-empty">
        <p>ตะกร้าของคุณว่างเปล่า</p>
      </div>
      <div v-else>
        <table class="cart-table">
          <thead>
            <tr>
              <th>สินค้า</th>
              <th>ราคา</th>
              <th>จำนวน (แก้ไขได้)</th>
              <th>รวม</th>
              <th>ลบ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in cart" :key="item.id">
              <td>{{ item.name }}</td>
              <td>{{ formatCurrency(item.price) }}</td>
              <td>
                <input type="number" v-model.number="item.quantity" @change="updateQuantity(item)" min="1" class="quantity-input">
              </td>
              <td>{{ formatCurrency(item.price * item.quantity) }}</td>
              <td>
                <button @click="removeFromCart(item.id)" class="delete-btn">🗑️</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="cart-total">
          <h3>ยอดรวมทั้งหมด: {{ formatCurrency(cartTotal) }}</h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ShopView',
  data() {
    return {
      // ข้อมูลสินค้าตัวอย่าง (ในอนาคตควรดึงมาจาก API)
      products: [
        { id: 1, name: 'ข้าวมันไก่มหัศจรรย์', price: 60, image: require('@/assets/now.png') },
        { id: 2, name: 'ข้าวมันไก่เรียลลิตี้', price: 65, image: require('@/assets/now1.png') },
        { id: 3, name: 'ข้าวมันไก่เดือดนรก', price: 55, image: require('@/assets/now2.png') },
        { id: 4, name: 'น้ำเปล่า', price: 10, image: require('@/assets/now3.png') },
      ],
      cart: [] // ตะกร้าสินค้า
    };
  },
  computed: {
    // คำนวณราคารวมของตะกร้า
    cartTotal() {
      return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }
  },
  methods: {
    // เพิ่มสินค้าลงตะกร้า
    addToCart(product) {
      const cartItem = this.cart.find(item => item.id === product.id);
      if (cartItem) {
        // ถ้ามีสินค้านี้ในตะกร้าแล้ว ให้เพิ่มจำนวน
        cartItem.quantity++;
      } else {
        // ถ้ายังไม่มี ให้เพิ่มสินค้าใหม่ลงตะกร้า
        this.cart.push({ ...product, quantity: 1 });
      }
    },
    // ลบสินค้าออกจากตะกร้า
    removeFromCart(productId) {
      this.cart = this.cart.filter(item => item.id !== productId);
    },
    // อัปเดตจำนวนสินค้า
    updateQuantity(item) {
      if (item.quantity <= 0) {
        // ถ้าจำนวนน้อยกว่าหรือเท่ากับ 0 ให้ลบออกจากตะกร้า
        this.removeFromCart(item.id);
      }
    },
    // จัดรูปแบบสกุลเงิน
    formatCurrency(value) {
      return new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB' }).format(value);
    }
  }
};
</script>

<style scoped>
.shop-page {
  max-width: 960px;
  margin: 2rem auto;
  padding: 1rem;
  font-family: sans-serif;
}

.divider {
  margin: 3rem 0;
}

/* Product List */
.product-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.5rem;
}

.product-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1rem;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.product-image {
  max-width: 100%;
  height: 120px;
  object-fit: cover;
  margin-bottom: 1rem;
  border-radius: 4px;
}

.product-card h3 {
  margin: 0.5rem 0;
}

.price {
  color: #28a745;
  font-weight: bold;
  margin-bottom: 1rem;
}

button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  background-color: #007bff;
  color: white;
}

button:hover {
  background-color: #0056b3;
}

/* Shopping Cart */
.cart-card {
  background: #f9f9f9;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.cart-empty {
  text-align: center;
  padding: 2rem;
  color: #666;
}

.cart-table {
  width: 100%;
  border-collapse: collapse;
}

.cart-table th, .cart-table td {
  border-bottom: 1px solid #ddd;
  padding: 1rem;
  text-align: left;
}

.quantity-input {
  width: 60px;
  padding: 0.25rem;
  text-align: center;
}

.delete-btn {
  background-color: #dc3545;
}
.delete-btn:hover {
  background-color: #c82333;
}

.cart-total {
  text-align: right;
  margin-top: 1.5rem;
  font-size: 1.2rem;
}
</style>
