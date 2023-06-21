<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 pt-5 p-3">
        <div class="">
          <h3 class="color1">{{ store.store_name }} Products</h3>
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
            @change="getStoreProducts"
          />
          <button type="button" class="btn search-btn"><i class="bi bi-search"></i>Search</button>
        </div>
      </div>
      <div class="col-md-4 pt-5 p-3 d-flex justify-content-end" v-if="user?.type == 'store'">
        <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#addProduct">
          <i class="bi bi-bag-plus"></i>Add Product
        </button>
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
                @change="getStoreProducts"
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
                @change="getStoreProducts"
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
                @change="getStoreProducts"
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
                @change="getStoreProducts"
                id="meal"
              />
              <label class="form-check-label" for="meal">Meal </label>
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
                    @change="getStoreProducts"
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
                    @change="getStoreProducts"
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
                <div class="dropdown menu" v-if="user?.type == 'store'">
                  <i
                    class="bi bi-list"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  ></i>

                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#editProduct"
                        @click="setData(product)"
                        >Edit</span
                      >
                    </li>
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteProduct"
                        @click="setData(product)"
                        >Delete</span
                      >
                    </li>
                  </ul>
                </div>
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
                <div class="dropdown menu" v-if="user?.type == 'store'">
                  <i
                    class="bi bi-list"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  ></i>

                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#editProduct"
                        @click="setData(product)"
                        >Edit</span
                      >
                    </li>
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteProduct"
                        @click="setData(product)"
                        >Delete</span
                      >
                    </li>
                  </ul>
                </div>
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
                <div class="dropdown menu" v-if="user?.type == 'store'">
                  <i
                    class="bi bi-list"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  ></i>

                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#editProduct"
                        @click="setData(product)"
                        >Edit</span
                      >
                    </li>
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteProduct"
                        @click="setData(product)"
                        >Delete</span
                      >
                    </li>
                  </ul>
                </div>
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
                <div class="dropdown menu" v-if="user?.type == 'store'">
                  <i
                    class="bi bi-list"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  ></i>

                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#editProduct"
                        @click="setData(product)"
                        >Edit</span
                      >
                    </li>
                    <li>
                      <span
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteProduct"
                        @click="setData(product)"
                        >Delete</span
                      >
                    </li>
                  </ul>
                </div>
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

    <!-- ADD PRODUCT MODAL START -->
    <div
      class="modal fade"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
      id="addProduct"
    >
      <div class="modal-dialog modal-lg">
        <form @submit.prevent="submit">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-white" id="addProductLabel">Add New Product</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-5">
                  <div class="border d-flex justify-content-center">
                    <img
                      class="img-fluid meal-image"
                      :src="previewImage"
                      v-if="previewImage"
                      alt="meal-image"
                    />
                    <img
                      class="img-fluid meal-image"
                      v-else
                      src="../assets/icon-512.png"
                      alt="meal-image"
                    />
                  </div>
                  <div class="mb-2">
                    <label for="" class="form-label">Product Image</label>
                    <input
                      class="form-control form-control-sm"
                      type="file"
                      accept="image/jpeg, image/png, image/jpg"
                      @change="onFileSelected"
                    />
                    <small v-if="error.image" class="form-text text-danger">{{
                      error.image
                    }}</small>
                  </div>
                  <div class="row">
                    <!-- INPUT START -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label input" for="type">Product Type</label>
                      <select
                        class="form-select"
                        aria-label="Select product type "
                        v-model="product.type"
                      >
                        <option>Snacks</option>
                        <option>Drinks</option>
                        <option>Meal</option>
                      </select>
                      <small v-if="error.type" class="form-text text-danger">{{
                        error.type
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <!-- INPUT START -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label input" for="type">Product Size</label>
                      <select
                        class="form-select"
                        aria-label="Select product type "
                        v-model="product.size"
                      >
                        <option>Small</option>
                        <option>Medium</option>
                        <option>Large</option>
                        <option>Extra Large</option>
                      </select>
                      <small v-if="error.type" class="form-text text-danger">{{
                        error.type
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>
                </div>
                <div class="col-md-7">
                  <h5 class="color1">Product Details</h5>
                  <!-- INPUT START -->
                  <div class="form-group mb-2">
                    <label class="input-label" for="name">Product Name</label>
                    <input
                      type="text"
                      class="form-control input"
                      id="name"
                      aria-describedby="name"
                      placeholder="Enter Product Name"
                      v-model="product.name"
                    />
                    <small v-if="error.name" class="form-text text-danger">{{ error.name }}</small>
                  </div>
                  <!-- INPUT END -->
                  <div class="row">
                    <!-- INPUT START -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label" for="quantity">Quantity</label>
                      <input
                        type="number"
                        class="form-control input"
                        id="quantity"
                        aria-describedby="quantity"
                        placeholder="Enter Product Quantity"
                        v-model="product.quantity"
                      />
                      <small v-if="error.quantity" class="form-text text-danger">{{
                        error.quantity
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label" for="price">Price</label>
                      <input
                        type="number"
                        class="form-control input"
                        id="price"
                        aria-describedby="price"
                        placeholder="Enter Product Price"
                        step="0.01"
                        v-model="product.price"
                      />
                      <small v-if="error.price" class="form-text text-danger">{{
                        error.price
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>

                  <!-- INPUT START -->
                  <div class="form-floating">
                    <textarea
                      class="form-control"
                      placeholder="Enter product description"
                      id="description"
                      style="height: 175px"
                      v-model="product.description"
                    ></textarea>
                    <label for="description">Description</label>
                  </div>
                  <small v-if="error.description" class="form-text text-danger">{{
                    error.description
                  }}</small>
                  <!-- INPUT END -->
                </div>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

              <button type="submit" class="btn btn1" v-if="!isLoading">Save Record</button>
              <button type="button" class="btn btn1" v-if="isLoading">
                <span class="spinner-border spinner-border-sm"></span>
                Saving Record.....
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- ADD PRODUCT MODAL END -->

    <!-- EDIT PRODUCT MODAL START -->
    <div
      class="modal fade"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
      id="editProduct"
    >
      <div class="modal-dialog modal-lg">
        <form @submit.prevent="submitEdit">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-white" id="addProductLabel">Edit Product</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-5">
                  <div class="border d-flex justify-content-center">
                    <img
                      class="img-fluid meal-image"
                      :src="previewImageEdit"
                      v-if="previewImageEdit"
                      alt="meal-image"
                    />
                    <img
                      class="img-fluid meal-image"
                      v-else
                      :src="productEdit.image"
                      alt="meal-image"
                    />
                  </div>
                  <div class="mb-2">
                    <label for="" class="form-label">Product Image</label>
                    <input
                      class="form-control form-control-sm"
                      type="file"
                      accept="image/jpeg, image/png, image/jpg"
                      @change="onFileSelectedEdit"
                    />
                    <small v-if="error.image" class="form-text text-danger">{{
                      error.image
                    }}</small>
                  </div>
                  <div class="row">
                    <!-- INPUT START -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label input" for="type">Product Type</label>
                      <select
                        class="form-select"
                        aria-label="Select product type "
                        v-model="productEdit.type"
                      >
                        <option>Snacks</option>
                        <option>Drinks</option>
                        <option>Meal</option>
                      </select>
                      <small v-if="error.type" class="form-text text-danger">{{
                        error.type
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <!-- INPUT START -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label input" for="type">Product Size</label>
                      <select
                        class="form-select"
                        aria-label="Select product type "
                        v-model="productEdit.size"
                      >
                        <option>Small</option>
                        <option>Medium</option>
                        <option>Large</option>
                        <option>Extra Large</option>
                      </select>
                      <small v-if="error.type" class="form-text text-danger">{{
                        error.type
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>
                </div>
                <div class="col-md-7">
                  <h5 class="color1">Product Details</h5>
                  <!-- INPUT START -->
                  <div class="form-group mb-2">
                    <label class="input-label" for="name">Product Name</label>
                    <input
                      type="text"
                      class="form-control input"
                      id="name"
                      aria-describedby="name"
                      placeholder="Enter Product Name"
                      v-model="productEdit.name"
                    />
                    <small v-if="error.name" class="form-text text-danger">{{ error.name }}</small>
                  </div>
                  <!-- INPUT END -->
                  <div class="row">
                    <!-- INPUT START -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label" for="quantity">Quantity</label>
                      <input
                        type="number"
                        class="form-control input"
                        id="quantity"
                        aria-describedby="quantity"
                        placeholder="Enter Product Quantity"
                        v-model="productEdit.quantity"
                      />
                      <small v-if="error.quantity" class="form-text text-danger">{{
                        error.quantity
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <div class="form-group mb-2 col-md-6">
                      <label class="input-label" for="price">Price</label>
                      <input
                        type="number"
                        class="form-control input"
                        id="price"
                        aria-describedby="price"
                        placeholder="Enter Product Price"
                        step="0.01"
                        v-model="productEdit.price"
                      />
                      <small v-if="error.price" class="form-text text-danger">{{
                        error.price
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>

                  <!-- INPUT START -->
                  <div class="form-floating">
                    <textarea
                      class="form-control"
                      placeholder="Enter product description"
                      id="description"
                      style="height: 175px"
                      v-model="productEdit.description"
                    ></textarea>
                    <label for="description">Description</label>
                  </div>
                  <small v-if="error.description" class="form-text text-danger">{{
                    error.description
                  }}</small>
                  <!-- INPUT END -->
                </div>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

              <button type="submit" class="btn btn1" v-if="!isLoading">Save Record</button>
              <button type="button" class="btn btn1" v-if="isLoading">
                <span class="spinner-border spinner-border-sm"></span>
                Saving Record.....
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- EDIT PRODUCT MODAL END -->
    <!-- DELETE PRODUCT START -->
    <div
      class="modal fade"
      id="deleteProduct"
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
                <h4 class="justify-content-center d-flex fw-semibold">Delete Product</h4>
                <p class="justify-content-center d-flex text-black-50 mt-3">
                  Are you sure you want to delete this product?
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
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useProductStore } from '../stores/product'
import { useAuthenticationStore } from '../stores/authenticaton'
import { useUserStore } from '../stores/user'
import Pagination from '../components/Pagination.vue'
import { useRoute, useRouter } from 'vue-router'

const productStore = useProductStore()
const authStore = useAuthenticationStore()
// const storeId = ref(authStore?.user?.id)
const userStore = useUserStore()
const user = ref(authStore.user)

const route = useRoute()

const router = useRouter()

const isLoading = ref(false)

const showError = ref(false)

const products = ref(computed(() => productStore.store_products))
const store = ref(computed(() => userStore.store))

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

const previewImage = ref('')
const onFileSelected = (event) => {
  product.value.image = event.target.files[0]
  previewImage.value = URL.createObjectURL(event.target.files[0])
}

const previewImageEdit = ref('')
const onFileSelectedEdit = (event) => {
  productEdit.value.image = event.target.files[0]
  previewImageEdit.value = URL.createObjectURL(event.target.files[0])
}

const filter = ref({
  type: 'All',
  min_price: 0,
  max_price: 0,
  search: ''
})

const product = ref({
  image: '',
  name: '',
  price: 0,
  quantity: 0,
  type: 'Snacks',
  description: '',
  size: 'Small'
})

const error = ref({
  image: '',
  name: '',
  price: 0,
  quantity: 0,
  type: '',
  description: ''
})

const productId = ref('')
const productEdit = ref({
  image: '',
  name: '',
  price: 0,
  quantity: 0,
  type: 'Snacks',
  description: '',
  size: 'Small'
})

const setData = (data) => {
  error.value = {
    image: '',
    name: '',
    price: 0,
    quantity: 0,
    type: '',
    description: ''
  }
  productId.value = data.product_id
  let productData = { ...data }
  productEdit.value = {
    image: productData.image,
    name: productData.name,
    price: productData.price,
    quantity: productData.quantity,
    type: productData.type,
    description: productData.description,
    size: productData.size
  }
}

const submit = () => {
  /**Add validation here */
  showError.value = false

  let isValid = true

  error.value = {
    image: '',
    name: '',
    price: 0,
    quantity: 0,
    type: '',
    description: ''
  }

  // add regex for tup email
  if (!product.value.image) {
    error.value.image = 'Please enter image.'
    isValid = false
  }

  if (product.value.name.length == 0) {
    error.value.name = 'Please enter product name.'
    isValid = false
  }

  if (product.value.name <= 0) {
    error.value.price = 'Please enter product price.'
    isValid = false
  }

  if (product.value.quantity <= 0) {
    error.value.quantity = 'Please enter product quantity.'
    isValid = false
  }

  if (product.value.type.length == 0) {
    error.value.type = 'Please select  product type.'
    isValid = false
  }

  if (product.value.description.length == 0) {
    error.value.description = 'Please enter product description.'
    isValid = false
  }

  if (isValid && isLoading) {
    isLoading.value = true
    productStore
      .addProduct(product.value)
      .then((data) => {
        isLoading.value = false
        if (data?.status === 200 || data?.status === 201) {
          product.value = {
            image: '',
            name: '',
            price: 0,
            quantity: 0,
            type: 'Snacks',
            description: '',
            size: 'Small'
          }
          previewImage.value = null
          $('#addProduct').modal('hide')
          setPagination()
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}

const submitEdit = () => {
  /**Add validation here */
  showError.value = false

  let isValid = true

  error.value = {
    image: '',
    name: '',
    price: 0,
    quantity: 0,
    type: '',
    description: ''
  }

  // add regex for tup email
  // if (!product.value.image) {
  //   error.value.image = 'Please enter image.'
  //   isValid = false
  // }

  if (productEdit.value.name.length == 0) {
    error.value.name = 'Please enter product name.'
    isValid = false
  }

  if (productEdit.value.name <= 0) {
    error.value.price = 'Please enter product price.'
    isValid = false
  }

  if (productEdit.value.quantity <= 0) {
    error.value.quantity = 'Please enter product quantity.'
    isValid = false
  }

  if (productEdit.value.type.length == 0) {
    error.value.type = 'Please select  product type.'
    isValid = false
  }

  if (productEdit.value.description.length == 0) {
    error.value.description = 'Please enter product description.'
    isValid = false
  }

  if (isValid && isLoading) {
    isLoading.value = true
    // if (previewImageEdit.value) {
    //   productEdit.value.image = previewImageEdit.value
    // }
    productStore
      .updateProduct(productId.value, productEdit.value)
      .then((data) => {
        isLoading.value = false

        if (data?.code === 'ERR_BAD_REQUEST') {
          showError.value = true
        } else {
          $('#editProduct').modal('hide')
          setPagination()
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}

const submitDelete = () => {
  /**Add validation here */
  showError.value = false

  // if (!isLoading) {

  isLoading.value = true
  productStore
    .deleteProduct({ id: productId.value })
    .then((data) => {
      isLoading.value = false

      if (data?.code === 'ERR_BAD_REQUEST') {
        showError.value = true
      } else {
        $('#deleteProduct').modal('hide')
        setPagination()
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}
// }

// const setPagination = () => {
//   productsDataHolder.value = products.value
//   productsDataDisplay.value = productsDataHolder.value.slice(0, 8)

//   pagination.value.total = productsDataHolder.value.length
//   pagination.value.totalPages = parseInt(productsDataHolder.value.length / 8)

//   if (productsDataHolder.value.length % 8 !== 0) {
//     pagination.value.totalPages += 1
//   }
//   getPage(1)
// }
const snacks = ref([])
const drinks = ref([])
const meal = ref([])
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

const getStoreProducts = () => {
  productStore
    .getStoreProducts({ id: route.params.id, filter: filter.value })
    .then((data) => {
      // productsDataHolder.value = products.value
      // productsDataDisplay.value = productsDataHolder.value.slice(0, 5)

      // pagination.value.total = productsDataHolder.value.length
      // pagination.value.totalPages = parseInt(productsDataHolder.value.length / 5)

      // if (productsDataHolder.value.length % 5 !== 0) {
      //   pagination.value.totalPages += 1
      // }

      // getPage(1)
      setPagination()
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const redirect = (id) => {
  router.push(`/product/detail/${id}`)
}

const getStore = () => {
  userStore
    .getStore({
      id: route.params.id
    })
    .then((data) => {})
    .catch((err) => {
      console.log('Error', err)
    })
}
onMounted(() => {
  getStoreProducts()
  getStore()
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
</style>
