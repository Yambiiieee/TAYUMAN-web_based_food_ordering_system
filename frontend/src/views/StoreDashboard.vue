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

    <div class="container" v-if="user?.type === 'store' && isLoggedIn === true">
      <div class="row my-5">
        <div class="col-md-12 d-flex justify-content-end mb-5">
          <router-link to="new-order" class="btn add-user-btn">
            <i class="bi bi-bag-plus-fill"></i> Add New Order
          </router-link>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body bg-white shadow rounded">
              <h4 class="color1">Order Today</h4>
              <p class="user-count">{{ sales_today?.count }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body bg-white shadow rounded">
              <h4 class="color1">Sales Today</h4>
              <p class="user-count">₱{{ sales_today?.total }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- STORE TABLE -->
      <div class="container bg-white">
        <table class="table">
          <thead class="">
            <tr class="pt-5">
              <th>Product ID</th>
              <th>Name</th>
              <th>Type</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in store_products" :key="product.product_id">
              <td>{{ product.product_id }}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.type }}</td>
              <td>₱{{ product.price }}</td>
              <td class="text-danger" v-if="product.quantity < 3">
                <b>{{ product.quantity }}</b>
              </td>
              <td v-else-if="product.quantity < 10 && product.quantity >= 3" class="text-warning">
                <b
                  ><span>{{ product.quantity }}</span></b
                >
              </td>
              <td v-else>
                <b>{{ product.quantity }}</b>
              </td>
              <td>
                <button
                  class="btn btn-outline-success mx-1 btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#addStock"
                  @click="setData(product)"
                >
                  Add Stock
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Add Order Modal -->
    <div
      class="modal fade"
      id="addNewOrder"
      tabindex="-1"
      aria-labelledby="addNewOrder"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="submitAddNewOrder">
            <div class="modal-header">
              <h1 class="modal-title fs-5 text-white" id="addNewOrder">Add New Order</h1>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="product">Product</label>
                <select
                  class="form-select"
                  aria-label="product"
                  id="product"
                  v-model="order.product"
                  @change="calculatePayment"
                >
                  <option selected value="0">Select Product</option>
                  <option
                    :value="product.product_id"
                    v-for="product in store_products"
                    :key="product.product_id"
                  >
                    {{ product.name }}
                  </option>
                </select>
                <small v-if="error.product" class="form-text text-danger">
                  {{ error.product }}
                </small>
              </div>
              <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input
                  type="text"
                  class="form-control"
                  id="quantity"
                  min="1"
                  v-model="order.quantity"
                  @change="calculatePayment"
                />
                <small v-if="error.quantity" class="form-text text-danger">
                  {{ error.quantity }}
                </small>
              </div>
              <div class="mb-3">
                <h5 class="color1 text-end px-5">Total payment: {{ order.payment }}</h5>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn1 px-4" v-show="isLoading">
                <span class="spinner-border spinner-border-sm"></span>Submitting.....
              </button>
              <button type="submit" class="btn btn1 px-4" v-show="!isLoading">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Add Stock Modal -->
    <div
      class="modal fade"
      id="addStock"
      tabindex="-1"
      aria-labelledby="addStock"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="submitEdit">
            <div class="modal-header">
              <h1 class="modal-title fs-5 text-white" id="addStock">Add Stock</h1>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="text" class="form-control" id="stock" v-model="newStock" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn1 px-4" v-show="isLoading">
                <span class="spinner-border spinner-border-sm"></span>Submitting.....
              </button>
              <button type="submit" class="btn btn1 px-4" v-show="!isLoading">Submit</button>
            </div>
          </form>
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
const productStore = useProductStore()

const sales_today = ref(computed(() => productStore.sales_today))
const newStock = ref(0)
const product_data = ref({})
const productId = ref(0)
const isLoading = ref(false)

const router = useRouter()

const order = ref({
  product: 0,
  quantity: 0,
  type: 'Walk-in',
  payment: 0,
  status: 'New',
  payment_method: 'Cash'
})

const setData = (data) => {
  productId.value = data.product_id
  let productData = { ...data }
  newStock.value = productData.quantity
  product_data.value = {
    image: productData.image,
    name: productData.name,
    price: productData.price,
    quantity: productData.quantity,
    type: productData.type,
    description: productData.description
  }
}

const calculatePayment = () => {
  console.log('heeeeeeeeeere', order.value.product)
  if (order.value.product === 0) {
    order.value.payment = 0
  } else {
    let prod = store_products.value.find((product) => {
      return (product.product_id = order.value.product)
    })
    console.log('prod', prod)
    order.value.payment = order.value.quantity * prod.price
  }
}

const submitEdit = () => {
  /**Add validation here */
  console.log('hello')
  isLoading.value = true
  // if (previewImageEdit.value) {
  //   productEdit.value.image = previewImageEdit.value
  // }
  productStore
    .updateProduct(productId.value, {
      ...product_data.value,
      quantity: newStock.value
    })
    .then((data) => {
      isLoading.value = false

      if (data?.code === 'ERR_BAD_REQUEST') {
        showError.value = true
      } else {
        $('#addStock').modal('hide')
        getStoreProducts()
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const store_products = ref(computed(() => productStore.store_products))

const store = useAuthenticationStore()
const user = ref(store.user)
const isLoggedIn = ref(computed(() => store.isLoggedIn))
const filter = ref({
  type: '',
  min_price: 0,
  max_price: 0,
  search: '',
  limit: 8
})

const filter3 = ref({
  type: '',
  min_price: 0,
  max_price: 0,
  search: '',
  limit: 0
})
const getAllProducts = () => {
  productStore
    .getAllProducts({ filter: filter.value })
    .then((data) => {
      if (filter.value.search) {
        router.replace({ query: { search: filter.value.search } })
      } else {
        router.replace({ query: {} })
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const getStoreProducts = () => {
  productStore
    .getStoreProducts({ id: user.value.id, filter: filter3.value })
    .then((data) => {})
    .catch((err) => {
      console.log('Error', err)
    })
}

const getSalesToday = () => {
  productStore
    .getSalesToday({})
    .then((data) => {})
    .catch((err) => {
      console.log('Error', err)
    })
}

const error = ref({
  product: '',
  quantity: '',
  payment: '',
  stock: ''
})

const submitAddNewOrder = () => {
  /**Add validation here */

  let isValid = true

  error.value = {
    product: '',
    quantity: '',
    payment: ''
  }

  if (order.value.product == '') {
    error.value.product = 'Please select product.'
    isValid = false
  }

  if (order.value.quantity.length == 0) {
    error.value.quantity = 'Please enter quantity.'
    isValid = false
  }

  if (isValid && isLoading) {
    console.log('hello')
    isLoading.value = true
    productStore
      .addWalkInOrder(order.value)
      .then((data) => {
        isLoading.value = false
        if (data?.status === 200 || data?.status === 201) {
          //  redirect here
          $('#addNewOrder').modal('hide')
          router.push(`/order/detail/${data.data.data.order.order_id}`)
        }
      })
      .catch((err) => {
        isLoading.value = false

        console.log('Error', err)
      })
  }
}
onMounted(() => {
  getAllProducts()

  if (isLoggedIn.value === true) {
    getSalesToday()
    getStoreProducts()
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
