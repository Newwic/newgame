<template>
  <div class="shop-wrapper">
    <div class="shop-container">
      <div class="hero-section">
        <h1>อาหารแสนอร่อยของเรา</h1>
        <p>ปรุงสดใหม่เพื่อคุณโดยเฉพาะ</p>
      </div>

          <!-- Tables Section -->
          <div class="tables-section">
            <div class="tables-header">
              <h2>Tables</h2>
              <div class="admin-toggle" v-if="isAdmin">
                <label class="switch">
                  <input type="checkbox" v-model="adminMode">
                  <span class="slider"></span>
                </label>
                <small>{{ adminMode ? 'Admin Mode' : 'Customer Mode' }}</small>
              </div>
            </div>

            <div class="tables-grid">
        <div v-for="table in tables" :key="table.id" class="table-card"
          :class="{ occupied: table.occupied, selected: selectedTable === table.id }"
          @click="onTableClick(table)">
                <div class="table-name">โต๊ะ {{ table.id }}</div>
                <div class="table-status">{{ table.occupied ? 'มีคนนั่ง' : 'ว่าง' }}</div>
              </div>
            </div>
          </div>
            <!-- Admin Orders (visible in Admin Mode) -->
            <div v-if="isAdmin && adminMode" class="admin-orders">
              <h3>Orders for Kitchen</h3>
              <div v-if="adminOrders.length === 0" class="no-orders">ไม่มีคำสั่งซื้อ</div>
              <ul v-else class="orders-list">
                <li v-for="o in adminOrders" :key="o.id" class="order-item">
                  <div><strong>Order #{{ o.id }}</strong> - โต๊ะ {{ o.table || 'ไม่ระบุ' }}</div>
                  <div>ยอดรวม: {{ formatCurrency(o.total) }} — วิธีจ่าย: {{ o.paymentMethod }}</div>
                  <div class="order-items">{{ o.items.map(i=>i.name+" x"+i.quantity).join(', ') }}</div>
                  <div class="order-actions">
                    <button @click="markOrderDone(o.id)" class="ready-btn">ทำเสร็จ / ส่งแล้ว</button>
                  </div>
                </li>
              </ul>
            </div>

      <div class="shop-layout">
        <!-- Products Section -->
        <div class="products-section">
          <h2>Products</h2>
          <div class="product-grid">
            <div v-for="product in products" :key="product.id" class="product-card">
              <img :src="product.image" :alt="product.name" class="product-image">
              <div class="product-info">
                <h3>{{ product.name }}</h3>
                <p class="price">{{ formatCurrency(product.price) }}</p>
                <button @click="addToCart(product)" class="add-to-cart-btn">
                  <i class="fas fa-cart-plus"></i> Add to Cart
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
          <h2>Your Cart</h2>
          <div class="cart-card">
            <div v-if="cart.length === 0" class="cart-empty">
              <i class="fas fa-shopping-cart empty-cart-icon"></i>
              <p>Your cart is empty.</p>
            </div>
            <div v-else>
              <ul class="cart-items">
                <li v-for="item in cart" :key="item.id" class="cart-item">
                  <img :src="item.image" :alt="item.name" class="cart-item-image">
                  <div class="cart-item-details">
                    <span class="cart-item-name">{{ item.name }}</span>
                    <span class="cart-item-price">{{ formatCurrency(item.price) }}</span>
                  </div>
                  <div class="cart-item-actions">
                    <input type="number" v-model.number="item.quantity" @change="updateQuantity(item)" min="1" class="quantity-input">
                    <button @click="removeFromCart(item.id)" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                  </div>
                </li>
              </ul>
              <div class="cart-total">
                <div>
                  <h4>โต๊ะที่เลือก: <span v-if="selectedTable">{{ selectedTable }}</span><span v-else>ยังไม่ได้เลือกโต๊ะ</span></h4>
                </div>
                <div>
                  <h3>Total:</h3>
                  <h3>{{ formatCurrency(cartTotal) }}</h3>
                </div>
              </div>
              <div style="margin-top:10px; display:flex; gap:8px; align-items:center;">
                <button class="checkout-btn" @click="openCheckout" :disabled="cart.length===0 || (!adminMode && !selectedTable)">Proceed to Checkout</button>
                <div v-if="!adminMode && !selectedTable" style="color:#c0392b; font-weight:600;">กรุณาเลือกโต๊ะก่อนชำระเงิน</div>
                <div v-if="orderSuccess" style="color:#1b7f6a; font-weight:700; margin-left:8px;">สั่งซื้อเรียบร้อยแล้ว</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- Checkout Modal -->
  <div class="shop-modal-wrapper">
    <div v-if="checkoutOpen" class="modal-backdrop" @click.self="closeCheckout">
      <div class="checkout-modal">
        <div class="checkout-header">
          <div class="checkout-title">Checkout - {{ formatCurrency(cartTotal) }}</div>
          <button class="close-x" @click="closeCheckout">✕</button>
        </div>

        <div v-if="!paymentMethod">
          <div class="small-muted">Choose payment method</div>
          <div class="payment-options">
            <button class="pay-btn" :class="{ active: paymentMethod==='scan' }" @click="chooseScan">Scan (QR)</button>
            <button class="pay-btn" :class="{ active: paymentMethod==='cash' }" @click="chooseCash">Cash</button>
          </div>
        </div>

        <div v-if="paymentMethod === 'scan'">
          <div class="small-muted">Show this QR to the scanner or simulate complete</div>
          <div class="qr-box">
            <div style="font-weight:700; margin-bottom:8px;">QR Payload</div>
            <div style="word-break:break-word; font-size:0.9rem">{{ qrCodeData }}</div>
          </div>
          <div class="modal-actions">
            <button class="confirm-btn" @click="simulateScanComplete" :disabled="paymentProcessing">Simulate Scan</button>
            <button class="secondary-btn" @click="closeCheckout">Cancel</button>
          </div>
        </div>

        <div v-if="paymentMethod === 'cash'">
          <div class="small-muted">Collect cash from customer and confirm</div>
          <div class="modal-actions">
            <button class="confirm-btn" @click="confirmCashReceived" :disabled="paymentProcessing">Confirm Cash Received</button>
            <button class="secondary-btn" @click="closeCheckout">Cancel</button>
          </div>
        </div>

        <div v-if="paymentSuccess" style="margin-top:12px; text-align:center; color:#1b7f6a; font-weight:700">Payment successful! ขอบคุณที่อุดหนุน</div>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import apiClient from '@/http.js';

export default {
  name: 'ShopView',
  data() {
    return {
      products: [
        { id: 1, name: 'ข้าวมันไก่มหัศจรรย์', price: 60, image: require('@/assets/now.png') },
        { id: 2, name: 'ข้าวมันไก่เรียลลิตี้', price: 65, image: require('@/assets/now1.png') },
        { id: 3, name: 'ข้าวมันไก่เดือดนรก', price: 55, image: require('@/assets/now2.png') },
        { id: 4, name: 'น้ำเปล่า', price: 10, image: require('@/assets/now3.png') },
      ],
      cart: [],
  orderSuccess: false,

  // Checkout modal state
  checkoutOpen: false,
  paymentMethod: null, // 'scan' or 'cash'
  paymentProcessing: false,
  paymentSuccess: false,
  qrCodeData: null,

  // Tables and admin
  tables: Array.from({ length: 10 }, (_, i) => ({ id: i + 1, occupied: false })),
  adminMode: false,
  selectedTable: null,
  adminOrders: [],
  nextOrderId: 1
    };
  },
  computed: {
    cartTotal() {
      return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }
    ,
    isAdmin() {
      try {
        const user = this.$store.getters['auth/currentUser'];
        if (!user) return false;
        return user.id === 'admin' || user.email === 'admin@newgame.com';
      } catch (e) {
        return false;
      }
    }
  },
  watch: {
    isAdmin(newVal) {
      if (newVal) {
        this.startOrderPolling();
        this.fetchOrders();
      } else {
        this.stopOrderPolling();
      }
    }
  },
  mounted() {
    // load table statuses from backend if available
    this.fetchTables();
    if (this.isAdmin) {
      this.startOrderPolling();
      this.fetchOrders();
    }
  },
  methods: {
    addToCart(product) {
      const cartItem = this.cart.find(item => item.id === product.id);
      if (cartItem) {
        cartItem.quantity++;
      } else {
        this.cart.push({ ...product, quantity: 1 });
      }
    },
    removeFromCart(productId) {
      this.cart = this.cart.filter(item => item.id !== productId);
    },
    updateQuantity(item) {
      if (item.quantity <= 0) {
        this.removeFromCart(item.id);
      }
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB' }).format(value);
    },

    startOrderPolling() {
      if (this._orderPollInterval) return;
      this._orderPollInterval = setInterval(() => {
        this.fetchOrders();
      }, 5000);
    },
    stopOrderPolling() {
      if (this._orderPollInterval) {
        clearInterval(this._orderPollInterval);
        this._orderPollInterval = null;
      }
    },
    fetchOrders() {
      apiClient.get('/get_orders.php?type=pending').then(res => {
        if (res && res.data && res.data.success && Array.isArray(res.data.orders)) {
          this.adminOrders = res.data.orders.map(o => ({
            id: parseInt(o.id),
            table: o.table_id ? o.table_id : null,
            items: Array.isArray(o.items) ? o.items : [],
            total: parseFloat(o.total),
            paymentMethod: o.payment_method || o.paymentMethod || 'unknown',
            createdAt: o.created_at || Date.now()
          }));
        }
      }).catch(e => {
        console.warn('fetchOrders error', e);
      });
    },

    // Checkout flows
    openCheckout() {
      if (this.cart.length === 0) return;
      // If not admin, require a selected table before checkout
      if (!this.adminMode && !this.selectedTable) {
        // visually hint: briefly flash or keep modal closed and show message (handled in template)
        return;
      }
      this.checkoutOpen = true;
      this.paymentMethod = null;
      this.paymentProcessing = false;
      this.paymentSuccess = false;
      this.qrCodeData = null;
    },
    closeCheckout() {
      this.checkoutOpen = false;
      this.paymentMethod = null;
      this.paymentProcessing = false;
      this.paymentSuccess = false;
    },
    chooseScan() {
  this.paymentMethod = 'scan';
  // include table number in QR payload when selected
  this.qrCodeData = `PAYMENT|amount=${this.cartTotal}|table=${this.selectedTable || 'NA'}|time=${Date.now()}`;
    },
    simulateScanComplete() {
      // Simulate scanning/processing delay
      this.paymentProcessing = true;
      setTimeout(() => {
        this.paymentProcessing = false;
  this.paymentSuccess = true;
  // push order to admin/orders list
  this.pushOrderToAdmin('scan');
  this.finishOrder();
      }, 1200);
    },
    chooseCash() {
      this.paymentMethod = 'cash';
    },
    confirmCashReceived() {
      // Simulate cashier confirmation
      this.paymentProcessing = true;
      setTimeout(() => {
        this.paymentProcessing = false;
        this.paymentSuccess = true;
        // push order to admin/orders list
        this.pushOrderToAdmin('cash');
        this.finishOrder();
      }, 800);
    },
    finishOrder() {
      // Clear cart and clear selected table for customer
      this.cart = [];
      // keep selected table as occupied; clear selection so customer can see no pending order
      if (!this.adminMode) {
        this.selectedTable = null;
      }
      // show small success indicator in cart
      this.orderSuccess = true;
      setTimeout(() => {
        this.orderSuccess = false;
      }, 1600);
      // auto-close modal after short delay
      setTimeout(() => {
        this.closeCheckout();
      }, 900);
    }

    ,
    onTableClick(table) {
      if (this.adminMode) {
        // Toggle occupied state for admin
        table.occupied = !table.occupied;
        if (table.occupied) {
          // if occupied and matches selectedTable, clear selection
          if (this.selectedTable === table.id) this.selectedTable = null;
        }
      } else {
        // Customer selects a table only if it's not occupied
        if (table.occupied) return;
        this.selectedTable = table.id;
      }
    },
    fetchTables() {
      apiClient.get('/get_tables.php').then(res => {
        if (res && res.data && res.data.success && Array.isArray(res.data.tables)) {
          // Merge server state into local tables array
          const serverTables = res.data.tables;
          // Ensure local tables length matches server
          this.tables = serverTables.map(t => ({ id: t.id, occupied: !!t.occupied }));
        }
      }).catch(e => {
        // ignore fetch errors (fallback to client-side tables)
        console.warn('Could not fetch tables:', e && e.message ? e.message : e);
      });
    },
    pushOrderToAdmin(method = 'scan') {
      const payload = {
        table: this.selectedTable,
        items: this.cart.map(i => ({ id: i.id, name: i.name, quantity: i.quantity, price: i.price })),
        total: this.cartTotal,
        paymentMethod: method
      };

      // Try to POST to backend orders.php
      apiClient.post('/orders.php', payload).then(res => {
        if (res && res.data && res.data.success) {
          const orderId = res.data.order_id;
          const order = {
            id: orderId,
            table: payload.table,
            items: payload.items,
            total: payload.total,
            paymentMethod: payload.paymentMethod,
            createdAt: Date.now(),
            done: false
          };
          this.adminOrders.unshift(order);
          // refresh tables from backend to reflect DB state
          this.fetchTables();
        } else {
          // fallback to local order when backend didn't return success
          this._pushLocalOrder(payload);
        }
      }).catch(err => {
        // network or server error -> fallback to local behavior
        console.error('orders.php POST error:', err);
        this._pushLocalOrder(payload);
      });
    },

    _pushLocalOrder(payload) {
      const order = {
        id: this.nextOrderId++,
        table: payload.table,
        items: payload.items,
        total: payload.total,
        paymentMethod: payload.paymentMethod,
        createdAt: Date.now(),
        done: false
      };
      this.adminOrders.unshift(order);
      if (order.table) {
        const t = this.tables.find(x => x.id === order.table);
        if (t) t.occupied = true;
      }
    },
    markOrderDone(orderId) {
      // Try to update server-side first
      apiClient.post('/mark_order_done.php', { order_id: orderId }).then(res => {
        if (res && res.data && res.data.success) {
          // refresh orders and tables from server
          this.fetchOrders();
          this.fetchTables();
        } else {
          // fallback to local behavior
          const idx = this.adminOrders.findIndex(o => o.id === orderId);
          if (idx !== -1) this.adminOrders.splice(idx, 1);
        }
      }).catch(e => {
        console.error('mark_order_done error', e);
        // fallback local
        const idx = this.adminOrders.findIndex(o => o.id === orderId);
        if (idx !== -1) {
          const o = this.adminOrders[idx];
          if (o.table) {
            const t = this.tables.find(x => x.id === o.table);
            if (t) t.occupied = false;
          }
          this.adminOrders.splice(idx, 1);
        }
      });
    }
  }
};
</script>

<style scoped>
.shop-wrapper {
  min-height: 100vh;
  background: #f4f7f6;
  padding: 2rem;
  font-family: 'Montserrat', sans-serif;
}

.shop-container {
  max-width: 1200px;
  margin: 0 auto;
}

.hero-section {
  text-align: center;
  padding: 2rem 0;
  margin-bottom: 2rem;
}

.hero-section h1 {
  font-size: 2.8rem;
  font-weight: 700;
  color: #333;
}

.hero-section p {
  font-size: 1.1rem;
  color: #777;
}

.shop-layout {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 2rem;
}

/* Products Section */
.products-section h2, .cart-section h2 {
  font-size: 1.8rem;
  font-weight: 600;
  color: #444;
  margin-bottom: 1.5rem;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 1.5rem;
}

.product-card {
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.product-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.product-info {
  padding: 1rem;
  text-align: center;
}

.product-info h3 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.price {
  font-size: 1.2rem;
  font-weight: 700;
  color: #e67e22;
  margin-bottom: 1rem;
}

.add-to-cart-btn {
  background: #e67e22;
  color: #fff;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s;
  font-weight: 600;
}

.add-to-cart-btn:hover {
  background: #d35400;
}

/* Cart Section */
.cart-card {
  background: #fff;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.cart-empty {
  text-align: center;
  padding: 3rem 0;
  color: #888;
}

.empty-cart-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.cart-items {
  list-style: none;
  padding: 0;
  margin: 0;
}

.cart-item {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #f0f0f0;
}

.cart-item:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.cart-item-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  margin-right: 1rem;
}

.cart-item-details {
  flex-grow: 1;
}

.cart-item-name {
  font-weight: 600;
  display: block;
}

.cart-item-price {
  color: #777;
}

.cart-item-actions {
  display: flex;
  align-items: center;
}

.quantity-input {
  width: 50px;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 0.3rem;
  margin-right: 0.5rem;
}

.delete-btn {
  background: none;
  border: none;
  color: #e74c3c;
  cursor: pointer;
  font-size: 1.1rem;
}

.cart-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 2px dashed #eee;
}

.cart-total h3 {
  font-size: 1.2rem;
  font-weight: 700;
}

.checkout-btn {
  width: 100%;
  background: #27ae60;
  color: #fff;
  border: none;
  padding: 1rem;
  border-radius: 10px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  margin-top: 1.5rem;
  transition: background 0.3s;
}

.checkout-btn:hover {
  background: #229954;
}

/* Tables section */
.tables-section { margin: 1.5rem 0 2rem; }
.tables-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem }
.tables-grid { display:grid; grid-template-columns: repeat(5, 1fr); gap:0.75rem }
.table-card { background:#fff; padding:0.75rem; border-radius:8px; text-align:center; border:1px solid #eee; cursor:pointer }
.table-card.occupied { background:#fdecea; border-color:#f5c6cb }
.table-card.selected { box-shadow:0 6px 20px rgba(39,174,96,0.12); border-color:#27ae60 }
.table-name { font-weight:700 }
.table-status { font-size:0.85rem; color:#666 }

.admin-toggle { display:flex; gap:0.5rem; align-items:center }
.switch { position:relative; display:inline-block; width:40px; height:22px }
.switch input { opacity:0; width:0; height:0 }
.slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#ccc; transition:0.2s; border-radius:22px }
.slider:before { position:absolute; content:''; height:16px; width:16px; left:3px; top:3px; background:white; border-radius:50%; transition:0.2s }
.switch input:checked + .slider { background:#27ae60 }
.switch input:checked + .slider:before { transform:translateX(18px) }

.admin-orders { margin-top:1rem; background:#fff; padding:1rem; border-radius:8px; border:1px solid #eee }
.orders-list { list-style:none; padding:0; margin:0 }
.order-item { padding:0.75rem; border-bottom:1px dashed #f0f0f0 }
.order-item:last-child { border-bottom:none }
.order-items { color:#666; font-size:0.95rem; margin-top:6px }
.ready-btn { margin-top:8px; background:#3498db; color:#fff; border:none; padding:6px 8px; border-radius:6px; cursor:pointer }
.no-orders { color:#777 }

@media (max-width: 992px) {
  .tables-grid { grid-template-columns: repeat(5, 1fr); }
}

@media (max-width: 992px) {
  .shop-layout {
    grid-template-columns: 1fr;
  }
  .cart-section {
    margin-top: 2rem;
  }
}

/* Checkout modal styles */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.checkout-modal {
  background: #fff;
  width: 420px;
  max-width: calc(100% - 32px);
  border-radius: 12px;
  padding: 1.25rem;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.checkout-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem }
.checkout-title { font-size:1.25rem; font-weight:700 }
.close-x { background:none; border:none; font-size:1.1rem; cursor:pointer }
.payment-options { display:flex; gap:0.5rem; margin:0.75rem 0 }
.pay-btn { flex:1; padding:0.6rem; border-radius:8px; border:1px solid #eee; cursor:pointer; font-weight:600 }
.pay-btn.active { border-color:#27ae60; background:#eaf6ee }
.qr-box { text-align:center; padding:1rem; border:1px dashed #eee; border-radius:8px; margin-top:0.75rem }
.small-muted { font-size:0.85rem; color:#666 }
.modal-actions { display:flex; gap:0.5rem; margin-top:1rem }
.confirm-btn { flex:1; background:#27ae60; color:#fff; border:none; padding:0.6rem; border-radius:8px; cursor:pointer }
.secondary-btn { flex:1; border:1px solid #ddd; background:#fff; padding:0.6rem; border-radius:8px; cursor:pointer }
</style>