<template>
  <div class="container py-lg-5 p-3">
    <h2 class="color1">Riders</h2>

    <div class="mt-4 row">
      <div class="input-group search-container col-md-6">
        <input
          type="search"
          class="form-control search-input"
          placeholder="Search"
          aria-label="Search"
          aria-describedby="search-addon"
          v-model="filter.search"
          @change="getAllUsers"
        />
        <button type="button" class="btn search-btn"><i class="bi bi-search"></i>Search</button>
      </div>
      <div class="col-md-6 d-flex justify-content-end mt-2">
        <button class="btn add-user-btn" data-bs-toggle="modal" data-bs-target="#addUserAccount">
          <i class="bi bi-person-bounding-box"></i>Add Rider
        </button>
      </div>
    </div>
    <div class="mt-3 table-container">
      <table class="table">
        <thead class="">
          <tr class="pt-5">
            <th>Full name</th>
            <th>Email</th>
            <th>Contact #</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in usersDataDisplay" :key="user.id">
            <td>
              <b>{{ user.first_name }} {{ user.last_name }}</b>
            </td>
            <td>{{ user.email }}</td>
            <td>{{ user.contact_number }}</td>
            <td>{{ user.is_active == 1 ? 'Active' : 'Not Active' }}</td>

            <td class="d-flex justify-content-around">
              <button
                class="btn btn-outline-success mx-1 btn-sm"
                v-if="!user.is_active"
                data-bs-toggle="modal"
                data-bs-target="#activateAccount"
                @click="setData(user)"
              >
                Activate
              </button>
              <button
                v-else
                class="btn btn-outline-danger mr-1 btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#deactivateAccount"
                @click="setData(user)"
              >
                Deactivate
              </button>
              <button
                class="btn btn-outline-primary mr-1 btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editUserAccount"
                @click="setData(user)"
              >
                Edit
              </button>
              <button
                class="btn btn-outline-danger btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#deleteAccount"
                @click="setData(user)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

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
            v-if="pagination.total >= 8"
          >
          </Pagination>
        </div>
      </div>
      <!-- PAGINATION END -->
    </div>

    <!-- DEACTIVATE ACCOUNT START -->
    <div
      class="modal fade"
      id="deactivateAccount"
      tabindex="-1"
      role="dialog"
      aria-labelledby="delete"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <form @submit.prevent="submitDeactivateAccount">
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
                <h4 class="justify-content-center d-flex fw-semibold">Deactivate Account</h4>
                <p class="justify-content-center d-flex text-black-50 mt-3">
                  Are you sure you want to deactivate this account?
                </p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger px-4" v-show="isLoading">
                <i class="bi bi-person-fill-slash"></i>
                <span class="spinner-border spinner-border-sm"></span>Deactivating.....
              </button>
              <button type="submit" class="btn btn-danger px-4" v-show="!isLoading">
                <i class="bi bi-person-x-fill"></i>Deactivate
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- DEACTIVATE ACCOUNT END -->

    <!-- ACTIVATE ACCOUNT START -->
    <div
      class="modal fade"
      id="activateAccount"
      tabindex="-1"
      role="dialog"
      aria-labelledby="delete"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <form @submit.prevent="submitActivateAccount">
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
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
              <i class="bi bi-person-check text-success justify-content-center d-flex"></i>
              <div class="col content-modal">
                <h4 class="justify-content-center d-flex fw-semibold">Activate Account</h4>
                <p class="justify-content-center d-flex text-black-50 mt-3">
                  Are you sure you want to activate this account?
                </p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success px-4" v-show="isLoading">
                <i class="bi bi-person-check-fill"></i>
                <span class="spinner-border spinner-border-sm"></span>Activating.....
              </button>
              <button type="submit" class="btn btn-success px-4" v-show="!isLoading">
                <i class="bi bi-person-check-fill"></i>Activate
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- ACTIVATE ACCOUNT END -->
    <!-- EDIT ACCOUNT START -->
    <div
      class="modal fade"
      id="addUserAccount"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addUserAccount"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <form @submit.prevent="submitRegisterUser">
          <div class="modal-content">
            <div class="modal-header bg-success text-white modal-header-edit">
              <h5 class="modal-title text-white" id="exampleModalLabel">Add Admin</h5>
              <button
                type="button"
                class="btn-close text-light"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mx-2">
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="first_name">First Name</label>
                      <input
                        type="text"
                        class="form-control input"
                        id="first_name"
                        aria-describedby="first_name"
                        placeholder="Enter first name"
                        v-model="userData.first_name"
                      />
                      <small v-if="error.first_name" class="form-text text-danger">{{
                        error.first_name
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="last_name">Last Name</label>
                      <input
                        type="text"
                        class="form-control input"
                        id="last_name"
                        aria-describedby="last_name"
                        placeholder="Enter last name"
                        v-model="userData.last_name"
                      />
                      <small v-if="error.last_name" class="form-text text-danger">{{
                        error.last_name
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="contact_number">Contact #</label>
                      <input
                        type="text"
                        class="form-control input"
                        id="contact_number"
                        aria-describedby="contact_number"
                        placeholder="Enter contact number"
                        v-model="userData.contact_number"
                      />
                      <small v-if="error.contact_number" class="form-text text-danger">{{
                        error.contact_number
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mx-2">
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <div class="d-flex justify-content-between">
                        <label class="input-label" for="email">Email</label>
                      </div>
                      <input
                        type="email"
                        class="form-control input"
                        id="email"
                        aria-describedby="email"
                        placeholder="Enter email"
                        v-model="userData.email"
                      />
                      <small v-if="error.email" class="form-text text-danger">{{
                        error.email
                      }}</small>
                    </div>
                    <!-- INPUT END -->

                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="password">Password</label>
                      <input
                        type="password"
                        class="form-control input"
                        id="password"
                        aria-describedby="password"
                        placeholder="Enter password"
                        v-model="userData.password"
                      />
                      <small v-if="error.password" class="form-text text-danger">{{
                        error.password
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="confirm_password">Confirm Password</label>
                      <input
                        type="password"
                        class="form-control input"
                        id="confirm_password"
                        aria-describedby="confirm_password"
                        placeholder="Enter password "
                        v-model="userData.password_confirmation"
                      />
                      <small v-if="error.password_confirmation" class="form-text text-danger">{{
                        error.password_confirmation
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn1 px-4" v-show="isLoading">
                <span class="spinner-border spinner-border-sm"></span>Submitting.....
              </button>
              <button type="submit" class="btn btn1 px-4" v-show="!isLoading">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- EDIT ACCOUNT END -->
    <!-- EDIT ACCOUNT START -->
    <div
      class="modal fade"
      id="editUserAccount"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editUserAccount"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <form @submit.prevent="submitUpdateUser">
          <div class="modal-content">
            <div class="modal-header bg-success text-white modal-header-edit">
              <h5 class="modal-title text-white" id="exampleModalLabel">Edit User</h5>
              <button
                type="button"
                class="btn-close text-light"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mx-2">
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="first_name">First Name</label>
                      <input
                        type="text"
                        class="form-control input"
                        id="first_name"
                        aria-describedby="first_name"
                        placeholder="Enter first name"
                        v-model="userDataEdit.first_name"
                      />
                      <small v-if="error.first_name" class="form-text text-danger">{{
                        error.first_name
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="last_name">Last Name</label>
                      <input
                        type="text"
                        class="form-control input"
                        id="last_name"
                        aria-describedby="last_name"
                        placeholder="Enter last name"
                        v-model="userDataEdit.last_name"
                      />
                      <small v-if="error.last_name" class="form-text text-danger">{{
                        error.last_name
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mx-2">
                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <div class="d-flex justify-content-between">
                        <label class="input-label" for="email">Email</label>
                      </div>
                      <input
                        type="email"
                        class="form-control input"
                        id="email"
                        aria-describedby="email"
                        placeholder="Enter email"
                        v-model="userDataEdit.email"
                      />
                      <small v-if="error.email" class="form-text text-danger">{{
                        error.email
                      }}</small>
                    </div>
                    <!-- INPUT END -->

                    <!-- INPUT START -->
                    <div class="form-group mb-2">
                      <label class="input-label" for="contact_number">Contact #</label>
                      <input
                        type="text"
                        class="form-control input"
                        id="contact_number"
                        aria-describedby="contact_number"
                        placeholder="Enter contact number"
                        v-model="userDataEdit.contact_number"
                      />
                      <small v-if="error.contact_number" class="form-text text-danger">{{
                        error.contact_number
                      }}</small>
                    </div>
                    <!-- INPUT END -->
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn1 px-4" v-show="isLoading">
                <span class="spinner-border spinner-border-sm"></span>Submitting.....
              </button>
              <button type="submit" class="btn btn1 px-4" v-show="!isLoading">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- EDIT ACCOUNT END -->
    <!-- DELETE ACCOUNT START -->
    <div
      class="modal fade"
      id="deleteAccount"
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
                <h4 class="justify-content-center d-flex fw-semibold">Delete Account</h4>
                <p class="justify-content-center d-flex text-black-50 mt-3">
                  Are you sure you want to delete this account?
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
    <!-- DELETE ACCOUNT END -->
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useUserStore } from '../stores/user'
import { useAuthenticationStore } from '../stores/authenticaton'
import Pagination from '../components/Pagination.vue'

import { useRouter } from 'vue-router'

const authStore = useAuthenticationStore()
const userStore = useUserStore()

const storeId = ref(authStore?.user?.id)
// const route = useRoute()

const router = useRouter()

const isLoading = ref(false)

const showError = ref(false)

const users = ref(computed(() => userStore.users))

const usersDataHolder = ref([])
const usersDataDisplay = ref([])

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

  usersDataDisplay.value = usersDataHolder.value.slice(val1 - 1, val2)

  if (pagination.value.totalEvents < 8) {
    pagination.value.secondVal = pagination.value.total
  } else if (pagination.value.firstVal + 8 > pagination.value.total) {
    pagination.value.secondVal = pagination.value.total
  }
}

const setPagination = () => {
  usersDataHolder.value = users.value
  usersDataDisplay.value = usersDataHolder.value.slice(0, 8)

  pagination.value.total = usersDataHolder.value.length
  pagination.value.totalPages = parseInt(usersDataHolder.value.length / 8)

  if (usersDataHolder.value.length % 8 !== 0) {
    pagination.value.totalPages += 1
  }
  getPage(1)
}

const user_id = ref('')

const setData = (data) => {
  user_id.value = data.id
  userDataEdit.value = {
    ...data
  }
  error.value = {
    first_name: '',
    last_name: '',
    store_name: '',
    contact_number: '',
    email: '',
    password_confirmation: '',
    password: ''
  }
}

const filter = ref({
  type: 'rider',
  limit: 0,
  // status: 0,
  search: ''
})

const userDataEdit = ref({
  first_name: '',
  last_name: '',
  store_name: '',
  contact_number: '',
  email: '',
  store_name: ''
})

const error = ref({
  first_name: '',
  last_name: '',
  store_name: '',
  contact_number: '',
  email: '',
  password_confirmation: '',
  password: ''
})

const userData = ref({
  first_name: '',
  last_name: '',
  store_name: '',
  contact_number: '',
  email: '',
  type: 'rider',
  store_name: '',
  password_confirmation: '',
  password: ''
})

const submitRegisterUser = () => {
  /**Add validation here */

  showError.value = false

  let isValid = true

  error.value = {
    first_name: '',
    last_name: '',
    store_name: '',
    contact_number: '',
    email: '',
    token: '',
    password_confirmation: '',
    password: ''
  }

  // add regex for tup email
  if (userData.value.email.length == 0) {
    error.value.email = 'Please enter your email.'
    isValid = false
  }

  if (userData.value.first_name.length == 0) {
    error.value.first_name = 'Please enter your first name.'
    isValid = false
  }

  if (userData.value.last_name.length == 0) {
    error.value.last_name = 'Please enter your last name.'
    isValid = false
  }

  if (userData.value.contact_number.length == 0) {
    error.value.contact_number = 'Please enter your contact number.'
    isValid = false
  }

  if (userData.value.password.length == 0) {
    error.value.password = 'Please enter password.'
    isValid = false
  }

  if (userData.value.password.length > 0) {
    if (userData.value.password != userData.value.password_confirmation) {
      error.value.password = 'Please password not match.'
      isValid = false
    } else {
      if (userData.value.password.length < 8) {
        error.value.password = 'Password length should be at least 8 character.'
        isValid = false
      }
    }
  }

  if (isValid && isLoading) {
    isLoading.value = true

    userStore
      .registerUserAccount(userData.value)
      .then((data) => {
        isLoading.value = false

        if (data?.code === 'ERR_BAD_REQUEST') {
          showError.value = true
        } else {
          $('#addUserAccount').modal('hide')
          setPagination()
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}

const submitDeactivateAccount = () => {
  /**Add validation here */

  isLoading.value = true
  userStore
    .deactivateUser({ id: user_id.value })
    .then((data) => {
      isLoading.value = false

      if (data?.code === 'ERR_BAD_REQUEST') {
        showError.value = true
      } else {
        $('#deactivateAccount').modal('hide')
        setPagination()
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const submitActivateAccount = () => {
  /**Add validation here */

  isLoading.value = true
  userStore
    .activateUser({ id: user_id.value })
    .then((data) => {
      isLoading.value = false

      if (data?.code === 'ERR_BAD_REQUEST') {
        showError.value = true
      } else {
        $('#activateAccount').modal('hide')
        setPagination()
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const submitUpdateUser = () => {
  /**Add validation here */

  showError.value = false

  let isValid = true

  error.value = {
    first_name: '',
    last_name: '',
    store_name: '',
    contact_number: '',
    email: ''
  }

  // add regex for tup email
  if (userDataEdit.value.email.length == 0) {
    error.value.email = 'Please enter your email.'
    isValid = false
  }

  if (userDataEdit.value.first_name.length == 0) {
    error.value.first_name = 'Please enter your first name.'
    isValid = false
  }

  if (userDataEdit.value.last_name.length == 0) {
    error.value.last_name = 'Please enter your last name.'
    isValid = false
  }

  if (userDataEdit.value.contact_number.length == 0) {
    error.value.contact_number = 'Please enter your contact number.'
    isValid = false
  }

  if (isValid && isLoading) {
    isLoading.value = true
    userStore
      .updateUser(userDataEdit.value)
      .then((data) => {
        isLoading.value = false

        if (data?.code === 'ERR_BAD_REQUEST') {
          showError.value = true
        } else {
          $('#editUserAccount').modal('hide')
          setPagination()
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}
const getAllUsers = () => {
  userStore
    .getAllUsers(filter.value)
    .then((data) => {
      setPagination()
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

const submitDelete = () => {
  /**Add validation here */

  // if (!isLoading) {

  isLoading.value = true
  userStore
    .deleteUser({ id: user_id.value })
    .then((data) => {
      isLoading.value = false
      if (data?.status === 200 || data?.status === 201) {
        $('#deleteAccount').modal('hide')
        setPagination()
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

onMounted(() => {
  getAllUsers()
})
</script>

<style scoped>
.table-container {
  max-height: 300px !important; /* Set the desired max height for the table */
  overflow-y: auto !important; /* Add scroll when the table overflows */
}

/* Optional: Adjust the table styles as needed */
.table {
  /* Table styles */
}
.color1 {
  color: var(--color1);
}
.search-container {
  position: relative;
  width: 500px;
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
.add-user-btn {
  font-size: 18px;
  background: var(--color1);
  color: #fff;
  width: 150px;
  padding: 7px;
}
.add-user-btn:hover {
  font-size: 18px;
  background: var(--color3);
  color: #fff;
}
.bi-person-check {
  font-size: 110px;
  width: fit-content;
  margin-left: 35%;
  padding: 10px;
  margin-top: -15%;
  margin-bottom: 5%;
  background-color: #fff;
  border: 3px solid #9fa5a1;
  border-radius: 50%;
}
.modal-header-edit {
  background: var(--color1) !important;
  color: #fff !important;
}
.input {
  background: var(--color4);
  padding: 7px;
}
.input-label {
  font-size: 16px;
  font-weight: 600;
}
</style>
