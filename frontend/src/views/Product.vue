<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 pt-5 p-3">
        <div class="">
          <h3 class="color1">Browse Products</h3>
        </div>
      </div>
      <div class="col-md-5 3 pt-5 p-3">
        <div class="input-group search-container">
          <input
            type="search"
            class="form-control search-input"
            placeholder="Search"
            aria-label="Search"
            aria-describedby="search-addon"
            v-model="filter.search"
            @change="getAllProducts"
          />
          <button type="button" class="btn search-btn"><i class="bi bi-search"></i>Search</button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md p-3">
        <div class="bg-light card px-3 py-3">
          <div class="px-3">
            <p><b>Category</b></p>
            <!-- CATEGORY START -->
            <div class="form-check">
              <input
                class="form-check-input"
                type="radio"
                name="product_category"
                v-model="filter.type"
                value="All"
                id="all"
                @change="getAllProducts"
              />
              <label class="form-check-label" for="all">All Product </label>
            </div>
            <!-- CATEGORY END -->
            <!-- CATEGORY START -->
            <div class="form-check">
              <input
                class="form-check-input"
                type="radio"
                name="product_category"
                v-model="filter.type"
                value="Snacks"
                id="snacks"
                @change="getAllProducts"
              />
              <label class="form-check-label" for="food">Snacks </label>
            </div>
            <!-- CATEGORY END -->
            <!-- CATEGORY START -->
            <div class="form-check">
              <input
                class="form-check-input"
                type="radio"
                name="product_category"
                v-model="filter.type"
                value="Drinks"
                @change="getAllProducts"
                id="drinks"
              />
              <label class="form-check-label" for="drink">Drinks </label>
            </div>
            <!-- CATEGORY END -->

            <!-- CATEGORY START -->
            <div class="form-check">
              <input
                class="form-check-input"
                type="radio"
                name="product_category"
                v-model="filter.type"
                value="Meal"
                id="meal"
                @change="getAllProducts"
              />
              <label class="form-check-label" for="all">Meal </label>
            </div>
            <!-- CATEGORY END -->
          </div>
          <div class="px-3 mt-4">
            <div class="row">
              <div class="col-md-12">
                <p><b>Price</b></p>
              </div>
              <div class="col-md-6 px-2">
                <div class="form-group mb-2">
                  <label class="input-label" for="maximum">Minimum</label>
                  <input
                    type="number"
                    class="form-control input"
                    id="minimum"
                    aria-describedby="minimum"
                    placeholder=""
                    v-model="filter.min_price"
                    @change="getAllProducts"
                  />
                </div>
              </div>
              <div class="col-md-6 px-2">
                <div class="form-group mb-2">
                  <label class="input-label" for="minimum">Maximum</label>
                  <input
                    type="number"
                    class="form-control input"
                    id="minimum"
                    aria-describedby="minimum"
                    placeholder=""
                    v-model="filter.max_price"
                    @change="getAllProducts"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-9 p-3">
        <div class="row" v-if="filter.type === 'All'">
          <!-- FOOD START -->
          <h5 class="color1">Snacks</h5>
          <div class="col-md-3 p-2" v-for="product in snacks" :key="product.product_id">
            <div class="card shadow meal">
              <div class="img-container pb-2">
                <img
                  class="img-fluid"
                  :src="product.image"
                  alt="meal-image"
                  @click="redirect(product.product_id)"
                />
              </div>
              <div class="px-3 pb-2">
                <h6 class="text-center" @click="redirect(product.product_id)">
                  {{ product.name }}
                </h6>
              </div>
              <div class="px-3 d-flex justify-content-between">
                <p class="price">₱{{ product.price }}</p>
                <small>{{ product.store_name }} </small>
              </div>
            </div>
          </div>
          <hr />

          <!-- FOOD END -->
          <!-- FOOD START -->
          <h5 class="color1">Drinks</h5>
          <div class="col-md-3 p-2" v-for="product in drinks" :key="product.product_id">
            <div class="card shadow meal">
              <div class="img-container pb-2">
                <img
                  class="img-fluid"
                  :src="product.image"
                  alt="meal-image"
                  @click="redirect(product.product_id)"
                />
              </div>
              <div class="px-3 pb-2">
                <h6 class="text-center" @click="redirect(product.product_id)">
                  {{ product.name }}
                </h6>
              </div>
              <div class="px-3 d-flex justify-content-between">
                <p class="price">₱{{ product.price }}</p>
                <small>{{ product.store_name }} </small>
              </div>
            </div>
          </div>
          <hr />

          <!-- FOOD END -->
          <!-- FOOD START -->
          <h5 class="color1">Meal</h5>
          <div class="col-md-3 p-2" v-for="product in meal" :key="product.product_id">
            <div class="card shadow meal">
              <div class="img-container pb-2">
                <img
                  class="img-fluid"
                  :src="product.image"
                  alt="meal-image"
                  @click="redirect(product.product_id)"
                />
              </div>
              <div class="px-3 pb-2">
                <h6 class="text-center" @click="redirect(product.product_id)">
                  {{ product.name }}
                </h6>
              </div>
              <div class="px-3 d-flex justify-content-between">
                <p class="price">₱{{ product.price }}</p>
                <small>{{ product.store_name }} </small>
              </div>
            </div>
          </div>
          <hr />

          <!-- FOOD END -->
        </div>
        <div class="row" v-else>
          <!-- FOOD START -->
          <div
            class="col-md-3 p-2"
            v-for="product in productsDataDisplay"
            :key="product.product_id"
          >
            <div class="card shadow meal">
              <div class="img-container pb-2">
                <img
                  class="img-fluid"
                  :src="product.image"
                  alt="meal-image"
                  @click="redirect(product.product_id)"
                />
              </div>
              <div class="px-3 pb-2">
                <h6 class="text-center" @click="redirect(product.product_id)">
                  {{ product.name }}
                </h6>
              </div>
              <div class="px-3 d-flex justify-content-between">
                <p class="price">₱{{ product.price }}</p>
                <small>{{ product.store_name }} </small>
              </div>
            </div>
          </div>
          <!-- FOOD END -->
        </div>
        <!-- PAGINATION START -->
        <div class="container py-5" v-if="filter.type !== 'All'">
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
              v-if="pagination.total >= 8"
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
import { useAuthenticationStore } from '../stores/authenticaton'
import Pagination from '../components/Pagination.vue'

import { useRoute, useRouter } from 'vue-router'

const productStore = useProductStore()
const authStore = useAuthenticationStore()

const router = useRouter()

const route = useRoute()

const products = ref(computed(() => productStore.products))

const snacks = ref([])
const drinks = ref([])
const meal = ref([])

const productsDataHolder = ref([])
const productsDataDisplay = ref([])

const pagination = ref({
  maxVisibleButtons: 10,
  totalPages: 1,
  perPage: 8,
  currentPage: 1,
  total: 0,
  showPagination: true,
  firstVal: 1,
  secondVal: 8
})

const getPage = (page) => {
  pagination.value.currentPage = page

  let val1 = 1
  let val2 = 8

  val2 = val2 * pagination.value.currentPage
  val1 = val2 - 7

  pagination.value.firstVal = val1
  pagination.value.secondVal = val2

  productsDataDisplay.value = productsDataHolder.value.slice(val1 - 1, val2)

  if (pagination.value.totalEvents < 8) {
    pagination.value.secondVal = pagination.value.total
  } else if (pagination.value.firstVal + 8 > pagination.value.total) {
    pagination.value.secondVal = pagination.value.total
  }
}

const filter = ref({
  type: 'All',
  min_price: 0,
  max_price: 0,
  search: route.query.search || ''
})

const setPagination = () => {
  if (filter.value.type === 'All') {
    snacks.value = []
    drinks.value = []
    meal.value = []

    snacks.value = products.value.filter((prod) => {
      return prod.type === 'Snacks'
    })

    drinks.value = products.value.filter((prod) => {
      return prod.type === 'Drinks'
    })
    meal.value = products.value.filter((prod) => {
      return prod.type === 'Meal'
    })
  } else {
    productsDataHolder.value = products.value
    productsDataDisplay.value = productsDataHolder.value.slice(0, 8)

    pagination.value.total = productsDataHolder.value.length
    pagination.value.totalPages = parseInt(productsDataHolder.value.length / 8)

    if (productsDataHolder.value.length % 8 !== 0) {
      pagination.value.totalPages += 1
    }
    getPage(1)
  }
}

const getAllProducts = () => {
  productStore
    .getAllProducts({ filter: filter.value })
    .then((data) => {
      if (filter.value.search) {
        router.replace({ query: { search: filter.value.search } })
      } else {
        router.replace({ query: {} })
      }
      setPagination()
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const redirect = (id) => {
  router.push(`/product/detail/${id}`)
}

onMounted(() => {
  getAllProducts()
})
</script>

<style scoped>
.meal-image {
  width: 100%;
  max-height: 220px;
  /*display: flex;
  justify-content: center;
  align-items: center; */
}
a {
  text-decoration: none;
  color: #333;
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
.search-input {
  padding: 12px;
  border-radius: 10px 0px 0px 10px;
}
.btn-to-register {
  font-size: 18px;
  font-weight: 600;
  color: #333;
}
.btn-to-register:hover {
  font-size: 18px;
  font-weight: 600;
  color: var(--color3);
  text-decoration: underline;
}
.color1 {
  color: var(--color1);
}
.menu {
  font-size: 30px;
  position: absolute;
  top: 1px;
  right: 10px;
}
.menu:hover {
  font-size: 30px;
  position: absolute;
  top: 1px;
  right: 10px;
  color: var(--color3);
}

.add-btn {
  font-size: 18px;
  background: var(--color1);
  color: #fff;
  width: 180px;
  /* padding: 4px; */
}
.add-btn:hover {
  font-size: 18px;
  background: var(--color3);
  color: #fff;
}
.modal-header {
  background: var(--color1);
  color: #fff !important;
}
.btn1 {
  background: var(--color1);
  color: #fff;
}
.btn1:hover {
  background: var(--color3);
  color: #fff;
}
</style>
