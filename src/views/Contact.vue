<template>
  <div class="contact">
    <h1 class="contact-title">ติดต่อร้านข้าวมันไก่</h1>
    <div class="contact-info">
      <p><span>โทร:</span> 02-123-4567</p>
      <p><span>Line:</span> @kaomankai</p>
      <p><span>Facebook:</span> facebook.com/kaomankai</p>
    </div>
    <p class="contact-footer">เรายินดีให้บริการข้าวมันไก่ทุกวัน!</p>
  </div>
</template>

<script>
export default {
  name: 'ContactView'
}
</script>

<style scoped>
.contact {
  text-align: center;
  margin-top: 40px;
  padding: 32px 0 0 0;
  background: linear-gradient(120deg, #fffbe6 0%, #f0f9ff 100%);
  min-height: 80vh;
}
.contact-title {
  font-size: 2.2rem;
  color: #e67e22;
  font-weight: bold;
  margin-bottom: 18px;
  letter-spacing: 1px;
}
.contact-info {
  background: #fff3e0;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(230,126,34,0.08);
  display: inline-block;
  padding: 24px 36px;
  margin-bottom: 18px;
}
.contact-info p {
  margin: 0 0 10px 0;
  color: #444;
  font-size: 1.08rem;
}
.contact-info span {
  color: #e67e22;
  font-weight: bold;
}
.contact-footer {
  color: #2980b9;
  font-size: 1.1rem;
  font-weight: bold;
}
</style>

