import { defineStore } from 'pinia'
import productService from '../../services/product'
import {
  deleteCookie,
  getCookie,
  getRefreshTokenExpiration,
  setCookie,
  utf8_to_b64
} from '../../utils/utils'

export const useProductStore = defineStore('product', {
  state: () => {
    return {
      products: [],
      store_products: [],

      product: {},
      user_carts: [],
      cart: {},
      orders: [],
      order: {},
      purchase: [],
      refund_orders: [],
      refund_order: {},
      product_rating: [],
      sales_today: {},
      available_delivery: [],
      riders_delivery: []
    }
  },
  getters: {},
  actions: {
    async addProduct(payload) {
      /**Call axios request in service */
      var formData = new FormData()
      formData.append('image', payload.image)
      formData.append('name', payload.name)
      formData.append('price', payload.price)
      formData.append('quantity', payload.quantity)
      formData.append('type', payload.type)
      formData.append('description', payload.description)
      formData.append('size', payload.size)

      return productService
        .addProduct(formData)
        .then((data) => {
          this.products = [data.data.data.product, ...this.products]
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getStoreProducts(payload) {
      return productService
        .getStoreProducts(payload)
        .then((data) => {
          this.store_products = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getStoreProduct(payload) {
      return productService
        .getProduct(payload.id)
        .then((data) => {
          this.product = data.product
          this.product_rating = data.ratings
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async updateProduct(id, data) {
      /**Call axios request in service */
      var formData = new FormData()
      if (data.image) {
        formData.append('image', data.image)
      }
      formData.append('_method', 'PATCH')
      formData.append('name', data.name)
      formData.append('price', data.price)
      formData.append('quantity', data.quantity)
      formData.append('type', data.type)
      formData.append('description', data.description)
      formData.append('size', data.size)

      return productService
        .updateProduct(id, formData)
        .then((data) => {
          let index = this.products.findIndex((x) => x.product_id == data.product_id)
          this.products[index] = data

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async deleteProduct(payload) {
      /**Call axios request in service */

      return productService
        .deleteProduct(payload.id)
        .then((data) => {
          this.products = this.products.filter((x) => x.product_id !== payload.id)

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async addToCart(payload) {
      /**Call axios request in service */
      var formData = new FormData()

      formData.append('product', payload.id)
      formData.append('quantity', payload.quantity)

      return productService
        .addToCart(formData)
        .then((data) => {
          // this.product.quantity = this.product.quantity - payload.quantity

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getUserCart(payload) {
      /**Call axios request in service */

      return productService
        .getUserCart()
        .then((data) => {
          this.user_carts = data

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async deleteCart(payload) {
      /**Call axios request in service */

      return productService
        .deleteCart(payload)
        .then((data) => {
          this.user_carts = this.user_carts.filter((x) => x.cart_id !== payload.id)

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getCartDetail(payload) {
      /**Call axios request in service */

      return productService
        .getCartDetail(payload.id)
        .then((data) => {
          this.cart = data

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async addOrder(payload) {
      /**Call axios request in service */
      // var formData = new FormData()

      // formData.append('product', payload.id)
      // formData.append('quantity', payload.quantity)

      return productService
        .addOrder(payload)
        .then((data) => {
          // this.product.quantity = this.product.quantity - payload.quantity

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getOrders(payload) {
      return productService
        .getOrders(payload)
        .then((data) => {
          this.orders = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getOrder(payload) {
      return productService
        .getOrder(payload.id)
        .then((data) => {
          this.order = data.order
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async updateStatus(id, data) {
      /**Call axios request in service */
      var formData = new FormData()

      formData.append('_method', 'PATCH')
      formData.append('status', data.status)

      return productService
        .updateStatus(id, formData)
        .then((data) => {
          this.order = data

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getAllProducts(payload) {
      return productService
        .getAllProducts(payload)
        .then((data) => {
          this.products = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getPurchase(payload) {
      return productService
        .getPurchase(payload)
        .then((data) => {
          this.purchase = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async addRefund(payload) {
      return productService
        .addRefund(payload)
        .then((data) => {
          // this.products = [data.data.data.product, ...this.products]
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getRefundOrders(payload) {
      return productService
        .getRefundOrders(payload)
        .then((data) => {
          this.refund_orders = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getRefundOrder(payload) {
      return productService
        .getRefundOrder(payload.id)
        .then((data) => {
          this.refund_order = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async updateRefundStatus(id, data) {
      /**Call axios request in service */
      var formData = new FormData()

      formData.append('_method', 'PATCH')
      formData.append('status', data.status)

      return productService
        .updateRefundStatus(id, formData)
        .then((data) => {
          this.refund_order = data

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async addRating(payload) {
      return productService
        .addRating(payload)
        .then((data) => {
          // this.products = [data.data.data.product, ...this.products]
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async sendReceipt(payload) {
      /**Call axios request in service */
      var formData = new FormData()
      formData.append('total', payload.total)
      formData.append('data', payload.data)
      return productService
        .sendReceipt(formData)
        .then((data) => {
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getSalesToday(payload) {
      return productService
        .getSalesToday()
        .then((data) => {
          this.sales_today = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async addWalkInOrder(payload) {
      /**Call axios request in service */
      // var formData = new FormData()

      // formData.append('product', payload.id)
      // formData.append('quantity', payload.quantity)

      return productService
        .addWalkInOrder(payload)
        .then((data) => {
          // this.product.quantity = this.product.quantity - payload.quantity

          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getAvailableDelivery(payload) {
      return productService
        .getAvailableDelivery()
        .then((data) => {
          this.available_delivery = data.orders
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async acceptDelivery(payload) {
      return productService
        .acceptDelivery(payload)
        .then((data) => {
          // this.sales_today = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async getRidersDelivery(payload) {
      return productService
        .getRidersDelivery()
        .then((data) => {
          this.riders_delivery = data.orders
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    },
    async completeDelivery(payload) {
      return productService
        .completeDelivery(payload)
        .then((data) => {
          // this.sales_today = data
          return Promise.resolve(data)
        })
        .catch((err) => {
          console.log(err)
          return Promise.resolve(err)
        })
    }
  }
})
