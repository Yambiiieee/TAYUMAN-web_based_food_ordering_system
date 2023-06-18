/**
 * Created by Zura on 12/25/2021.
 */
import axios from 'axios'

import { getCookie } from '../utils/utils'

const axiosClient = axios.create({
  baseURL: `http://127.0.0.1:8000/api/`
})

axiosClient.interceptors.request.use((config) => {
  const token = getCookie('rfce_7519')
  config.headers.Authorization = `Bearer ${token}`
  return config
})

// axiosClient.interceptors.response.use(
//   (response) => {
//     return response;
//   },
//   (error) => {
//     console.log("axios err", error);
//     // if (error.response.status === 401) {
//     //   sessionStorage.removeItem("TOKEN");
//     //   router.push({ name: "Login" });
//     // } else if (error.response.status === 404) {
//     //   router.push({ name: "NotFound" });
//     // }
//     return error;
//   }
// );

export default axiosClient
