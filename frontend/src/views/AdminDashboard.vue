<template>
  <div class="py-4 bg-img">
    <!-- banner section -->
    <div class="container mt-lg-5 banner mb-5 border">
      <div class="row px-3">
        <div class="col">
          <div class="row">
            <div class="col py-5">
              <h1 class="color1">
                Hello!
                <span class="hi"> {{ user.first_name }} {{ user.last_name }}.</span>
              </h1>
              <div class="">
                <p class="py-2">
                  Welcome to your Food Ordering Dashboard! Let's grow your food business together!
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 text-center banner-img">
          <img
            src="../assets/banner_img.png"
            alt="welcome back"
            class="hello-img img-fluid text-center"
          />
        </div>
      </div>
    </div>
    <!-- FOR ADMIN -->
    <div class="container mb-5" v-if="user?.type === 'admin' && isLoggedIn === true">
      <div class="row mt-5" v-if="user?.type === 'admin' && isLoggedIn === true">
        <div class="col-md-3">
          <div class="card">
            <div class="card-body bg-white shadow rounded">
              <h4 class="color1">Total Users</h4>
              <p class="user-count">{{ userCount }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body bg-white shadow rounded">
              <h4 class="color1">Total Merchant</h4>
              <p class="user-count">{{ storeCount }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body bg-white shadow rounded">
              <h4 class="color1">Total Rider</h4>
              <p class="user-count">{{ riderCount }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthenticationStore } from '../stores/authenticaton'
import { useProductStore } from '../stores/product'
import { useUserStore } from '../stores/user'
const userStore = useUserStore()

const store = useAuthenticationStore()
const user = ref(store.user)
const isLoggedIn = ref(computed(() => store.isLoggedIn))

const userCount = ref(0)
const storeCount = ref(0)
const riderCount = ref(0)

const filter1 = ref({
  type: 'user',
  limit: 0,
  // status: 0,
  search: ''
})

const getAllUsers = () => {
  userStore
    .getAllUsers(filter1.value)
    .then((data) => {
      userCount.value = data.length
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const filter2 = ref({
  type: 'store',
  limit: 0,
  // status: 0,
  search: ''
})

const getAllStores = () => {
  userStore
    .getAllUsers(filter2.value)
    .then((data) => {
      storeCount.value = data.length
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const filter3 = ref({
  type: 'rider',
  limit: 0,
  // status: 0,
  search: ''
})

const getRiders = () => {
  userStore
    .getAllUsers(filter3.value)
    .then((data) => {
      riderCount.value = data.length
    })
    .catch((err) => {
      console.log('Error', err)
    })
}
onMounted(() => {
  if (isLoggedIn.value === true) {
    getAllUsers()
    getAllStores()
    getRiders()
  }
})
</script>

<style scoped>
.bg-img {
  background-image: url('../assets/bg.svg');

  min-height: 100vh !important;
}

.hero {
  background-image: url('../assets/bg.svg');
  min-height: 100%;
}
.banner {
  background: #fff5dc;
  border-radius: 10px;
}
.banner-img {
  display: flex;
  justify-content: center;
  /* align-items: center; */
  padding: 20px;
}
.search-container {
  position: relative;
}
.search-btn {
  background: var(--color1);
  border-radius: 10px;
  color: #fff;
}

.search-btn:hover {
  background: var(--color3);
  border-radius: 10px;
  color: #fff;
}

.search-input {
  padding: 12px;
  border-radius: 10px 0px 0px 10px;
}
.img-stall-container {
  height: 300px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.img-stall-container img {
  height: 100%;
}
.view-more-product {
  font-size: 18px;
  border: 1px solid var(--color1);
  color: var(--color1);
}
.view-more-product:hover {
  font-size: 18px;
  color: #fff;
  background: var(--color1);
}
.reg-merchant-btn {
  font-size: 18px;
  border: 1px solid var(--color1);
  color: var(--color1);
}
.reg-merchant-btn:hover {
  font-size: 18px;
  color: #fff;
  background: var(--color1);
}
.meal-image {
  width: 100%;
  max-height: 220px;
  /*display: flex;
  justify-content: center;
  align-items: center; */
}

.img-container {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  max-height: 180px !important;
}
.img-container img {
  /* height: 100%; */
  height: 180px !important;
  border-radius: 5px 5px 0px 0px;
  width: 100%;
}
.price {
  background: var(--color1);
  color: #fff;
  font-size: 18px;
  font-weight: 600;
  padding: 0px 5px;
  border-radius: 5px;
}
.meal:hover {
  border: 1px solid var(--color1);
  cursor: pointer;
}
.add-user-btn {
  font-size: 18px;
  background: var(--color1);
  color: #fff;
  /* width: 150px; */
  padding: 8px;
}
.add-user-btn:hover {
  font-size: 18px;
  background: var(--color3);
  color: #fff;
}
.text-red {
  color: red;
}

.text-blue {
  color: blue;
}
</style>
