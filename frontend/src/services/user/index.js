import axiosInstance from '../axiosInstance'

export default {
  async getAllUsers(payload) {
    let querytString = ''

    if (payload.type && payload.type !== 'All') {
      querytString += '?type=' + payload.type
    }

    if (payload.status && payload.status !== '') {
      if (querytString) {
        querytString += '&status=' + payload.status
      } else {
        querytString += '?status=' + payload.status
      }
    }

    if (payload.limit && payload.limit !== 0) {
      if (querytString) {
        querytString += '&limit=' + payload.limit
      } else {
        querytString += '?limit=' + payload.limit
      }
    }

    if (payload.search && payload.search !== '') {
      if (querytString) {
        querytString += '&search=' + payload.search
      } else {
        querytString += '?search=' + payload.search
      }
    }

    return axiosInstance
      .get(`user${querytString}`)
      .then((response) => {
     
        return response.data.data.users
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async getStore(payload) {
    return axiosInstance
      .get(`user/${payload.id}`)
      .then((response) => {
      
        return response.data.data.user
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async deactivateUser(payload) {
    return axiosInstance
      .post(`/user/deactivate/${payload.id}`)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async activateUser(payload) {
    return axiosInstance
      .post(`/user/activate/${payload.id}`)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async updateUser(payload) {
    return axiosInstance
      .post(`user/update/${payload.id}`, payload.data)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async deleteUser(payload) {
    return axiosInstance
      .delete(`user/delete/${payload}`)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async registerUserAccount(payload) {
    return axiosInstance
      .post('register/user', payload)
      .then((response) => {
        return response
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  }
}
