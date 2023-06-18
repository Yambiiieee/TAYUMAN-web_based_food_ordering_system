import axiosInstance from '../axiosInstance'

export default {
  async loginUser(payload) {
    return axiosInstance
      .post('login', payload)
      .then((response) => {
        axiosInstance.defaults.headers['Authorization'] = 'Bearer ' + response.data.token
      
        return response
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async verifyEmail(payload) {
    return axiosInstance
      .post('verify-email', payload)
      .then((response) => {
        return response.data
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async registerUser(payload) {
    return axiosInstance
      .post('register', payload)
      .then((response) => {
        axiosInstance.defaults.headers['Authorization'] = 'Bearer ' + response.data.token

        return response.data
      })
      .catch((err) => {
        console.log(err)

        return Promise.reject(err)
      })
  },
  async logoutUser() {
    return axiosInstance
      .post('logout')
      .then((response) => {
        return Promise.resolve(response.data)
      })
      .catch((err) => {
        console.log(err)
        return Promise.reject(err)
      })
  },
  //   async checkEmail(payload) {
  //     return axiosInstance
  //         .post('account/check-emailaddress',payload)
  //         .then((response) => {
  //             return Promise.resolve(response);
  //         })
  //         .catch((err) => {
  //             console.log(err);
  //             return Promise.reject(err);
  //         });
  //   },
  async forgotPassword(payload) {
    return axiosInstance
      .post('forgot-password', payload)
      .then((response) => {
        return Promise.resolve(response)
      })
      .catch((err) => {
        console.log(err)
        return Promise.reject(err)
      })
  },
  async resetPassword(payload) {
    return axiosInstance
      .post('reset-password', payload)
      .then((response) => {
        return Promise.resolve(response)
      })
      .catch((err) => {
        console.log(err)
        return Promise.reject(err)
      })
  }
}
