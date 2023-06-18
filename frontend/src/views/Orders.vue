<template>
  <div class="bg-img">
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <h3 class="text-white bg1 py-2 text-center">New ({{ newOrder.length }})</h3>
            <div class="order-container">
              <!-- ORDER START -->
              <div
                class="order border shadow bg-white mb-1 card mx-1"
                v-for="order in newOrder"
                :key="order.id"
              >
                <router-link :to="`order/detail/${order.order_id}`" class="row p-2">
                  <div class="col-md-5">
                    <div class="img-container p-2">
                      <img class="img-fluid" :src="order.image" alt="meal-image" />
                    </div>
                  </div>
                  <div class="col-md-7 py-2 px-3">
                    <h5 class="color1">{{ order.name }}</h5>
                    <p>
                      Quantity:<b> {{ order.order_quantity }}</b>
                    </p>
                    <p>
                      Payment: <b> {{ order.payment }}</b>
                    </p>
                  </div>
                </router-link>
              </div>
              <!-- ORDER END -->
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <h3 class="text-white bg1 py-2 text-center">Preparing({{ preparing.length }})</h3>
            <div class="order-container">
              <!-- ORDER START -->
              <div
                class="cart border shadow bg-white mb-1 card mx-1"
                v-for="order in preparing"
                :key="order.id"
              >
                <router-link :to="`order/detail/${order.order_id}`" class="row p-2">
                  <div class="col-md-5">
                    <div class="img-container p-2">
                      <img class="img-fluid" :src="order.image" alt="meal-image" />
                    </div>
                  </div>
                  <div class="col-md-7 py-2">
                    <h5 class="color1">{{ order.name }}</h5>
                    <p>
                      Quantity:<b> {{ order.order_quantity }}</b>
                    </p>
                    <p>
                      Payment: <b> {{ order.payment }}</b>
                    </p>
                  </div>
                </router-link>
              </div>
              <!-- ORDER END -->
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <h3 class="text-white bg1 py-2 text-center">Ready({{ ready.length }})</h3>
            <div class="order-container">
              <!-- ORDER START -->
              <div
                class="cart border shadow bg-white mb-1 card mx-1"
                v-for="order in ready"
                :key="order.id"
              >
                <router-link :to="`order/detail/${order.order_id}`" class="row p-2">
                  <div class="col-md-5">
                    <div class="img-container p-2">
                      <img class="img-fluid" :src="order.image" alt="meal-image" />
                    </div>
                  </div>
                  <div class="col-md-7 py-2">
                    <h5 class="color1">{{ order.name }}</h5>
                    <p>
                      Quantity:<b> {{ order.order_quantity }}</b>
                    </p>
                    <p>
                      Payment: <b> {{ order.payment }}</b>
                    </p>
                  </div>
                </router-link>
              </div>
              <!-- ORDER END -->
            </div>
          </div>
        </div>
      </div>
      <div class="row my-3">
        <div class="col-md-4">
          <div class="card">
            <h3 class="text-white bg1 py-2 text-center">To Pick Up({{ toPickUp.length }})</h3>
            <div class="order-container">
              <!-- ORDER START -->
              <div
                class="cart border shadow bg-white mb-1 card mx-1"
                v-for="order in toPickUp"
                :key="order.id"
              >
                <router-link :to="`order/detail/${order.order_id}`" class="row p-2">
                  <div class="col-md-5">
                    <div class="img-container p-2">
                      <img class="img-fluid" :src="order.image" alt="meal-image" />
                    </div>
                  </div>
                  <div class="col-md-7 py-2">
                    <h5 class="color1">{{ order.name }}</h5>
                    <p>
                      Quantity:<b> {{ order.order_quantity }}</b>
                    </p>
                    <p>
                      Payment: <b> {{ order.payment }}</b>
                    </p>
                  </div>
                </router-link>
              </div>
              <!-- ORDER END -->
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <h3 class="text-white bg1 py-2 text-center">For Delivery({{ forDelivery.length }})</h3>
            <div class="order-container">
              <!-- ORDER START -->
              <div
                class="cart border shadow bg-white mb-1 card mx-1"
                v-for="order in forDelivery"
                :key="order.id"
              >
                <router-link :to="`order/detail/${order.order_id}`" class="row p-2">
                  <div class="col-md-5">
                    <div class="img-container p-2">
                      <img class="img-fluid" :src="order.image" alt="meal-image" />
                    </div>
                  </div>
                  <div class="col-md-7 py-2">
                    <h5 class="color1">{{ order.name }}</h5>
                    <p>
                      Quantity:<b> {{ order.order_quantity }}</b>
                    </p>
                    <p>
                      Payment: <b> {{ order.payment }}</b>
                    </p>
                  </div>
                </router-link>
              </div>
              <!-- ORDER END -->
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <h3 class="text-white bg1 py-2 text-center">Done({{ done.length }})</h3>
            <div class="order-container">
              <!-- ORDER START -->
              <div
                class="cart border shadow bg-white mb-1 card mx-1"
                v-for="order in done"
                :key="order.id"
              >
                <router-link :to="`order/detail/${order.order_id}`" class="row p-2">
                  <div class="col-md-5">
                    <div class="img-container p-2">
                      <img class="img-fluid" :src="order.image" alt="meal-image" />
                    </div>
                  </div>
                  <div class="col-md-7 py-2">
                    <h5 class="color1">{{ order.name }}</h5>
                    <p>
                      Quantity:<b> {{ order.order_quantity }}</b>
                    </p>
                    <p>
                      Payment: <b> {{ order.payment }}</b>
                    </p>
                  </div>
                </router-link>
              </div>
              <!-- ORDER END -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useProductStore } from '../stores/product'
import { useRouter } from 'vue-router'
import Pagination from '../components/Pagination.vue'

const productStore = useProductStore()
// const route = useRoute()
const router = useRouter()

const isLoading = ref(false)

const showError = ref(false)

const activeTab = ref('All')

const orders = ref(computed(() => productStore.orders))
const id = ref('')

const newOrder = ref([])
const preparing = ref([])
const ready = ref([])
const toPickUp = ref([])
const forDelivery = ref([])
const done = ref([])

const filter = ref({
  type: '',
  min_price: 0,
  max_price: 0,
  search: ''
})

const getOrders = () => {
  productStore
    .getOrders({
      filter: {
        type: activeTab.value,
        search: ''
      }
    })
    .then((data) => {
      newOrder.value = orders.value.filter((data) => {
        return data.status === 'New'
      })

      preparing.value = orders.value.filter((data) => {
        return data.status === 'Preparing'
      })

      ready.value = orders.value.filter((data) => {
        return data.status === 'Ready'
      })

      toPickUp.value = orders.value.filter((data) => {
        return data.status === 'To Pick up'
      })

      forDelivery.value = orders.value.filter((data) => {
        return data.status === 'For Delivery'
      })

      done.value = orders.value.filter((data) => {
        return data.status === 'Done'
      })
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

onMounted(() => {
  getOrders()
})
</script>

<style scoped>
.active-tab {
  border-bottom: 5px solid var(--color1) !important;
  color: #333 !important;
}
.nav-container {
  overflow-x: scroll !important;
  display: flex;
  width: 95vw;
}
.nav-item a {
  color: #333 !important;
  width: 200px;
  text-align: center;
  font-weight: 600;
}
.bg-img {
  background-image: url('../assets/bg.svg');

  min-height: 100vh !important;
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
  height: 120px !important;
  border-radius: 5px 5px 0px 0px;
  width: 100%;
}
.cart {
  border-radius: 7px;
}
a {
  text-decoration: none;
  color: unset;
}
a :hover {
  text-decoration: none;
  color: none;
}
a * {
  text-decoration: none;
  color: none;
}

a * :hover {
  text-decoration: none;
  color: none;
}
.order-container {
  max-height: 400px;
  overflow-y: scroll;
}
/* Width and height of the scrollbar track */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

/* Background color of the scrollbar track */
::-webkit-scrollbar-track {
  background-color: #f1f1f1;
}

/* Color of the scrollbar thumb */
::-webkit-scrollbar-thumb {
  background-color: #888;
}

/* Hover style of the scrollbar thumb */
::-webkit-scrollbar-thumb:hover {
  background-color: #555;
}
</style>
