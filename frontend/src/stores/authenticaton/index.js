import { defineStore } from 'pinia'
import authenticationService from '../../services/authentication'
import {
  deleteCookie,
  getCookie,
  getRefreshTokenExpiration,
  setCookie,
  utf8_to_b64
} from '../../utils/utils'

export const useAuthenticationStore = defineStore('authentication', {
  state: () => {
    const user = getCookie('user') || null
    const isLoggedIn = getCookie('isLoggedIn') || false

    return {
      user: user || null,
      isLoggedIn: isLoggedIn || false
    }
  },
  getters: {},
  actions: {
    async login(payload) {
      /**Call axios request in service */
      var formData = new FormData()
      formData.append('email', payload.email)
      formData.append('password', payload.password)

      

      return authenticationService
        .loginUser(formData)
        .then((data) => {
         

          /**Save to cookies and local storage */

          /*Set user */

          this.isLoggedIn = true

          this.user = data.data.user

          /**Set token */
          setCookie('user', data.data.user, 420)
          setCookie('rfce_7519', data.data.token, 420)
          setCookie('isLoggedIn', true, 420)

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },

    async registerUser(payload) {
      /**Call axios request in service */
      var formData = new FormData()
      formData.append('first_name', payload.first_name)
      formData.append('last_name', payload.last_name)
      formData.append('id_number', payload.id_number)
      formData.append('contact_number', payload.contact_number)
      formData.append('email', payload.email)
      formData.append('token', payload.token)
      formData.append('password', payload.password)
      formData.append('store_name', payload.store_name)
      formData.append('type', payload.type)
      formData.append('password_confirmation', payload.password_confirmation)

      

      return authenticationService
        .registerUser(formData)
        .then((data) => {
          /*Set user */

          this.isLoggedIn = true
          this.user = data.user
          /**Set token */
          setCookie('user', data.user, 420)
          setCookie('rfce_7519', data.token, 420)
          setCookie('isLoggedIn', true, 420)

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },

    async verifyEmail(payload) {
      /**Call axios request in service */
      var formData = new FormData()
      formData.append('email', payload.email)
      formData.append('first_name', payload.first_name)

     

      return authenticationService
        .verifyEmail(formData)
        .then((data) => {
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },

    async logout() {
      /**Call axios request in service */
      return authenticationService
        .logoutUser()
        .then(() => {
          this.isLoggedIn = false

          deleteCookie('user')
          deleteCookie('rfc7519')
          deleteCookie('isLoggedIn')

          //  if (!store.state.auth.stayed_in) {
          //      localStorage.removeItem('remember_me');
          //      localStorage.removeItem('stayed_in');
          //  }

          return Promise.resolve()
        })
        .catch((err) => {
          this.isLoggedIn = false

          deleteCookie('user')
          deleteCookie('rfc7519')
          deleteCookie('isLoggedIn')
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async forgotPassword(payload) {
      /**Call axios request in service */

      let formData = new FormData()
      formData.append('email', payload.email)

      /**Check if email exist */
      return authenticationService
        .forgotPassword(formData)
        .then((data) => {
          /**Send forgot password */

          // .then(()=>{
          //      return Promise.resolve()
          // })
          // .catch((err)=>{
          // console.log(err)
          // return Promise.resolve(err)
          // })

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async resetPassword(payload) {
      /**Call axios request in service */

      // let formData = new FormData()
      // formData.append('new_password', payload.password)
      // formData.append('token', payload.token)

      return authenticationService
        .resetPassword(payload)
        .then((data) => {
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    }
  }
})
