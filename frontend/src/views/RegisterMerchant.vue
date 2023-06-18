<template>
  <div class="bg-img">
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="col-md-10 py-5 card my-5 px-4">
          <div class="px-5">
            <h4 class="text-center">Register to Tayuman</h4>
          </div>
          <form
            @submit.prevent="submitRegister"
            class="mx-2 mt-2 row d-flex justify-content-between"
          >
            <div class="col-md-5">
              <!-- INPUT START -->
              <div class="form-group mb-2">
                <label class="input-label" for="id_number">Store Name</label>
                <input
                  type="text"
                  class="form-control input"
                  id="store_name"
                  aria-describedby="store_name"
                  placeholder="Enter store name"
                  v-model="userData.store_name"
                />
                <small v-if="error.store_name" class="form-text text-danger">{{
                  error.store_name
                }}</small>
              </div>
              <!-- INPUT END -->
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
                <label class="input-label" for="contact_number">Contact #(Gcash number)</label>
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
            <div class="col-md-5">
              <!-- INPUT START -->
              <div class="form-group mb-2">
                <div class="d-flex justify-content-between">
                  <label class="input-label" for="email">Email</label>

                  <label
                    v-if="isSend == false && isSendVerificationLoading == false"
                    class="input-label mx-2 btn"
                    for="email"
                    @click="sendVerificationCode"
                    >Send Verification Code</label
                  >

                  <label
                    v-else-if="isSend == false && isSendVerificationLoading == true"
                    class="input-label mx-2 btn"
                    for="email"
                  >
                    <span class="spinner-border spinner-border-sm"></span>Sending...</label
                  >

                  <label
                    v-else
                    class="input-label mx-2 btn"
                    for="email"
                    @click="sendVerificationCode"
                    >Sent<i class="bi bi-envelope-check"></i
                  ></label>
                </div>
                <input
                  type="email"
                  class="form-control input"
                  id="email"
                  aria-describedby="email"
                  placeholder="Enter email"
                  v-model="userData.email"
                />
                <small v-if="error.email" class="form-text text-danger">{{ error.email }}</small>
              </div>
              <!-- INPUT END -->

              <!-- INPUT START -->
              <div class="form-group mb-2">
                <label class="input-label" for="otp_code">Verification Code</label>
                <input
                  type="text"
                  class="form-control input"
                  id="otp_code"
                  aria-describedby="otp_code"
                  placeholder="Enter otp code"
                  v-model="userData.token"
                />
                <small v-if="error.token" class="form-text text-danger">{{ error.token }}</small>
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

            <div class="d-flex justify-content-end">
              <div>
                <div class="form-check mt-3 mx-2">
                  <input type="checkbox" class="form-check-input" id="terms" v-model="isAgree" />
                  <label class="form-check-label" for="exampleCheck1"
                    >I agree with the
                    <span class="term-condition">terms and conditions</span></label
                  >
                </div>

                <div class="mt-4 d-flex justify-content-center">
                  <button class="btn login-btn" v-if="!isLoading" :disabled="!isAgree">
                    Register
                  </button>
                  <button type="button" class="btn login-btn" v-if="isLoading">
                    <span class="spinner-border spinner-border-sm"></span>
                    Registering...
                  </button>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <router-link class="btn btn-to-register" to="/register">Register</router-link>
              <router-link class="btn btn-to-register" to="/forgot-password"
                >Forgot Password</router-link
              >
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthenticationStore } from '../stores/authenticaton'
import { useRouter } from 'vue-router'
import { validEmail, validPassword } from '../utils/utils'

const store = useAuthenticationStore()
// const route = useRoute()
const router = useRouter()

const isAgree = ref(false)

const isLoading = ref(false)
const isSendVerificationLoading = ref(false)

const isSendVerification = ref(false)

const isSend = ref(false)

const showError = ref(false)

const userData = ref({
  first_name: '',
  last_name: '',
  id_number: '',
  contact_number: '',
  email: '',
  token: '',
  type: 'store',
  store_name: '',
  password_confirmation: '',
  password: ''
})

const error = ref({
  first_name: '',
  last_name: '',
  id_number: '',
  contact_number: '',
  email: '',
  token: '',
  password_confirmation: '',
  password: ''
})

const sendVerificationCode = () => {
  isSendVerification.value = true

  let isValid = true

  error.value = {
    first_name: '',
    last_name: '',
    id_number: '',
    contact_number: '',
    email: '',
    token: '',
    password_confirmation: '',
    password: ''
  }
  validEmail
  // add regex for tup email
  if (userData.value.email.length == 0) {
    error.value.email = 'Please enter tup email.'
    isValid = false
  }

  if (userData.value.first_name.length == 0) {
    error.value.first_name = 'Please enter your email.'
    isValid = false
  }

  if (isValid && isSendVerification) {
    isSendVerificationLoading.value = true
    store
      .verifyEmail({
        email: userData.value.email,
        first_name: userData.value.first_name
      })
      .then((data) => {
        isSendVerification.value = false
        isSendVerificationLoading.value = false
        if (data?.code === 'ERR_BAD_REQUEST') {
          isSend.value = false
        } else {
          isSend.value = true
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}

const submitRegister = () => {
  /**Add validation here */

  showError.value = false

  let isValid = true

  error.value = {
    first_name: '',
    last_name: '',
    id_number: '',
    contact_number: '',
    email: '',
    token: '',
    password_confirmation: '',
    password: ''
  }

  if (userData.value.email.length == 0) {
    error.value.email = 'Please enter tup email.'
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

  if (userData.value.store_name.length == 0) {
    error.value.store_name = 'Please enter store name.'
    isValid = false
  }

  if (userData.value.contact_number.length == 0) {
    error.value.contact_number = 'Please enter your contact number.'
    isValid = false
  }

  if (userData.value.token.length == 0) {
    error.value.token = 'Please enter verification code.'
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
      if (!validPassword.test(userData.value.password)) {
        error.value.password =
          'Password length should be at least 8 character, one uppercase letter, one lowercase letter, one number and one special character.'
        isValid = false
      }
    }
  }

  if (isValid && isLoading) {
    isLoading.value = true

    store
      .registerUser(userData.value)
      .then((data) => {
        isLoading.value = false

        if (data?.code === 'ERR_BAD_REQUEST') {
          showError.value = true
        } else {
          router.push('/')
        }
      })
      .catch((err) => {
        console.log('Error', err)
      })
  }
}
</script>

<style scoped>
.bg-img {
  background-image: url('../assets/bg.svg');

  min-height: 100vh !important;
}
.input {
  background: var(--color4);
  padding: 7px;
}
.input-label {
  font-size: 16px;
  font-weight: 600;
}
.login-btn {
  font-size: 18px;
  background: var(--color1);
  color: #fff;
  width: 300px;
  padding: 7px;
}
.login-btn:hover {
  font-size: 18px;
  background: var(--color3);
  color: #fff;
}
.term-condition {
  color: var(--color1);
  cursor: pointer;
}
.term-condition:hover {
  color: var(--color1);
  text-decoration: underline;
}
.btn-to-register {
  font-size: 18px;
  /* font-weight: 600; */
  color: #333;
}
.btn-to-register:hover {
  font-size: 18px;
  /* font-weight: 600; */
  color: var(--color3);
  text-decoration: underline;
}
</style>
