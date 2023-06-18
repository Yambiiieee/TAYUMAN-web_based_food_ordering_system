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
          <p><b>Description:</b></p>
          <p class="px-2">
            {{ order.description }}
          </p>
        </div>
      </div>
      <div class="col-md-5 card py-4 px-4">
        <h3 class="color1">Refund Order Details</h3>
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
              <p><b>Refund Status:</b></p>
            </div>
            <div class="col-7">
              <!-- INPUT START -->
              <div class="form-group mb-2" v-if="user.id === order.product_by">
                <select class="form-select" aria-label="Select product type " v-model="orderStatus">
                  <option>Pending</option>
                  <option>Accepted</option>
                  <option>Done</option>
                  <option>Declined</option>
                </select>
                <small v-if="error.place" class="form-text text-danger">{{ error.place }}</small>
              </div>
              <!-- INPUT END -->
              <p class="color1" v-else>
                <b>{{ order.refund_status }}</b>
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
              <p><b>Reason:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.reason }}</p>
            </div>
          </div>

          <div class="row">
            <div class="col-4">
              <p><b>Payment:</b></p>
            </div>
            <div class="col-7">
              <p>{{ order.payment }}</p>
            </div>
          </div>

          <div class="mt-2 d-flex justify-content-end" v-if="user.id === order.product_by">
            <button type="submit" class="btn btn1" v-if="!isLoading">Update Status</button>
            <button type="button" class="btn btn1" v-if="isLoading">
              <span class="spinner-border spinner-border-sm"></span>
              Updating Status.....
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
                  <p>{{ order.payment }}</p>
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

const order = ref(computed(() => productStore.refund_order))

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

const getRefundOrder = () => {
  productStore
    .getRefundOrder({ id: route.params.id })
    .then((data) => {
     
      orderStatus.value = data.refund_status
    })
    .catch((err) => {
     
      console.log('Error', err)
    })
}

const updateStatus = () => {
  isLoading.value = true
  productStore
    .updateRefundStatus(route.params.id, { status: orderStatus.value })
    .then((data) => {
      isLoading.value = false
      
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

onMounted(() => {
  getRefundOrder()
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
