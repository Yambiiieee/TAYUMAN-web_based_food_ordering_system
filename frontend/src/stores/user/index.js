import { defineStore } from 'pinia'
import userService from '../../services/user'

export const useUserStore = defineStore('users', {
  state: () => {
    return {
      users: [],
      user: {},
      store: {}
    }
  },
  getters: {},
  actions: {
    async getAllUsers(payload) {
      return userService
        .getAllUsers(payload)
        .then((data) => {
        
          this.users = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getStore(payload) {
      return userService
        .getStore(payload)
        .then((data) => {
         
          this.store = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async deactivateUser(payload) {
      /**Call axios request in service */

      return userService
        .deactivateUser(payload)
        .then((data) => {
          console.log(data)

          let index = this.users.findIndex((x) => x.id == payload.id)
          this.users[index].is_active = 0

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async activateUser(payload) {
      /**Call axios request in service */

      return userService
        .activateUser(payload)
        .then((data) => {
         

          let index = this.users.findIndex((x) => x.id == payload.id)
          this.users[index].is_active = 1

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async updateUser(payload) {
      /**Call axios request in service */
      var formData = new FormData()
      formData.append('_method', 'PATCH')
      formData.append('first_name', payload.first_name)
      formData.append('last_name', payload.last_name)
      formData.append('id_number', payload.id_number)
      formData.append('contact_number', payload.contact_number)
      formData.append('email', payload.email)
      formData.append('store_name', payload.store_name)

      

      return userService
        .updateUser({ id: payload.id, data: formData })
        .then((data) => {
          
          let index = this.users.findIndex((x) => x.id == payload.id)
          this.users[index] = data.data.data.user
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async deleteUser(payload) {
      /**Call axios request in service */

      return userService
        .deleteUser(payload.id)
        .then((data) => {
          

          this.users = this.users.filter((x) => x.id !== payload.id)

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async registerUserAccount(payload) {
      /**Call axios request in service */
      var formData = new FormData()
      formData.append('first_name', payload.first_name)
      formData.append('last_name', payload.last_name)
      formData.append('id_number', payload.id_number)
      formData.append('contact_number', payload.contact_number)
      formData.append('email', payload.email)
      formData.append('password', payload.password)
      formData.append('store_name', payload.store_name)
      formData.append('type', payload.type)
      formData.append('password_confirmation', payload.password_confirmation)
  

      return userService
        .registerUserAccount(formData)
        .then((data) => {
          /*Set user */

          this.users = [data.data.data.user, ...this.users]

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    }
  }
})
