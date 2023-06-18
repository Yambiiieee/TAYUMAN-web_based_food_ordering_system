<template>
  <div class="bg-img">
    <div class="container">
      <div class="row d-flex justify-content-center" v-if="!isShowReset">
        <div class="col-md-5 py-5 card mt-5 px-4">
          <div class="">
            <h4 class="text-center">Forgot Password</h4>
          </div>
          <form @submit.prevent="sendForgotPassword" class="mt-2 mx-2 mx-lg-5">
            <!-- INPUT START -->
            <div class="form-group mb-2">
              <label class="input-label" for="email">Email</label>
              <input
                type="email"
                class="form-control input"
                id="email"
                aria-describedby="email"
                placeholder="Enter email"
                v-model="email"
              />
              <!-- <small id="emailHelp" class="form-text text-muted"
              >We'll never share your email with anyone else.</small
            > -->
            </div>
            <!-- INPUT END -->
            <div class="d-flex justify-content-center">
              <span>{{ message }}</span>
            </div>
            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn login-btn" v-if="!isLoading">Submit</button>
              <button type="button" class="btn login-btn" v-if="isLoading">
                <span class="spinner-border spinner-border-sm"></span> Sending.....
              </button>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <router-link class="btn btn-to-register" to="/login">Login</router-link>
              <router-link class="btn btn-to-register" to="/register">Register</router-link>
            </div>
          </form>
        </div>
      </div>
      <div class="row d-flex justify-content-center" v-if="isShowReset">
        <div class="col-md-5 py-5 card mt-5 px-4">
          <div class="mt-3">
            <h4 class="text-center">Reset Password</h4>
          </div>
          <form @submit.prevent="submitResetPassword" class="mx-2 mt-4 mx-lg-5">
            <!-- INPUT START -->
            <div class="form-group mb-2">
              <label class="input-label" for="code">Code</label>
              <input
                type="text"
                class="form-control input"
                id="code"
                aria-describedby="code"
                placeholder="Enter Code"
                v-model="passwordResetData.token"
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
                v-model="passwordResetData.password"
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
                v-model="passwordResetData.password_confirmation"
              />
              <small v-if="error.password_confirmation" class="form-text text-danger">{{
                error.password_confirmation
              }}</small>
            </div>
            <div class="d-flex justify-content-center">
              <span>{{ message }}</span>
            </div>
            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn login-btn" v-if="!isLoading">Submit</button>
              <button type="button" class="btn login-btn" v-if="isLoading">
                <span class="spinner-border spinner-border-sm"></span> Submiting.....
              </button>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <router-link class="btn btn-to-register" to="/login">Login</router-link>
              <router-link class="btn btn-to-register" to="/register">Register</router-link>
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

const isShowReset = ref(false)
const isLoading = ref(false)

const email = ref('')
const message = ref('')

const store = useAuthenticationStore()

const passwordResetData = ref({
  token: '',
  password: '',
  password_confirmation: ''
})

const error = ref({
  token: '',
  password: '',
  password_confirmation: ''
})

const sendForgotPassword = () => {
  /**Add validation here */
  if (!email.value) {
    message.value = 'Please enter email address'
  } else {
    isLoading.value = true
    message.value = ''
    store
      .forgotPassword({ email: email.value })
      .then((data) => {
        isLoading.value = false
        

        if (data?.status === 200 || data?.status === 201) {
          message.value = ''
          isShowReset.value = true
        } else if (data?.status === 400) {
          message.value = 'Email not found'
          isShowReset.value = true
        } else {
          message.value = 'Error'
          isShowReset.value = false
        }
      })
      .catch((err) => {
      
        console.log('Error', err)
      })
  }
}

const submitResetPassword = () => {
  /**Add validation here */

  let isValid = true

  error.value = {
    token: '',
    password: '',
    password_confirmation: ''
  }

  // add regex for tup email
  if (passwordResetData.value.token.length == 0) {
    error.value.to = 'Please enter verification code.'
    isValid = false
  }

  if (passwordResetData.value.password.length == 0) {
    error.value.password = 'Please enter Passwod.'
    isValid = false
  }

  if (passwordResetData.value.password.length > 0) {
    if (passwordResetData.value.password != passwordResetData.value.password_confirmation) {
      error.value.password = 'Please password not match.'
      isValid = false
    } else {
      if (passwordResetData.value.password.length < 8) {
        error.value.password = 'Password length should be at least 8 character.'
        isValid = false
      }
    }
  }

  if (isValid && isLoading) {
    isLoading.value = true

    store
      .resetPassword({ ...passwordResetData.value, email: email.value })
      .then((data) => {
        isLoading.value = false
        
        if (data?.status === 200 || data?.status === 201) {
          passwordResetData.value = {
            token: '',
            password: '',
            password_confirmation: ''
          }
          message.value = 'Password Reset Successfully. Please re-login your account.'
          isShowReset.value = true
        } else if (
          data?.status === 400 ||
          data?.status === 404 ||
          data?.message == 'Request failed with status code 404'
        ) {
          message.value = 'Invalid verification code'
        } else {
          message.value = 'Error'
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
  min-height: 80% !important;
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
