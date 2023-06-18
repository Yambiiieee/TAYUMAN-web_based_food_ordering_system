<template>
  <div class="bg-img">
    <div class="container my-5">
      <form @submit.prevent="placeOrder">
        <div class="row">
          <div class="col-md-6 py-2">
            <div class="d-flex justify-content-between">
              <h3 class="color1">Order Summary</h3>
              <button
                type="button"
                class="btn add-btn btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#product"
              >
                <i class="bi bi-bag-plus"></i>Add Product
              </button>
            </div>

            <!-- ORDER SUMMARY START -->
            <div class="order-summary">
              <div class="px-2 mb-1" v-for="order in orders" :key="order.id">
                <div class="row p-0 m-0">
                  <p class="col-6 p-0 m-0"><b>Product Name:</b></p>
                  <p class="col-6 p-0 m-0">{{ order.name }}</p>
                </div>
                <div class="row p-0 m-0">
                  <p class="col-6 p-0 m-0"><b>Size:</b></p>
                  <p class="col-6 p-0 m-0">₱{{ order.size }}</p>
                </div>
                <div class="row p-0 m-0">
                  <p class="col-6 p-0 m-0"><b>Price:</b></p>
                  <p class="col-6 p-0 m-0">₱{{ order.price }}</p>
                </div>
                <div class="row p-0 m-0">
                  <p class="col-6 p-0 m-0"><b>Quantity:</b></p>
                  <p class="col-6 p-0 m-0">{{ order.order_quantity }}</p>
                </div>

                <div class="row p-0 m-0">
                  <p class="col-6 p-0 m-0"><b>Payment:</b></p>
                  <p class="col-6 p-0 m-0">₱{{ order.order_quantity * order.price }}</p>
                </div>
                <hr />
              </div>
            </div>
            <!-- ORDER SUMMARY END -->

            <div class="mt-4">
              <h5 class="color1 text-end">
                Total:₱{{ (Math.round(totalPayment * 100) / 100).toFixed(2) }}
              </h5>
            </div>
          </div>
          <div class="col-md-6 card py-4 px-4">
            <!-- INPUT START -->
            <div class="form-group mb-2">
              <label class="input-label" for="name">Payment Method</label>
              <select
                class="form-select"
                aria-label="Select product type "
                v-model="order.payment_method"
              >
                <option>Cash on Delivery</option>
                <!-- <option>Gcash</option> -->
              </select>
              <!-- <small v-if="order.payment_method == 'Gcash'" class="form-text">
                        Please make sure to pay the product using the store Gcash number. Add order
                        id as reference.
                      </small> -->
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
                <optgroup label="COS BUILDING">
                  <option value="COS BUILDING Dean's Office">Dean's Office</option>
                  <option value="COS BUILDING College Secretary">College Secretary</option>
                  <option value="COS BUILDING Mathematics Department">
                    Mathematics Department
                  </option>
                  <option value="COS BUILDING Chemistry Department">Chemistry Department</option>
                  <option value="COS BUILDING Physics Department">Physics Department</option>
                  <option value="COS BUILDING Computer Studies Department">
                    Computer Studies Department
                  </option>
                </optgroup>
                <optgroup label="CIE BUILDING">
                  <option value="CIE BUILDING Dean's Office">Dean's Office</option>
                  <option value="CIE BUILDING College Secretary">College Secretary</option>
                  <option value="CIE BUILDING Technical Arts Dept">Technical Arts Dept</option>
                  <option value="CIE BUILDING Student Teaching Dept">Student Teaching Dept</option>
                  <option value="CIE BUILDING Home Economic Dept">Home Economic Dept</option>
                  <option value="CIE BUILDING Professional Industrial Dept">
                    Professional Industrial Dept
                  </option>
                </optgroup>
                <optgroup label="CLA BUILDING">
                  <option value="CLA BUILDING Dean's Office">Dean's Office</option>
                  <option value="CLA BUILDING College Secretary">College Secretary</option>
                  <option value="CLA BUILDING LanguageDept">LanguageDept</option>
                  <option value="CLA BUILDING Enterpreneurship Management Dept">
                    Enterpreneurship Management Dept
                  </option>
                  <option value="CLA BUILDING Physical Education Dept">
                    Physical Education Dept
                  </option>
                </optgroup>
                <optgroup label="CAFA BUILDING">
                  <option value="CAFA BUILDING Dean's Office">Dean's Office</option>
                  <option value="CAFA BUILDING College Secretary">College Secretary</option>
                  <option value="CAFA BUILDING Architecture Dept">Architecture Dept</option>
                  <option value="CAFA BUILDING Fine Arts Dept">Fine Arts Dept</option>
                  <option value="CAFA BUILDING Graphics Dept">Graphics Dept</option>
                </optgroup>
                <optgroup label="COE BUILDING">
                  <option value="COE BUILDING Dean's Office">Dean's Office</option>
                  <option value="COE BUILDING College Secretary">College Secretary</option>
                  <option value="COE BUILDING Electrical Engineering Dept">
                    Electrical Engineering Dept
                  </option>
                  <option value="COE BUILDING Electronics Engineering Dept">
                    Electronics Engineering Dept
                  </option>
                  <option value="COE BUILDING Mechanical Engineering Dept">
                    Mechanical Engineering Dept
                  </option>
                  <option value="COE BUILDING Civil Engineering Dept">
                    Civil Engineering Dept
                  </option>
                </optgroup>
                <optgroup label="CIT BUILDING">
                  <option value="CIT BUILDING Dean's Office">Dean's Office</option>
                  <option value="CIT BUILDING College Secretary">College Secretary</option>
                  <option value="CIT BUILDING Basic Industrial Technology Dept">
                    Basic Industrial Technology Dept
                  </option>
                  <option value="CIT BUILDING Civil Engineering Technology Dept">
                    Civil Engineering Technology Dept
                  </option>
                  <option value="CIT BUILDING Food and Apparel Technology Dept">
                    Food and Apparel Technology Dept
                  </option>
                  <option
                    value="CIT BUILDING Graphic Arts and Printing Technology Dept Mechanical Engineering Technology Dept"
                  >
                    Graphic Arts and Printing Technology Dept Mechanical Engineering Technology Dept
                  </option>
                  <option value="CIT BUILDING Power Plant Engineering Technology Dept">
                    Power Plant Engineering Technology Dept
                  </option>
                  <option value="CIT BUILDING Electronics Engineering Technology Dept">
                    Electronics Engineering Technology Dept
                  </option>
                  <option value="CIT BUILDING Student Teaching Dept">Student Teaching Dept</option>
                  <option value="CIT BUILDING Electrical Engineering Dept">
                    Electrical Engineering Dept
                  </option>
                </optgroup>
                <optgroup label="IRTC BUILDING">
                  <option value="IRTC BUILDING 1st Floor">1st Floor</option>
                  <option value="IRTC BUILDING 2nd Floor">2nd Floor</option>
                  <option value="IRTC BUILDING 3rd Floor">3rd Floor</option>
                  <option value="IRTC BUILDING 5th Floor">5th Floor</option>
                </optgroup>
              </select>
              <small v-if="error.place" class="form-text text-danger">{{ error.place }}</small>
            </div>

            <div class="form-floating">
              <textarea
                class="form-control"
                placeholder="Enter product description"
                id="note"
                style="height: 200px"
                v-model="order.note"
              ></textarea>
              <label for="note">Notes</label>
            </div>
            <!-- <small v-if="error.description" class="form-text text-danger">{{
          error.description
        }}</small> -->

            <!-- INPUT END -->
          </div>
          <div class="col-md-12 d-flex justify-content-end mt-4">
            <button type="button" class="btn btn1 px-4" v-show="isLoading">
              <span class="spinner-border spinner-border-sm"></span>
              <i class="bi bi-cart-check-fill"></i>Placing Order.....
            </button>
            <button type="submit" class="btn btn1 px-4" v-show="!isLoading">
              <i class="bi bi-cart-check-fill"></i> Place Order
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- ADD PRODUCT START -->
  <div
    class="modal fade"
    id="product"
    tabindex="-1"
    role="dialog"
    aria-labelledby="delete"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg1 text-white">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row products">
            <!-- FOOD START -->
            <h5 class="color1">Snacks</h5>
            <div
              class="col-md-3 p-2"
              v-for="product in snacks"
              :key="product.product_id"
              @click="addItem(product)"
            >
              <div class="card shadow meal">
                <div class="img-container pb-2">
                  <img class="img-fluid" :src="product.image" alt="meal-image" />
                </div>
                <div class="px-3 pb-2">
                  <h6 class="text-center">
                    {{ product.name }} <span class="size">({{ product.size }})</span>
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
            <div
              class="col-md-3 p-2"
              v-for="product in drinks"
              :key="product.product_id"
              @click="addItem(product)"
            >
              <div class="card shadow meal">
                <div class="img-container pb-2">
                  <img class="img-fluid" :src="product.image" alt="meal-image" />
                </div>
                <div class="px-3 pb-2">
                  <h6 class="text-center">
                    {{ product.name }}<span class="size">({{ product.size }})</span>
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
            <div
              class="col-md-3 p-2"
              v-for="product in meal"
              :key="product.product_id"
              @click="addItem(product)"
            >
              <div class="card shadow meal">
                <div class="img-container pb-2">
                  <img class="img-fluid" :src="product.image" alt="meal-image" />
                </div>
                <div class="px-3 pb-2">
                  <h6 class="text-center">
                    {{ product.name }} <span class="size">({{ product.size }})</span>
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  <!-- ADD PRODUCT END -->
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useProductStore } from '../stores/product'
import { useRoute, useRouter } from 'vue-router'
import Pagination from '../components/Pagination.vue'
import { useAuthenticationStore } from '../stores/authenticaton'

const productStore = useProductStore()
const route = useRoute()
const router = useRouter()

const isLoading = ref(false)
const store = useAuthenticationStore()
const user = ref(store.user)
const showError = ref(false)
const error = ref({
  place: '',
  time: ''
})
const carts = ref(computed(() => productStore.user_carts))
const id = ref('')
const orders = ref([])

const order = ref({
  quantity: 0,
  type: 'To pick up',
  place: '',
  time: '',
  note: '',
  cart_id: 0,
  payment: 0,
  status: 'New',
  payment_method: 'Cash on Delivery'
})

const snacks = ref([])
const drinks = ref([])
const meal = ref([])
const products = ref(computed(() => productStore.store_products))
const totalPayment = ref(0)

const addItem = (data) => {
  // check if item exist on orders
  let found = false
  let i
  for (i = 0; i < orders.value.length; i++) {
    if (orders.value[i].product_id === data.product_id) {
      found = true
      break
    }
  }
  if (found) {
    orders.value[i].order_quantity += 1
    orders.value[i].payment += data.price
  } else {
    orders.value.push({
      ...data,
      order_quantity: 1,
      payment: data.price
    })
  }

  totalPayment.value += data.price

  $('#product').modal('hide')
}

const placeOrder = () => {
  /**Validation */
  let sendData = []
  let isValid = true

  error.value = {
    place: '',
    time: ''
  }

  if (order.value.type == 'For delivery' && order.value.place.length == 0) {
    error.value.place = 'Please select delivery place.'
    isValid = false
  }

  if (isValid) {
    console.log('111111111111111')
    /**Call add to cart */
    let counter = 1
    isLoading.value = true
    for (let j = 0; j < orders.value.length; j++) {
      productStore
        .addWalkInOrder({
          ...order.value,
          product: orders.value[j].product_id,
          quantity: orders.value[j].order_quantity,
          payment: (
            Math.round(orders.value[j].order_quantity * orders.value[j].price * 100) / 100
          ).toFixed(2)
        })
        .then((data) => {
          console.log('hello', data)
          // isLoading.value = false
          if (data?.status === 200 || data?.status === 201) {
            //  redirect here
            sendData.push(orders.value[j])
            console.log('counter', counter)
            if (counter == orders.value.length && sendData.length > 0) {
              /**Send email first */
              productStore
                .sendReceipt({
                  total: totalPayment.value,
                  data: JSON.stringify(sendData)
                })
                .then((data) => {
                  isLoading.value = false
                  if (data?.status === 200 || data?.status === 201) {
                    //  redirect here
                    console.log('counter', counter)
                    console.log('Redirect')
                    // $('#placeOrder').modal('hide')
                    router.push(`/orders`)
                  }
                })
                .catch((err) => {
                  isLoading.value = false

                  console.log('Error', err)
                })
              // console.log('Redirect')
              // $('#placeOrder').modal('hide')
              // router.push(`/purchase`)
            }

            counter++
          } else {
            alert(orders.value[j].name + ' is not available.')
            isLoading.value = false
            counter++

            if (counter == orders.value.length && sendData.length > 0) {
              /**Send email first */
              productStore
                .sendReceipt({
                  total: totalPayment.value,
                  data: JSON.stringify(sendData)
                })
                .then((data) => {
                  isLoading.value = false
                  if (data?.status === 200 || data?.status === 201) {
                    //  redirect here
                    console.log('counter', counter)
                    console.log('Redirect')
                    $('#placeOrder').modal('hide')
                    router.push(`/purchase`)
                  }
                })
                .catch((err) => {
                  isLoading.value = false

                  console.log('Error', err)
                })
              // console.log('Redirect')
              // $('#placeOrder').modal('hide')
              // router.push(`/purchase`)
            }
          }
        })
        .catch((err) => {
          isLoading.value = false

          console.log('Error', err)
        })
    }
  }
}

const placeOrderData = ref([])

const getUserCart = () => {
  productStore
    .getUserCart()
    .then((data) => {})
    .catch((err) => {
      console.log('Error', err)
    })
}

const filter = ref({
  type: 'All',
  min_price: 0,
  max_price: 0,
  search: ''
})
const getStoreProducts = () => {
  productStore
    .getStoreProducts({ id: user.value.id, filter: filter.value })
    .then((data) => {
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
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

onMounted(() => {
  getUserCart()
  getStoreProducts()
})
</script>

<style scoped>
.bg-img {
  background-image: url('../assets/bg.svg');

  min-height: 100vh !important;
}
.products {
  max-height: 500px;
  overflow-y: scroll;
}
.size {
  font-size: 10px;
}
/* .img-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 150px;
}
.img-container img {
  height: 100%;
} */
.cart {
  border-radius: 7px;
}
.add-btn {
  font-size: 18px;
  background: var(--color1);
  color: #fff;
  width: 130px;
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
.order-summary {
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
  height: 150px !important;
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
