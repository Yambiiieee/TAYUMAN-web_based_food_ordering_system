<template>
  <div class="container my-4">
    <div class="row">
      <div class="col-md-7">
        <div class="img-container p-2">
          <img class="img-fluid" :src="product.image" alt="meal-image" />
        </div>
        <div class="px-3 pb-2">
          <h4 class="color1 text-center">{{ product.name }}</h4>
        </div>
        <div class="px-3">
          <p>{{ product.quantity }} Available</p>
        </div>
        <div class="px-3 d-flex justify-content-between">
          <p class="price">₱{{ product.price }}</p>
          <router-link :to="`/store/${product.product_by}`">{{ product.store_name }} </router-link>
        </div>
        <div class="px-3 rating" v-if="avgRating !== 0">
          <span class="">Ratings: </span>
          <div class="star-rating">
            <span
              v-for="star in avgRating"
              :key="star"
              :class="starClass(star)"
              @mousemove="updateHoverRating(star)"
              @mouseleave="resetHoverRating"
              @click="setRating(star)"
            >
              ★
            </span>
          </div>
        </div>
        <form @submit.prevent="addToCart">
          <div class="quantiy-container px-3">
            <p>Quantity:</p>
            <div class="input-group mb-3 quantiy-input-container">
              <div class="input-group-prepend">
                <span class="input-group-text quantity-btn" @click="minus">-</span>
              </div>
              <input type="text" class="form-control mx-1 quantity-input" v-model="orderQuantity" />
              <div class="input-group-prepend">
                <span class="input-group-text quantity-btn" @click="add">+</span>
              </div>
            </div>
          </div>
          <div class="mt-2 px-3">
            <div class="">
              <span class="text-danger" v-if="orderQuantityError"
                >Please enter valid order quantity</span
              >
            </div>
            <button class="btn add-to-cart-btn mb-2" v-if="isLoggedIn == true && isLoading == true">
              <i class="bi bi-cart"></i>
              <span class="spinner-border spinner-border-sm"></span>Adding To cart...
            </button>
            <button class="btn add-to-cart-btn mb-2" v-else-if="isLoggedIn">
              <i class="bi bi-cart"></i> Add To cart
            </button>

            <router-link to="/login" class="btn add-to-cart-btn mb-2" v-else>
              <i class="bi bi-cart"></i> Add To cart</router-link
            >
          </div>
        </form>
      </div>
      <div class="col-md-5 card py-4 px-4">
        <div class="">
          <p><b>Size:</b> {{ product.size }}</p>
          <p><b>Description:</b></p>
          <p class="px-2">
            {{ product.description }}
          </p>
        </div>
      </div>
    </div>
    <div class="row d-flex mt-4" v-if="avgRating !== 0">
      <div class="col-md-12 py-2">
        <div class="card py-3">
          <h2 class="color1 mx-5">Ratings : {{ avgRating }}</h2>
          <!-- COMMMENT START -->
          <div class="py-2" tr v-for="rating in product_rating" :key="rating.id">
            <div class="px-5 py-0">
              <h6 class="m-0">
                <b> {{ rating.first_name }} {{ rating.last_name }}</b>
              </h6>
              <div class="star-rating">
                <span
                  v-for="star in rating.rating"
                  :key="star"
                  :class="starClass(star)"
                  @mousemove="updateHoverRating(star)"
                  @mouseleave="resetHoverRating"
                  @click="setRating(star)"
                >
                  ★
                </span>
              </div>
            </div>
            <div class="px-5">
              <p>
                {{ rating.comment }}
              </p>
              <hr />
            </div>
          </div>
          <!-- COMMENT END -->
        </div>
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

const showError = ref(false)

const product = ref(computed(() => productStore.product))

const product_rating = ref(computed(() => productStore.product_rating))

const isLoggedIn = ref(computed(() => authStore.isLoggedIn))

const avgRating = ref(0)

const add = () => {
  orderQuantity.value += 1
}

const minus = () => {
  if (orderQuantity.value <= 0) {
    orderQuantity.value = 0
  } else {
    orderQuantity.value -= 1
  }
}

const getStoreProduct = () => {
  productStore
    .getStoreProduct({ id: route.params.id })
    .then((data) => {
      avgRating.value = 0
      console.log(data.ratings)
      for (let i = 0; i < data.ratings.length; i++) {
        avgRating.value += data.ratings[i].rating
      }
      console.log('Rating', avgRating.value)
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const addToCart = () => {
  /**Validation */
  orderQuantityError.value = true
  if (orderQuantity.value <= 0 || orderQuantity.value > product.value.quantity) {
    orderQuantityError.value = true
  } else {
    /**Call add to cart */
    orderQuantityError.value = false
    isLoading.value = true

    productStore
      .addToCart({ quantity: orderQuantity.value, id: route.params.id })
      .then((data) => {
        isLoading.value = false
        if (data?.status === 200 || data?.status === 201) {
          $('#addToCart').modal('show')
          orderQuantity.value = 0
        }
      })
      .catch((err) => {
        isLoading.value = false

        console.log('Error', err)
      })
  }
}

const rating = ref(4.5)
const hoverRating = ref(null)
const stars = Array(5)
  .fill()
  .map((_, index) => index + 1)

const starClass = (star) => {
  if (star <= rating.value) {
    return 'filled'
  } else if (true && star - 0.5 <= rating.value && star - 0.5 > rating.value - 1) {
    return 'half-filled'
  } else {
    return ''
  }
}

const updateHoverRating = (star) => {
  if (true) {
    hoverRating.value = star
  }
}

const resetHoverRating = () => {
  hoverRating.value = null
}

const setRating = (newRating) => {
  if (props.allowHalf && hoverRating.value === newRating) {
    rating.value = newRating - 0.5
  } else {
    rating.value = newRating
  }

  emit('rating-updated', rating.value)
}

const redirect = (id) => {
  router.push(`/product/detail/${id}`)
}

onMounted(() => {
  getStoreProduct()
})
</script>

<style scoped>
.rating {
  display: flex;
  align-items: center;
  justify-content: start;
}
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

.star-rating {
  margin: 0;
  padding: 0;
  display: inline-block;
  font-size: 24px;
  color: #f3e74b;
  cursor: pointer;
}

.star-rating span {
  display: inline-block;
}

.star-rating span.filled,
.star-rating span.half-filled {
  color: #ffda44;
}
</style>
