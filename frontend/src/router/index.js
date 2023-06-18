import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import RegisterUser from '../views/RegisterUser.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import Product from '../views/Product.vue'
import ProductDetail from '../views/ProductDetail.vue'
import Users from '../views/Users.vue'
import Stores from '../views/Stores.vue'
import Admins from '../views/Admins.vue'
import Store from '../views/Store.vue'
import Cart from '../views/Cart.vue'
import PlaceOrder from '../views/PlaceOrder.vue'
import Orders from '../views/Orders.vue'
import Purchase from '../views/Purchase.vue'
import OrderDetail from '../views/OrderDetail.vue'
import Refunds from '../views/Refunds.vue'
import RefundDetail from '../views/RefundDetail.vue'
import RegisterMerchant from '../views/RegisterMerchant.vue'
import AdminDashboard from '../views/AdminDashboard.vue'
import StoreDashboard from '../views/StoreDashboard.vue'
import UserDashboard from '../views/UserDashboard.vue'
import Riders from '../views/Riders.vue'
import RiderDashboard from '../views/RiderDashboard.vue'
import NewOrder from '../views/NewOrder.vue'

import SalesList from '../views/SalesList.vue'
import { useAuthenticationStore } from '../stores/authenticaton'

import MainLayout from '../components/MainLayout.vue'
import { ref } from 'vue'

const routes = [
  {
    path: '/',
    // redirect: "/",
    component: MainLayout,
    // meta: { requiresAuth: true },
    children: [
      {
        path: '/rider-dashboard',
        name: 'RiderDashboard',
        component: RiderDashboard,
        meta: { admin: false, store: false, user: false, rider: true, requiresAuth: true }
      },
      {
        path: '/user-dashboard',
        name: 'UserDashboard',
        component: UserDashboard,
        meta: { admin: false, store: false, user: true, requiresAuth: true }
      },
      {
        path: '/store-dashboard',
        name: 'StoreDashboard',
        component: StoreDashboard,
        meta: { admin: false, store: true, user: false, requiresAuth: true }
      },
      {
        path: '/admin-dashboard',
        name: 'AdminDashboard',
        component: AdminDashboard,
        meta: { admin: true, store: false, user: false, requiresAuth: true }
      },
      {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { isGuest: true }
      },

      {
        path: '/products',
        name: 'Products',
        component: Product,
        meta: { all: true }
      },
      {
        path: '/product/detail/:id',
        name: 'ProductDetail',
        component: ProductDetail,
        meta: { all: true }
      },
      {
        path: '/riders',
        name: 'Riders',
        component: Riders,
        meta: { admin: true, store: false, user: false, requiresAuth: true }
      },
      {
        path: '/users',
        name: 'Users',
        component: Users,
        meta: { admin: true, store: false, user: false, requiresAuth: true }
      },
      {
        path: '/stores',
        name: 'Stores',
        component: Stores,
        meta: { admin: true, store: false, user: false, requiresAuth: true }
      },
      {
        path: '/store/:id',
        name: 'Store',
        component: Store,
        meta: { all: true }
      },
      {
        path: '/cart',
        name: 'Cart',
        component: Cart,
        meta: { requiresAuth: true }
      },
      {
        path: '/new-order',
        name: 'NewOrder',
        component: NewOrder,
        meta: { requiresAuth: true }
      },
      {
        path: '/product/place-order/:id',
        name: 'PlaceOrder',
        component: PlaceOrder,
        meta: { requiresAuth: true }
      },
      {
        path: '/orders',
        name: 'Orders',
        component: Orders,
        meta: { requiresAuth: true }
      },
      {
        path: '/order/detail/:id',
        name: 'OrderDetail',
        component: OrderDetail,
        meta: { requiresAuth: true }
      },
      {
        path: '/admin',
        name: 'Admins',
        component: Admins,
        meta: { admin: true, store: false, user: false, requiresAuth: true }
      },
      {
        path: '/purchase',
        name: 'Purchase',
        component: Purchase,
        meta: { requiresAuth: true }
      },
      {
        path: '/refund',
        name: 'Refunds',
        component: Refunds,
        meta: { requiresAuth: true }
      },
      {
        path: '/refund/detail/:id',
        name: 'RefundDetail',
        component: RefundDetail,
        meta: { requiresAuth: true }
      },

      {
        path: '/sales/list',
        name: 'SalesList',
        component: SalesList,
        meta: { admin: false, store: true, user: false, requiresAuth: true }
      },
      /**Not auth */
      {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { isGuest: true }
      },
      {
        path: '/register',
        name: 'RegisterUser',
        component: RegisterUser,
        meta: { isGuest: true }
      },
      {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPassword,
        meta: { isGuest: true }
      },
      {
        path: '/register/merchant',
        name: 'RegisterMerchant',
        component: RegisterMerchant,
        meta: { all: true }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthenticationStore()

  const user = ref(authStore.user)

  const isLoggedIn = ref(authStore.isLoggedIn)

  if (to.meta.requiresAuth && !isLoggedIn.value) {
    next({ name: 'Login' })
  } else if (isLoggedIn.value && to.meta.isGuest) {
    if (user.value.type === 'admin') {
      next({ name: 'AdminDashboard' })
    } else if (user.value.type === 'store') {
      next({ name: 'StoreDashboard' })
    } else if (user.value.type === 'rider') {
      next({ name: 'RiderDashboard' })
    } else {
      next({ name: 'UserDashboard' })
    }
  } else {
    if (to.meta.all) {
      next()
    }

    if (to.meta.admin) {
      if (user.value.type !== 'admin') {
        if (user.value.type === 'admin') {
          next({ name: 'AdminDashboard' })
        } else if (user.value.type === 'store') {
          next({ name: 'StoreDashboard' })
        } else if (user.value.type === 'rider') {
          next({ name: 'RiderDashboard' })
        } else {
          next({ name: 'UserDashboard' })
        }
      }
      next()
    } else if (to.meta.store) {
      if (user.value.type != 'store') {
        if (user.value.type === 'admin') {
          next({ name: 'AdminDashboard' })
        } else if (user.value.type === 'store') {
          next({ name: 'StoreDashboard' })
        } else if (user.value.type === 'rider') {
          next({ name: 'RiderDashboard' })
        } else {
          next({ name: 'UserDashboard' })
        }
      }
      next()
    } else if (to.meta.rider) {
      if (user.value.type != 'rider') {
        if (user.value.type === 'admin') {
          next({ name: 'AdminDashboard' })
        } else if (user.value.type === 'store') {
          next({ name: 'StoreDashboard' })
        } else if (user.value.type === 'rider') {
          next({ name: 'RiderDashboard' })
        } else {
          next({ name: 'UserDashboard' })
        }
      }
      next()
    }

    next()
  }
})

export default router
