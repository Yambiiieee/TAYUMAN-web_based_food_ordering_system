import axiosInstance from '../axiosInstance'

export default {
  async addProduct(payload) {
    return axiosInstance
      .post('product', payload)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async getStoreProducts(payload) {
    let querytString = ''

    if (payload.filter.type && payload.filter.type !== 'All') {
      querytString += '?type=' + payload.filter.type
    }

    if (payload.filter.min_price && payload.filter.min_price !== 0) {
      if (querytString) {
        querytString += '&min_price=' + payload.filter.min_price
      } else {
        querytString += '?min_price=' + payload.filter.min_price
      }
    }

    if (payload.filter.max_price && payload.filter.max_price !== 0) {
      if (querytString) {
        querytString += '&max_price=' + payload.filter.max_price
      } else {
        querytString += '?max_price=' + payload.filter.max_price
      }
    }

    if (payload.filter.search && payload.filter.search !== '') {
      if (querytString) {
        querytString += '&search=' + payload.filter.search
      } else {
        querytString += '?search=' + payload.filter.search
      }
    }

    return axiosInstance
      .get(`product/store/${payload.id}${querytString}`)
      .then((response) => {
        return response.data.data.products
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async updateProduct(id, data) {
    return axiosInstance
      .post(`product/update/${id}`, data)
      .then((response) => {
        return response.data.data.product
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async deleteProduct(id) {
    return axiosInstance
      .delete(`product/delete/${id}`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async getProduct(id) {
    return axiosInstance
      .get(`product/${id}`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async addToCart(data) {
    return axiosInstance
      .post(`cart`, data)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async getUserCart() {
    return axiosInstance
      .get(`cart/user`)
      .then((response) => {
        return response.data.data.carts
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async deleteCart(payload) {
    return axiosInstance
      .delete(`cart/delete/${payload.id}`)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async getCartDetail(id) {
    return axiosInstance
      .get(`cart/${id}`)
      .then((response) => {
        return response.data.data.cart
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async addOrder(data) {
    return axiosInstance
      .post(`order`, data)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async getOrders(payload) {
    let querytString = ''

    if (payload.filter.type && payload.filter.type !== 'All') {
      querytString += '?type=' + payload.filter.type
    }

    if (payload.filter.startDate) {
      if (querytString) {
        querytString += '&startDate=' + payload.filter.startDate
      } else {
        querytString += '?startDate=' + payload.filter.startDate
      }
    }

    if (payload.filter.endDate) {
      if (querytString) {
        querytString += '&endDate=' + payload.filter.endDate
      } else {
        querytString += '?endDate=  ' + payload.filter.endDate
      }
    }

    if (payload.filter.search && payload.filter.search !== '') {
      if (querytString) {
        querytString += '&search=' + payload.filter.search
      } else {
        querytString += '?search=' + payload.filter.search
      }
    }

    if (payload.filter.order_by && payload.filter.order_by !== '0') {
      if (querytString) {
        querytString += '&order_by=' + payload.filter.order_by
      } else {
        querytString += '?order_by=' + payload.filter.order_by
      }
    }

    return axiosInstance
      .get(`order/user${querytString}`)
      .then((response) => {
        return response.data.data.orders
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async getOrder(id) {
    return axiosInstance
      .get(`order/${id}`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async updateStatus(id, data) {
    return axiosInstance
      .post(`order/update/status/${id}`, data)
      .then((response) => {
        return response.data.data.order
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async getAllProducts(payload) {
    let querytString = ''

    if (payload.filter.type && payload.filter.type !== 'All') {
      querytString += '?type=' + payload.filter.type
    }

    if (payload.filter.min_price && payload.filter.min_price !== 0) {
      if (querytString) {
        querytString += '&min_price=' + payload.filter.min_price
      } else {
        querytString += '?min_price=' + payload.filter.min_price
      }
    }

    if (payload.filter.max_price && payload.filter.max_price !== 0) {
      if (querytString) {
        querytString += '&max_price=' + payload.filter.max_price
      } else {
        querytString += '?max_price=' + payload.filter.max_price
      }
    }

    if (payload.filter.search && payload.filter.search !== '') {
      if (querytString) {
        querytString += '&search=' + payload.filter.search
      } else {
        querytString += '?search=' + payload.filter.search
      }
    }

    if (payload.filter.limit && payload.filter.limit !== '') {
      if (querytString) {
        querytString += '&limit=' + payload.filter.limit
      } else {
        querytString += '?limit=' + payload.filter.limit
      }
    }

    return axiosInstance
      .get(`product${querytString}`)
      .then((response) => {
        return response.data.data.products
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async getPurchase(payload) {
    let querytString = ''

    if (payload.filter.type && payload.filter.type !== 'All') {
      querytString += '?type=' + payload.filter.type
    }

    if (payload.filter.search && payload.filter.search !== '') {
      if (querytString) {
        querytString += '&search=' + payload.filter.search
      } else {
        querytString += '?search=' + payload.filter.search
      }
    }

    return axiosInstance
      .get(`purchase/user${querytString}`)
      .then((response) => {
        return response.data.data.orders
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async addRefund(data) {
    return axiosInstance
      .post(`refund`, data)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async getRefundOrders(payload) {
    let querytString = ''

    if (payload.filter.type && payload.filter.type !== 'All') {
      querytString += '?type=' + payload.filter.type
    }

    if (payload.filter.search && payload.filter.search !== '') {
      if (querytString) {
        querytString += '&search=' + payload.filter.search
      } else {
        querytString += '?search=' + payload.filter.search
      }
    }

    return axiosInstance
      .get(`/refund/user${querytString}`)
      .then((response) => {
        return response.data.data.refund_orders
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async getRefundOrder(id) {
    return axiosInstance
      .get(`refund/${id}`)
      .then((response) => {
        return response.data.data.refund_order
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async updateRefundStatus(id, data) {
    return axiosInstance
      .post(`refund/update/status/${id}`, data)
      .then((response) => {
        return response.data.data.refund_order
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async addRating(data) {
    return axiosInstance
      .post(`rating`, data)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async sendReceipt(data) {
    return axiosInstance
      .post(`order/receipt/send`, data)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async getSalesToday() {
    return axiosInstance
      .get(`/order/sales/today`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async addWalkInOrder(data) {
    return axiosInstance
      .post(`store/order`, data)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async getAvailableDelivery() {
    return axiosInstance
      .get(`/order/delivery/available`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async acceptDelivery(payload) {
    return axiosInstance
      .post(`/order/delivery/${payload.id}`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async getRidersDelivery() {
    return axiosInstance
      .get(`/order/delivery/rider`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  },
  async completeDelivery(payload) {
    return axiosInstance
      .post(`/order/delivery/complete/${payload.id}`)
      .then((response) => {
        return response.data.data
      })
      .catch((err) => {
        console.log(err)

        return err
      })
  }
}
