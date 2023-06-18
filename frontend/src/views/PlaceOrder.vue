<template>
  <div class="container my-4">
    <div class="row">
      <div class="col-md-7 px-5">
        <div class="img-container p-2">
          <img class="img-fluid" :src="cart.image" alt="meal-image" />
        </div>
        <div class="px-3 pb-2">
          <h4 class="color1 text-center">{{ cart.name }}</h4>
        </div>
        <div class="px-3">
          <p>{{ cart.quantity }} Available</p>
        </div>
        <div class="px-3 d-flex justify-content-between">
          <p class="price">₱{{ cart.price }}</p>
          <small>{{ cart.store_name }} </small>
        </div>

        <div class="quantiy-container px-3">
          <p>Quantity:</p>
          <div class="input-group mb-3 quantiy-input-container">
            <div class="input-group-prepend">
              <span class="input-group-text quantity-btn" @click="minus">-</span>
            </div>
            <input type="text" class="form-control mx-1 quantity-input" v-model="order.quantity" />
            <div class="input-group-prepend">
              <span class="input-group-text quantity-btn" @click="add">+</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5 card py-4 px-4">
        <form @submit.prevent="placeOrder">
          <!-- INPUT START -->
          <div class="form-group mb-2">
            <label class="input-label" for="name">Payment Method</label>
            <select
              class="form-select"
              aria-label="Select product type "
              v-model="order.payment_method"
            >
              <option>Cash on Delivery</option>
              <option>Gcash</option>
            </select>
            <small v-if="order.payment_method == 'Gcash'" class="form-text">
              Please make sure to pay the product using the store Gcash number. Add order id as
              reference.
            </small>
            <small v-if="error.payment_method" class="form-text text-danger">{{
              error.payment_method
            }}</small>
          </div>
          <!-- INPUT END -->
          <!-- INPUT START -->
          <div class="form-group mb-2">
            <label class="input-label input" for="type">Order Type</label>
            <select class="form-select" aria-label="Select product type " v-model="order.type">
              <option>To pick up</option>
              <option>For delivery</option>
            </select>
            <!-- <small v-if="error.type" class="form-text text-danger">{{ error.type }}</small> -->
          </div>
          <!-- INPUT END -->

          <!-- INPUT START -->
          <div class="form-group mb-2" v-if="order.type == 'For delivery'">
            <label class="input-label" for="name">Delivery Place</label>
            <select class="form-select" aria-label="Select product type " v-model="order.place">
              <option>College of Science Building</option>
              <option>College of Industrial Education Building</option>
              <option>College of Industrial Technology Building</option>
              <option>College of Engineering Building</option>
              <option>College of Liberal Arts Building</option>
              <option>College of Architecture and Fine Arts Building</option>
              <option>Integrated Research and Training Center Building</option>
              <option>Industrial Relation and Job Placement Office Building</option>
            </select>
            <small v-if="error.place" class="form-text text-danger">{{ error.place }}</small>
          </div>
          <!-- INPUT END -->
          <!-- INPUT START -->
          <div class="form-group mb-2">
            <label class="input-label" for="name">Time</label>
            <input
              type="time"
              class="form-control input"
              id="time"
              aria-describedby="time"
              placeholder="Enter Time"
              v-model="order.time"
            />
            <small v-if="error.time" class="form-text text-danger">{{ error.time }}</small>
          </div>
          <!-- INPUT END -->
          <!-- INPUT START -->
          <div class="form-floating">
            <textarea
              class="form-control"
              placeholder="Enter product description"
              id="note"
              style="height: 175px"
              v-model="order.note"
            ></textarea>
            <label for="note">Notes</label>
          </div>
          <!-- <small v-if="error.description" class="form-text text-danger">{{
          error.description
        }}</small> -->
          <div class="mt-4">
            <p>
              <b
                >Total Payment: <span class="color1">{{ order?.payment }}</span></b
              >
            </p>
          </div>
          <!-- INPUT END -->
          <div class="mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn1" v-if="!isLoading">Place Order</button>
            <button type="button" class="btn btn1" v-if="isLoading">
              <span class="spinner-border spinner-border-sm"></span>
              Submitting.....
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- ADDED TO CART MODAL START -->
    <div
      class="modal fade"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
      id="addToCart"
    >
      <div class="modal-dialog modal-sm">
        <form @submit.prevent="submitAddToCart">
          <div class="modal-content">
            <div class="modal-header border-0">
              <!-- <h5 class="modal-title text-white" id="addToCar">Add To</h5> -->
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body text-center">
              <i class="bi bi-bag-check-fill color1 bag"></i>
              <h5 class="text-center p-0">Added to cart</h5>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- ADDED TO CART MODAL END -->
  </div>
</template>

<script setup>
import { useProductStore } from '../stores/product'
import { useAuthenticationStore } from '../stores/authenticaton'

import { useRoute, useRouter } from 'vue-router'
import { computed, onMounted, ref } from 'vue'

const productStore = useProductStore()
const authStore = useAuthenticationStore()

const orderQuantity = ref(0)
const orderQuantityError = ref(false)

const router = useRouter()

const route = useRoute()

const isLoading = ref(false)

const isValidOrder = ref(false)

const showError = ref(false)

const cart = ref(computed(() => productStore.cart))

const isLoggedIn = ref(computed(() => authStore.isLoggedIn))

const order = ref({
  quantity: cart.order_quantity,
  type: 'To pick up',
  place: '',
  time: '',
  note: '',
  cart_id: route.params.id,
  payment: 0,
  status: 'New',
  payment_method: 'Cash on Delivery'
})

const error = ref({
  place: '',
  time: ''
})

const add = () => {
  order.value.quantity += 1
  order.value.payment = cart.value.price * order.value.quantity
}

const minus = () => {
  if (order.value.quantity <= 0) {
    order.value.quantity = 0
  } else {
    order.value.quantity -= 1
  }
  order.value.payment = cart.value.price * order.value.quantity
}

const getCartDetail = () => {
  productStore
    .getCartDetail({ id: route.params.id })
    .then((data) => {
      order.value.quantity = data.order_quantity
      order.value.payment = data.price * data.order_quantity
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const placeOrder = () => {
  /**Validation */
  let isValid = true

  error.value = {
    place: '',
    time: ''
  }

  if (order.value.time.length == 0) {
    error.value.time = 'Please select time.'
    isValid = false
  }

  if (order.value.time.length == 0 && order.value.type == 'To pick up') {
    error.value.time = 'Please select delivery place.'
    isValid = false
  }

  if (order.value.quantity == 0 || cart.quantity === 0) {
    isValid = false
  }

  if (isValid) {
    /**Call add to cart */
    isLoading.value = true

    productStore
      .addOrder(order.value)
      .then((data) => {
        isLoading.value = false
        if (data?.status === 200 || data?.status === 201) {
          //  redirect here
          router.push(`/order/detail/${data.data.data.order.order_id}`)
        }
      })
      .catch((err) => {
        isLoading.value = false

        console.log('Error', err)
      })
  }
}

const redirect = (id) => {
  router.push(`/product/detail/${id}`)
}

onMounted(() => {
  getCartDetail()
})
</script>

<style scoped>
.bag {
  font-size: 100px;
}
.img-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 250px;
}
.img-container img {
  height: 100%;
}
.color1 {
  color: var(--color1);
}
.price {
  background: var(--color1);
  color: #fff;
  font-size: 18px;
  font-weight: 600;
  padding: 0px 5px;
  border-radius: 5px;
}
.quantiy-input-container {
  width: 135px;

  margin-left: 5px;
}
.quantiy-container {
  display: flex;
  align-items: center;
}
.quantity-btn {
  border-radius: 50%;
  height: 38px;
  cursor: pointer;
}
.quantity-btn:hover {
  border-radius: 50%;
  height: 38px;
  cursor: pointer;
  background: var(--color1);
  color: #fff;
}
.quantity-input {
  height: 35px;
  border-radius: 10px !important;
  text-align: center;
}
.add-to-cart-btn {
  font-size: 18px;
  background: var(--color1);
  color: #fff;
  width: 300px;
  padding: 7px;
}
.add-to-cart-btn:hover {
  font-size: 18px;
  background: var(--color3);
  color: #fff;
}
</style>
