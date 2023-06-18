<template>
  <div class="bg-img">
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="col-md-5 py-5 card mt-5 px-4">
          <div class="">
            <h4 class="text-center">Login to your Tayuman Account</h4>
          </div>
          <form @submit.prevent="submitLogin" class="mt-2 mx-2">
            <!-- INPUT START -->
            <div class="form-group mb-2 mx-lg-5">
              <label class="input-label" for="email">Email</label>
              <input
                type="email"
                class="form-control input"
                id="email"
                aria-describedby="email"
                placeholder="Enter email"
                v-model="loginCredentials.email"
              />
              <!-- <small id="emailHelp" class="form-text text-muted"
              >We'll never share your email with anyone else.</small
            > -->
            </div>
            <!-- INPUT END -->

            <!-- INPUT START -->
            <div class="form-group mb-2 mx-lg-5">
              <label class="input-label" for="password">Password</label>
              <input
                type="password"
                class="form-control input"
                id="password"
                aria-describedby="password"
                placeholder="Enter password"
                v-model="loginCredentials.password"
              />
              <!-- <small id="emailHelp" class="form-text text-muted"
              >We'll never share your email with anyone else.</small
            > -->
            </div>
            <!-- INPUT END -->

            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn login-btn" v-if="!isLoading">Login</button>
              <button type="button" class="btn login-btn" v-if="isLoading">
                Logging in
                <span class="spinner-border spinner-border-sm"></span>
              </button>
            </div>
            <div class="mt-2" v-if="showMessage">
              <p class="text-center text-danger">{{ showMessage }}</p>
            </div>

            <div class="d-flex justify-content-between mt-3">
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

const store = useAuthenticationStore()
// const route = useRoute()
const router = useRouter()

const isLoading = ref(false)

const showMessage = ref('')

const loginCredentials = ref({
  email: '',
  password: ''
})

const submitLogin = () => {
  /**Add validation here */

  isLoading.value = true
  showMessage.value = ''
  store
    .login(loginCredentials.value)
    .then((data) => {
      isLoading.value = false
      console.log(data)
      // if (data?.code === 'ERR_BAD_REQUEST') {
      //   showError.value = true
      // } else {
      //   router.push('/')
      // }
      if (data?.status === 200 || data?.status === 201) {
        if (data.data.user.type === 'admin') {
          router.push('/admin-dashboard')
        } else if (data.data.user.type === 'store') {
          router.push('/store-dashboard')
        } else if (data.data.user.type === 'rider') {
          router.push('/rider-dashboard')
        } else {
          router.push('/user-dashboard')
        }
      } else {
        showMessage.value = data.response.data.error
      }
    })
    .catch((err) => {
      console.log('Error', err)
    })
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
