<template>
  <div class="min-h-full test">
    <div class="main-layout">
      <div class="header">
        <Header></Header>

        <router-view :key="$route.path"></router-view>

        <div class="footer">
          <Footer></Footer>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Header from './Header.vue'
import Footer from './Footer.vue'
</script>

<style scoped>
.main-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
.header {
  flex-grow: 1;
}
.footer {
  position: sticky;
  top: 100%;
}
</style>
