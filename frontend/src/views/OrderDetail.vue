<template>
  <div class="container my-4">
    <div class="row">
      <div class="col-md-7 px-5">
        <div class="img-container p-2">
          <img class="img-fluid" :src="order.image" alt="meal-image" />
        </div>
        <div class="px-3 pb-2">
          <h4 class="color1 text-center">{{ order.name }}</h4>
        </div>
        <div class="px-3">
          <p>{{ order.quantity }} Available</p>
        </div>
        <div class="px-3 d-flex justify-content-between">
          <p class="price">₱{{ order.price }}</p>
          <router-link :to="`/store/${order.product_by}`">{{ order.store_name }} </router-link>
        </div>
        <div class="">
          <p><b>Size:</b> {{ order?.size }}</p>
          <p><b>Description:</b></p>
          <p class="px-2">
            {{ order.description }}
          </p>
        </div>
      </div>
      <div class="col-md-5 card py-4 px-4">
        <h3 class="color1">Order Details</h3>
        <form @submit.prevent="updateStatus">
          <div class="row">
            <div class="col-4">
              <p><b>Order ID:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.order_id }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p><b>Product Name:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.name }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p><b>Quantity:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.order_quantity }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p><b>Payment Method:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.payment_method }}</p>
            </div>
          </div>
          <div class="row" v-if="order.payment_method == 'Gcash'">
            <div class="col-4">
              <p><b>Store Gcash:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.store_gcash }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p><b>Status:</b></p>
            </div>
            <div class="col-7">
              <!-- INPUT START -->
              <div class="form-group mb-2" v-if="user.id === order.product_by">
                <select class="form-select" aria-label="Select product type " v-model="orderStatus">
                  <option>New</option>
                  <!-- <option>Accepted</option> -->
                  <option>Preparing</option>
                  <option>Ready</option>

                  <option>To Pick up</option>
                  <option>For Delivery</option>
                  <!-- <option>Paid</option> -->
                  <option>Done</option>
                  <!-- <option>Declined</option> -->
                  <!-- <option>Canceled</option> -->
                </select>
                <small v-if="error.place" class="form-text text-danger">{{ error.place }}</small>
              </div>
              <!-- INPUT END -->
              <p class="color1" v-else>
                <b>{{ order.status }}</b>
              </p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p><b>Buyer: </b></p>
            </div>
            <div class="col-7">
              <p>{{ order.first_name }} {{ order.last_name }}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <p><b>Contact #:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.contact_number }}</p>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <p><b>Order Type:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.order_type }}</p>
            </div>
          </div>

          <div class="row" v-if="order.order_type == 'For Delivery'">
            <div class="col-4">
              <p><b>Delivery Place:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.place }}</p>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <p><b>Rider Name</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.rider_first_name }}</p>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <p><b>Rider Contact</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.rider_contact_number }}</p>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <p><b>Notes:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.note }}</p>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <p><b>Payment:</b></p>
            </div>
            <div class="col-7">
              <p>₱{{ order.payment }}</p>
            </div>
          </div>
          <div
            class="mt-2 d-flex justify-content-between"
            v-if="user.id === order.user_id && order.status == 'Done'"
          >
            <button type="submit" class="btn btn1" data-bs-toggle="modal" data-bs-target="#refund">
              Ask for refund
            </button>
            <button
              class="btn btn1"
              data-bs-toggle="modal"
              data-bs-target="#rating"
              v-if="isShowRating && user.id !== order.product_by && order.status == 'Done'"
            >
              Rate this product
            </button>
          </div>
          <div class="mt-2 d-flex justify-content-end" v-if="user.id === order.product_by">
            <button type="submit" class="btn btn1" v-if="!isLoading">Update Status</button>
            <button type="button" class="btn btn1" v-if="isLoading">
              <span class="spinner-border spinner-border-sm"></span>
              Updating Status.....
            </button>
          </div>
          <div
            class="mt-2 d-flex justify-content-end"
            v-if="order.rider === 0 && order.status === 'For Delivery' && user.type === 'rider'"
          >
            <button type="button" @click="acceptDelivery" class="btn btn1" v-if="!isLoading">
              Accept Delivery
            </button>
            <button type="button" class="btn btn1" v-if="isLoading">
              <span class="spinner-border spinner-border-sm"></span>
              Accepting Delivery.....
            </button>
          </div>
          <div
            class="mt-2 d-flex justify-content-end"
            v-if="
              order.rider === user.id && order.status === 'For Delivery' && user.type === 'rider'
            "
          >
            <button type="button" @click="completeDelivery" class="btn btn1" v-if="!isLoading">
              Mark as delivered
            </button>
            <button type="button" class="btn btn1" v-if="isLoading">
              <span class="spinner-border spinner-border-sm"></span>
              Saving record.....
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- REFUND FORM MODAL START -->
    <div
      class="modal fade"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
      id="refund"
    >
      v
      <div class="modal-dialog">
        <form @submit.prevent="submit">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-white" id="addProductLabel">Refund Form</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <h5 class="color1 text-center">Ask For a Refund?</h5>
              <div class="row">
                <div class="col-4">
                  <p><b>Order ID:</b></p>
                </div>
                <div class="col-7">
                  <p>{{ order.order_id }}</p>
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <p><b>Product Name:</b></p>
                </div>
                <div class="col-7">
                  <p>{{ order.name }}</p>
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <p><b>Order quantity:</b></p>
                </div>
                <div class="col-7">
                  <p>{{ order.order_quantity }}</p>
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <p><b>Total Payment:</b></p>
                </div>
                <div class="col-7">
                  <p>₱{{ order.payment }}</p>
                </div>
              </div>

              <hr />
              <!-- INPUT START -->
              <div class="form">
                <label for="description"
                  >Please provide a detailed explanation of the reason(s) why you are asking for a
                  refund:</label
                >
                <textarea
                  class="form-control"
                  id="description"
                  style="height: 175px"
                  v-model="refund.reason"
                ></textarea>
              </div>
              <small v-if="error.reason" class="form-text text-danger">{{ error.reason }}</small>
              <!-- INPUT END -->
            </div>

            <div class="d-flex justify-content-center">
              <span class="text-danger text-center">{{ message }}</span>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

              <button type="submit" class="btn btn1" v-if="!isLoading">Submit</button>
              <button type="button" class="btn btn1" v-if="isLoading">
                <span class="spinner-border spinner-border-sm"></span>
                Submitting.....
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- REFUND FORM MODAL END -->
    <!-- REFUND FORM MODAL START -->
    <div
      class="modal fade"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
      id="rating"
    >
      v
      <div class="modal-dialog">
        <form @submit.prevent="submitRating">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-white" id="addProductLabel">Rating Form</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <h5 class="color1 text-center">Rate this product</h5>

              <div class="form-group mb-2">
                <label class="input-label" for="confirm_password">Rate(1-5)</label>
                <input
                  type="number"
                  class="form-control input"
                  id="rate"
                  aria-describedby="rate"
                  placeholder="Enter your rate to this product "
                  v-model="rate.rating"
                  min="1"
                  max="5"
                />
                <small v-if="error2.rating" class="form-text text-danger">{{
                  error2.rating
                }}</small>
              </div>

              <hr />
              <!-- INPUT START -->
              <div class="form">
                <label for="description">Comment:</label>
                <textarea
                  class="form-control"
                  id="description"
                  style="height: 175px"
                  v-model="rate.comment"
                ></textarea>
              </div>
              <!-- <small v-if="error.reason" class="form-text text-danger">{{ error.reason }}</small> -->
              <!-- INPUT END -->
            </div>

            <div class="d-flex justify-content-center">
              <span class="text-danger text-center">{{ message }}</span>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

              <button type="submit" class="btn btn1" v-if="!isLoading">Submit</button>
              <button type="button" class="btn btn1" v-if="isLoading">
                <span class="spinner-border spinner-border-sm"></span>
                Submitting.....
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- REFUND FORM MODAL END -->
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

const message = ref('')

const refund = ref({
  order: route.params.id,
  reason: '',
  status: 'Pending'
})

const rate = ref({
  order: route.params.id,
  rating: '',
  comment: ''
})

const error2 = ref({
  rating: ''
})
const isShowRating = ref(false)

const order = ref(computed(() => productStore.order))

const isLoggedIn = ref(computed(() => authStore.isLoggedIn))

const user = ref(computed(() => authStore.user))

const orderStatus = ref('')

// const order = ref({
//   quantity: cart.order_quantity,
//   type: 'To pick up',
//   place: '',
//   time: '',
//   note: '',
//   cart_id: route.params.id,
//   payment: 0,
//   status: 'New'
// })

const error = ref({
  reason: ''
})

const getOrder = () => {
  productStore
    .getOrder({ id: route.params.id })
    .then((data) => {
      console.log(data.ratings.length)
      orderStatus.value = data.order.status
      if (data.ratings.length > 0) {
        isShowRating.value = false
      } else {
        isShowRating.value = true
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const updateStatus = () => {
  isLoading.value = true
  productStore
    .updateStatus(route.params.id, { status: orderStatus.value })
    .then((data) => {
      isLoading.value = false
      router.push('/orders')
    })
    .catch((err) => {
      isLoading.value = false

      console.log('Error', err)
    })
}

const acceptDelivery = () => {
  isLoading.value = true
  productStore
    .acceptDelivery({ id: route.params.id })
    .then((data) => {
      isLoading.value = false
      router.push('/rider-dashboard')
    })
    .catch((err) => {
      isLoading.value = false

      console.log('Error', err)
    })
}
const completeDelivery = () => {
  isLoading.value = true
  productStore
    .completeDelivery({ id: route.params.id })
    .then((data) => {
      isLoading.value = false
      router.push('/rider-dashboard')
    })
    .catch((err) => {
      isLoading.value = false

      console.log('Error', err)
    })
}
const redirect = (id) => {
  router.push(`/product/detail/${id}`)
}

const submit = () => {
  /**Add validation here */
  let isValid = true

  error.value = {
    reason: ''
  }

  // add regex for tup email
  if (!refund.value.reason) {
    error.value.reason = 'Please enter image.'
    isValid = false
  }

  if (isValid && isLoading) {
    isLoading.value = true
    productStore
      .addRefund(refund.value)
      .then((data) => {
        isLoading.value = false
        if (data?.status === 200 || data?.status === 201) {
          refund.value = {
            order: route.params.id,
            reason: '',
            status: 'Pending'
          }

          $('#refund').modal('hide')
        } else {
          message.value = data.response.data.message
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}

const submitRating = () => {
  /**Add validation here */
  let isValid = true

  error2.value = {
    rating: ''
  }

  // add regex for tup email
  if (!rate.value.rating > 1) {
    error2.value.rating = 'Rate should be greater than 0.'
    isValid = false
  }

  if (isValid && isLoading) {
    isLoading.value = true
    productStore
      .addRating(rate.value)
      .then((data) => {
        console.log('data', data.data.status)
        isLoading.value = false
        if (data?.status === 200 || data?.status === 201) {
          rate.value = {
            order: route.params.id,
            rating: '',
            comment: ''
          }
          isShowRating.value = false
          $('#rating').modal('hide')
        } else {
          message.value = data.response.data.message
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}

onMounted(() => {
  getOrder()
})
</script>

<style scoped>
a {
  text-decoration: unset !important;
  color: unset;
}
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
.modal-header {
  background: var(--color1) !important;
  color: #fff !important;
}
</style>
