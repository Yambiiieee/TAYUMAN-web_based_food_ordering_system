<template>
  <!-- BOTTOM NAV -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container-fluid px-3">
      <router-link class="navbar-brand" to="/">
        <img class="img-logo" src="../assets/images/icon.png" alt="logo" /><span class="brand"
          >Tayuman</span
        ></router-link
      >
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item" v-if="isLoggedIn && user?.type == 'admin'">
            <router-link
              to="/admin-dashboard"
              :class="`${route.name == 'AdminDashboard' ? 'nav-link active' : 'nav-link'}`"
            >
              <i class="bi bi-card-checklist"></i>
              Dashboard
            </router-link>
          </li>
          <li class="nav-item" v-if="isLoggedIn && user?.type == 'store'">
            <router-link
              to="/store-dashboard"
              :class="`${route.name == 'StoreDashboard' ? 'nav-link active' : 'nav-link'}`"
            >
              <i class="bi bi-card-checklist"></i>
              Dashboard
            </router-link>
          </li>
          <li class="nav-item" v-if="isLoggedIn && user?.type == 'user'">
            <router-link
              to="/user-dashboard"
              :class="`${route.name == 'UserDashboard' ? 'nav-link active' : 'nav-link'}`"
            >
              <i class="bi bi-card-checklist"></i>
              Dashboard
            </router-link>
          </li>
          <li class="nav-item" v-if="isLoggedIn && user?.type == 'rider'">
            <router-link
              to="/rider-dashboard"
              :class="`${route.name == 'RiderDashboard' ? 'nav-link active' : 'nav-link'}`"
            >
              <i class="bi bi-card-checklist"></i>
              Dashboard
            </router-link>
          </li>
          <li class="nav-item" v-if="!isLoggedIn">
            <!-- " v-if="user?.type !== 'admin' && user?.type !== 'store'" -->
            <router-link to="/" :class="`${route.name == 'Home' ? 'nav-link active' : 'nav-link'}`">
              <i class="bi bi-house"></i>
              Home
            </router-link>
          </li>
          <li class="nav-item" v-if="isLoggedIn && user?.type == 'store'">
            <router-link
              :to="`/store/${user.id}`"
              :class="`${route.name == 'Store' ? 'nav-link active' : 'nav-link'}`"
            >
              <i class="bi bi-box-seam"></i>
              My Products
            </router-link>
          </li>
          <li
            class="nav-item"
            v-if="user?.type != 'admin' && user?.type != 'store' && user?.type == 'rider'"
          >
            <router-link
              to="/products"
              :class="`${route.name == 'Products' ? 'nav-link active' : 'nav-link'}`"
            >
              <i class="bi bi-box-seam"></i>
              Products
            </router-link>
          </li>

          <li class="nav-item dropdown" v-if="isLoggedIn && user?.type == 'admin'">
            <a
              href="#"
              :class="`${
                route.name == 'Stores' || route.name == 'Users' || route.name == 'Admins'
                  ? ' nav-link dropdown-toggle active dropdown-item'
                  : ' nav-link dropdown-toggle dropdown-item'
              }`"
              data-bs-toggle="dropdown"
            >
              <i class="bi bi-people-fill"></i>Accounts</a
            >
            <div class="dropdown-menu">
              <router-link
                to="/admin"
                :class="`${route.name == 'Admins' ? 'nav-link active' : 'nav-link'}`"
              >
                <i class="bi bi-person-bounding-box"></i>
                Admin
              </router-link>
              <router-link
                to="/users"
                :class="`${route.name == 'Users' ? 'nav-link active' : 'nav-link'}`"
              >
                <i class="bi bi-people-fill"></i>
                Users
              </router-link>
              <router-link
                to="/stores"
                :class="`${
                  route.name == 'Stores'
                    ? 'nav-link active dropdown-item'
                    : 'nav-link dropdown-item'
                }`"
              >
                <i class="bi bi-shop"></i>
                Stores
              </router-link>
              <router-link
                to="/riders"
                :class="`${
                  route.name == 'Riders'
                    ? 'nav-link active dropdown-item'
                    : 'nav-link dropdown-item'
                }`"
              >
                <i class="bi bi-truck"></i>
                Riders
              </router-link>
            </div>
          </li>

          <li
            class="nav-item dropdown"
            v-if="isLoggedIn && (user?.type == 'user' || user?.type == 'store')"
          >
            <a
              href="#"
              :class="`${
                route.name == 'Orders' || route.name == 'Carts' || route.name == 'Purchase'
                  ? ' nav-link dropdown-toggle active dropdown-item'
                  : ' nav-link dropdown-toggle dropdown-item'
              }`"
              data-bs-toggle="dropdown"
            >
              <i class="bi bi-cart-check-fill"></i>Order</a
            >
            <div class="dropdown-menu">
              <router-link
                v-if="isLoggedIn && user?.type == 'user'"
                to="/cart"
                :class="`${
                  route.name == 'Carts' ? 'nav-link active dropdown-item' : 'nav-link dropdown-item'
                }`"
              >
                <i class="bi bi-cart"></i>Cart
              </router-link>
              <router-link
                v-if="isLoggedIn && user?.type == 'store'"
                to="/orders"
                :class="`${route.name == 'Orders' ? 'nav-link active' : 'nav-link'}`"
              >
                <i class="bi bi-cart-check-fill"></i>Orders
              </router-link>
              <router-link
                to="/purchase"
                v-if="isLoggedIn && user?.type == 'user'"
                :class="`${route.name == 'Purchase' ? 'nav-link active' : 'nav-link'}`"
              >
                <i class="bi bi-cart-check-fill"></i> Purchase
              </router-link>
              <router-link
                to="/refund"
                :class="`${route.name == 'Refunds' ? 'nav-link active' : 'nav-link'}`"
              >
                <i class="bi bi-cash"></i> Refund
              </router-link>
            </div>
          </li>

          <li class="nav-item" v-if="isLoggedIn && user?.type == 'store'">
            <router-link
              to="/sales/list"
              :class="`${route.name == 'SalesList' ? 'nav-link active  ' : 'nav-link  '}`"
            >
              <i class="bi bi-cash"></i>Sales
            </router-link>
          </li>
        </ul>

        <router-link class="btn btn-brand ms-lg-3" to="/login" v-if="!isLoggedIn"
          >Login/Register</router-link
        >
        <button class="btn btn-brand ms-lg-3 mt-2" v-if="isLoggedIn" @click="submitLogout">
          Logout
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthenticationStore } from '../stores/authenticaton'
import { useRoute, useRouter } from 'vue-router'

const store = useAuthenticationStore()
const route = useRoute()
const router = useRouter()

const user = ref(store.user)

const isLoggedIn = ref(store.isLoggedIn)

const submitLogout = () => {
  /**Add validation here */
  store
    .logout()
    .then((data) => {
      router.go()
      // router.push("/auth/login")
    })
    .catch((err) => {
      console.log('Error', err)
    })
}
</script>

<style scoped>
.navbar {
  box-shadow: var(--shadow);
}

.navbar .navbar-nav .nav-link {
  color: var(--dark);
  padding: 1px 15px;
  font-size: 22px;
  display: flex;
  align-items: baseline;
}

.navbar .navbar-nav .nav-link:hover {
  color: var(--brand);
}

.navbar .navbar-nav .nav-link.active {
  /* color: var(--brand); */
  border-bottom: 4px solid var(--color1);
}

.navbar-brand {
  font-size: 28px;
  font-weight: 700;
}

.navbar-brand .dot {
  color: var(--brand);
}

.btn {
  padding: 8px 26px;
}

.brand {
  color: var(--color1);
}
.btn-brand {
  border-color: var(--color1);
  background-color: #fff;
  color: var(--color1);
}
.btn-brand:hover {
  background-color: var(--color3);
  border-color: var(--color3);
  color: #fff;
}

.img-logo {
  width: 50px;
  padding: 2px;
}
</style>
