<template>
  <div class="bg-img">
    <div class="container-fluid">
      <div class="row d-flex justify-content-center mx-2 mt-4">
        <div class="col-md-12 py-5 d-flex justify-content-between">
          <h1 class="text-center color1">My Cart</h1>
          <button
            class="btn btn1 mx-2"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#placeOrder"
            @click="getOrders"
            :disabled="placeOrderData.length == 0"
          >
            Place Order
          </button>
        </div>
      </div>

      <div class="row mx-2">
        <!-- CART ITEM START -->
        <div class="col-md-6" v-for="cart in carts" :key="cart.id">
          <div class="cart border shadow bg-white mb-1">
            <div class="row p-2">
              <div class="col-md-2 text-center">
                <div class="mt-lg-5 d-flex justify-content-lg-center justify-content-md-start">
                  <input
                    class="form-check-input p-3"
                    type="checkbox"
                    value=""
                    @click="addRemoveId(cart.cart_id)"
                    :disabled="cart.quantity < cart.order_quantity"
                  />
                  <br />
                </div>
                <span class="text-danger text-center" v-if="cart.quantity < cart.order_quantity"
                  >Product Not Available</span
                >
              </div>

              <div class="col-md-3">
                <div class="img-container p-2">
                  <img class="img-fluid" :src="cart.image" alt="meal-image" />
                </div>
              </div>
              <div class="col-md-7 py-2">
                <div class="d-flex justify-content-end">
                  <small>{{ cart.cart_created }}</small>
                </div>
                <h3 class="color1">{{ cart.name }}</h3>
                <p>
                  Quantity:<b>{{ cart.order_quantity }}</b>
                </p>
                <p>Price:₱{{ cart.price }}</p>
                <p>
                  Total Payment: ₱<b>{{
                    (Math.round(placeOrderData.value[j].payment * 100) / 100).toFixed(2)
                  }}</b>
                </p>
                <div class="d-flex justify-content-end">
                  <button
                    class="btn btn-outline-danger mx-2"
                    type="button"
                    @click="setData(cart)"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteCart"
                  >
                    Delete
                  </button>
                  <!-- <router-link
                    :to="`product/place-order/${cart.cart_id}`"
                    class="btn btn1"
                    type="button"
                    >Place Order</router-link
                  > -->
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CART ITEM END -->
        <!-- PAGINATION START -->
        <!-- <div class="container py-5">
          <div class="px-2 pb-2 table-pagination" style="margin-top: -40px; width: 100%">
            <div class="d-flex align-items-center pagination-label">
              Showing {{ pagination.firstVal }} to {{ pagination.secondVal }} of
              {{ pagination.total }}
            </div> -->

        <!-- Imported Pagination Component -->
        <!-- <Pagination
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
        </div> -->
        <!-- PAGINATION END -->
      </div>
    </div>

    <!-- DELETE PRODUCT START -->
    <div
      class="modal fade"
      id="deleteCart"
      tabindex="-1"
      role="dialog"
      aria-labelledby="delete"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <form @submit.prevent="submitDelete">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button
                type="button"
                class="btn text-white"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
              </button>
            </div>
            <div class="modal-body">
              <i
                class="bi bi-exclamation-circle-fill text-danger justify-content-center d-flex"
              ></i>
              <div class="col content-modal">
                <h4 class="justify-content-center d-flex fw-semibold">Delete Record</h4>
                <p class="justify-content-center d-flex text-black-50 mt-3">
                  Are you sure you want to delete this record?
                </p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger px-4" v-show="isLoading">
                <i class="bi bi-trash"></i>
                <span class="spinner-border spinner-border-sm"></span>Delete.....
              </button>
              <button type="submit" class="btn btn-danger px-4" v-show="!isLoading">
                <i class="bi bi-trash"></i> Delete
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- DELETE PRODUCT END -->
    <!-- PLACE  PRODUCT ORDER START -->
    <div
      class="modal fade"
      id="placeOrder"
      tabindex="-1"
      role="dialog"
      aria-labelledby="delete"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <form @submit.prevent="placeOrder">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-white" id="exampleModalLabel">Place Order</h5>
              <button
                type="button"
                class="btn text-white"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6 py-2">
                  <h3 class="color1">Order Summary</h3>

                  <!-- ORDER SUMMARY START -->
                  <div class="order-summary">
                    <div class="px-2 mb-1" v-for="order in placeOrderData" :key="order.id">
                      <div class="row p-0 m-0">
                        <p class="col-6 p-0 m-0"><b>Product Name:</b></p>
                        <p class="col-6 p-0 m-0">{{ order.name }}</p>
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
                    <h5 class="color1 text-end">Total:₱{{ totalPayment }}</h5>
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
                    <select
                      class="form-select"
                      aria-label="Select product type "
                      v-model="order.type"
                    >
                      <option>To pick up</option>
                      <option>For delivery</option>
                    </select>
                    <!-- <small v-if="error.type" class="form-text text-danger">{{ error.type }}</small> -->
                  </div>
                  <!-- INPUT END -->

                  <!-- INPUT START -->
                  <div class="form-group mb-2" v-if="order.type == 'For delivery'">
                    <label class="input-label" for="name">Delivery Place</label>
                    <select
                      class="form-select"
                      aria-label="Select product type "
                      v-model="order.place"
                    >
                      <optgroup label="COS BUILDING">
                        <option value="COS BUILDING Dean's Office">Dean's Office</option>
                        <option value="COS BUILDING College Secretary">College Secretary</option>
                        <option value="COS BUILDING Mathematics Department">
                          Mathematics Department
                        </option>
                        <option value="COS BUILDING Chemistry Department">
                          Chemistry Department
                        </option>
                        <option value="COS BUILDING Physics Department">Physics Department</option>
                        <option value="COS BUILDING Computer Studies Department">
                          Computer Studies Department
                        </option>
                      </optgroup>
                      <optgroup label="CIE BUILDING">
                        <option value="CIE BUILDING Dean's Office">Dean's Office</option>
                        <option value="CIE BUILDING College Secretary">College Secretary</option>
                        <option value="CIE BUILDING Technical Arts Dept">
                          Technical Arts Dept
                        </option>
                        <option value="CIE BUILDING Student Teaching Dept">
                          Student Teaching Dept
                        </option>
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
                          Graphic Arts and Printing Technology Dept Mechanical Engineering
                          Technology Dept
                        </option>
                        <option value="CIT BUILDING Power Plant Engineering Technology Dept">
                          Power Plant Engineering Technology Dept
                        </option>
                        <option value="CIT BUILDING Electronics Engineering Technology Dept">
                          Electronics Engineering Technology Dept
                        </option>
                        <option value="CIT BUILDING Student Teaching Dept">
                          Student Teaching Dept
                        </option>
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
                    <small v-if="error.place" class="form-text text-danger">{{
                      error.place
                    }}</small>
                  </div>
                  <!-- INPUT END -->
                  <!-- INPUT START -->
                  <!-- <div class="form-group mb-2">
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
                  </div> -->
                  <!-- INPUT END -->
                  <!-- INPUT START -->
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
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Cancel</button>
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
    <!-- PLACE  PRODUCT ORDER END -->
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useProductStore } from '../stores/product'
import { useRoute, useRouter } from 'vue-router'
import Pagination from '../components/Pagination.vue'

const productStore = useProductStore()
const route = useRoute()
const router = useRouter()

const isLoading = ref(false)

const showError = ref(false)
const error = ref({
  place: '',
  time: ''
})
const carts = ref(computed(() => productStore.user_carts))
const id = ref('')

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
    for (let j = 0; j < placeOrderData.value.length; j++) {
      productStore
        .addOrder({
          ...order.value,
          cart_id: placeOrderData.value[j].cart_id,
          quantity: placeOrderData.value[j].order_quantity,
          payment: (
            Math.round(
              placeOrderData.value[j].order_quantity * placeOrderData.value[j].price * 100
            ) / 100
          ).toFixed(2)
        })
        .then((data) => {
          console.log('hello', data)
          // isLoading.value = false
          if (data?.status === 200 || data?.status === 201) {
            //  redirect here
            sendData.push(placeOrderData.value[j])
            console.log('counter', counter)
            if (counter == placeOrderData.value.length && sendData.length > 0) {
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

            counter++
          } else {
            alert(placeOrderData.value[j].name + ' is not available.')
            isLoading.value = false
            counter++

            if (counter == placeOrderData.value.length && sendData.length > 0) {
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
const totalPayment = ref(0)
const getOrders = () => {
  placeOrderData.value = []
  for (let j = 0; j < addOrderIds.value.length; j++) {
    for (let i = 0; i < carts.value.length; i++) {
      if (addOrderIds.value[j] === carts.value[i].cart_id) {
        placeOrderData.value.push(carts.value[i])
      }
    }
  }
  totalPayment.value = 0
  for (let j = 0; j < placeOrderData.value.length; j++) {
    totalPayment.value += placeOrderData.value[j].order_quantity * placeOrderData.value[j].price
  }
  totalPayment.value = (Math.round(totalPayment.value * 100) / 100).toFixed(2)
  console.log(placeOrderData.value)
}
const addOrderIds = ref([])
const addRemoveId = (id) => {
  var status = 0

  status = 1
  for (var i = 0; i < addOrderIds.value.length; i++) {
    if (addOrderIds.value[i] == id) {
      status = 0
      break
    }
  }

  if (status == 1) {
    addOrderIds.value.push(id)
  } else {
    addOrderIds.value = addOrderIds.value.filter((x) => x != id)
  }
  getOrders()
  console.log('id', addOrderIds.value)
}

const setData = (data) => {
  id.value = data.cart_id
}
const getUserCart = () => {
  productStore
    .getUserCart()
    .then((data) => {})
    .catch((err) => {
      console.log('Error', err)
    })
}

const redirect = (id) => {
  router.push(`/product/detail/${id}`)
}

const submitDelete = () => {
  /**Add validation here */
  showError.value = false

  // if (!isLoading) {

  isLoading.value = true
  productStore
    .deleteCart({ id: id.value })
    .then((data) => {
      isLoading.value = false

      if (data?.code === 'ERR_BAD_REQUEST') {
        showError.value = true
      } else {
        $('#deleteCart').modal('hide')
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

onMounted(() => {
  getUserCart()
})
</script>

<style scoped>
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
</style>
