<template>
  <div class="bg-img">
    <div class="container-fluid">
      <div class="row d-flex justify-content-center mt-4">
        <div class="col-md-12 py-5 bg-white">
          <h1 class="text-center color1">My Purchase</h1>
          <ul class="nav-container bg-light pt-2 px-0">
            <li
              :class="activeTab == 'All' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'All'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" aria-current="page" href="#">All</a>
            </li>

            <li
              :class="activeTab == 'New' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'New'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">New</a>
            </li>

            <li
              :class="activeTab == 'Accepted' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'Accepted'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">Accepted</a>
            </li>
            <li
              :class="activeTab == 'Preparing' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'Preparing'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">Preparing</a>
            </li>
            <li
              :class="activeTab == 'Ready' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'Ready'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">Ready</a>
            </li>
            <li
              :class="activeTab == 'For Delivery' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'For Delivery'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">For Delivery</a>
            </li>

            <li
              :class="activeTab == 'To Pick up' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'To Pick up'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">To Pick up</a>
            </li>
            <li
              :class="activeTab == 'Paid' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'Paid'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">Paid</a>
            </li>
            <li
              :class="activeTab == 'Done' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'Done'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">Done</a>
            </li>
            <li
              :class="activeTab == 'Declined' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'Declined'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">Declined</a>
            </li>
            <li
              :class="activeTab == 'Canceled' ? 'nav-item active-tab' : 'nav-item'"
              @click="
                () => {
                  activeTab = 'Canceled'
                  getPurchases()
                }
              "
            >
              <a class="nav-link" href="#">Canceled</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row mx-2">
        <!-- ORDER ITEM START -->
        <div class="col-md-6" v-for="purchase in purchasesDataDisplay" :key="purchase.id">
          <div class="cart border shadow bg-white mb-1">
            <div class="row p-2">
              <div class="col-md-4">
                <div class="img-container p-2">
                  <img class="img-fluid" :src="purchase.image" alt="meal-image" />
                </div>
              </div>
              <div class="col-md-8 py-2 px-4">
                <div class="d-flex justify-content-end">
                  <small> {{ purchase.order_created }} </small>
                </div>
                <h3 class="color1">{{ purchase.name }}</h3>
                <p>
                  Quantity: <b> {{ purchase.order_quantity }}</b>
                </p>
                <p>Price: {{ purchase.price }}</p>
                <p>
                  Total Payment: <b>{{ purchase.payment }}</b>
                </p>
                <div class="d-flex justify-content-end">
                  <!-- <button
                    class="btn btn-outline-danger mx-2"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteCart"
                  >
                    Cancel
                  </button> -->
                  <router-link
                    :to="`/order/detail/${purchase.order_id}`"
                    class="btn btn1"
                    type="button"
                    >View Order</router-link
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ORDER ITEM END -->
        <!-- PAGINATION START -->
        <div class="container py-5">
          <div class="px-2 pb-2 table-pagination" style="margin-top: -40px; width: 100%">
            <div class="d-flex align-items-center pagination-label">
              Showing {{ pagination.firstVal }} to {{ pagination.secondVal }} of
              {{ pagination.total }}
            </div>

            <!-- Imported Pagination Component -->
            <Pagination
              :maxVisibleButtons="pagination.maxVisibleButtons"
              :totalPages="pagination.totalPages"
              :perPage="pagination.perPage"
              :currentPage="pagination.currentPage"
              @pagechanged="getPage"
              class="d-flex justify-content-end float-end"
              style="width: fit-content; margin-left: 0px"
              v-if="pagination.total >= 4"
            >
            </Pagination>
          </div>
        </div>
        <!-- PAGINATION END -->
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

const purchases = ref(computed(() => productStore.purchase))
const id = ref('')

const purchasesDataHolder = ref([])
const purchasesDataDisplay = ref([])

const pagination = ref({
  maxVisibleButtons: 10,
  totalPages: 1,
  perPage: 4,
  currentPage: 1,
  total: 0,
  showPagination: true,
  firstVal: 1,
  secondVal: 4
})

const getPage = (page) => {
  pagination.value.currentPage = page

  let val1 = 1
  let val2 = 4

  val2 = val2 * pagination.value.currentPage
  val1 = val2 - 3

  pagination.value.firstVal = val1
  pagination.value.secondVal = val2

  purchasesDataDisplay.value = purchasesDataHolder.value.slice(val1 - 1, val2)

  if (pagination.value.totalEvents < 4) {
    pagination.value.secondVal = pagination.value.total
  } else if (pagination.value.firstVal + 4 > pagination.value.total) {
    pagination.value.secondVal = pagination.value.total
  }
}

const setPagination = () => {
  purchasesDataHolder.value = purchases.value
  purchasesDataDisplay.value = purchasesDataHolder.value.slice(0, 4)

  pagination.value.total = purchasesDataHolder.value.length
  pagination.value.totalPages = parseInt(purchasesDataHolder.value.length / 4)

  if (purchasesDataHolder.value.length % 4 !== 0) {
    pagination.value.totalPages += 1
  }
  getPage(1)
}

const filter = ref({
  type: '',
  min_price: 0,
  max_price: 0,
  search: ''
})

const getPurchases = () => {
  productStore
    .getPurchase({
      filter: {
        type: activeTab.value,
        search: ''
      }
    })
    .then((data) => {
 
      setPagination()
    })
    .catch((err) => {
     
      console.log('Error', err)
    })
}

onMounted(() => {
  getPurchases()
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
  display: flex;
  justify-content: center;
  align-items: center;
  height: 150px;
}
.img-container img {
  height: 100%;
}
.cart {
  border-radius: 7px;
}
</style>
