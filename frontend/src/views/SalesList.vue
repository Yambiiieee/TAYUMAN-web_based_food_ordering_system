<template>
  <div class="container py-5">
    <h2 class="color1">Total Sales:₱{{ total }}</h2>

    <div class="mt-4 row">
      <div class="input-group search-container col-md-6">
        <input
          type="search"
          class="form-control search-input"
          placeholder="Search"
          aria-label="Search"
          aria-describedby="search-addon"
          v-model="filter.search"
          @change="getOrders"
        />
        <button type="button" class="btn search-btn"><i class="bi bi-search"></i>Search</button>
      </div>
      <div class="col-md-3">
        <button
          type="button"
          class="btn shadow border"
          data-bs-toggle="collapse"
          data-bs-target="#collapseFilter"
          aria-expanded="false"
          aria-controls="collapseFilter"
        >
          <i class="bi bi-funnel"></i> All Filters
        </button>
      </div>
      <div class="col-md-3 justify-content-end d-flex">
        <button type="button" class="btn shadows btn-danger" @click="exportToPDF">
          <i class="bi bi-file-earmark-arrow-down"></i>
          Download
        </button>
      </div>
    </div>

    <div class="container mt-3 collapse" id="collapseFilter">
      <div class="card border-0">
        <div class="card-body p-3 bg-light">
          <div class="row">
            <div class="col-2">
              <label for="date-from"><b>Date From</b></label>
              <input
                type="date"
                class="form-control"
                id="date-from"
                name="date-from"
                v-model="filter.startDate"
                @change="getOrders"
              />
            </div>
            <div class="col-2">
              <label for="date-to"><b>Date To</b></label>
              <input
                type="date"
                class="form-control"
                id="date-to"
                name="date-to"
                v-model="filter.endDate"
                @change="getOrders"
              />
            </div>
            <div class="col-3">
              <label for="file-type"><b>Order by Surname</b></label>
              <select
                class="form-select"
                aria-label="Default select example"
                id="file-type"
                name="file-type"
                @change="getOrders"
                v-model="filter.order_by"
              >
                <option value="0">----Select----</option>
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-md-12">
        <div class="table-responsivepx-lg-4">
          <table class="table table-sm">
            <thead class="">
              <tr>
                <th>Order Id</th>
                <th>Product</th>
                <th>Buyer first name</th>
                <th>Buyer last name</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in salesDataDisplay" :key="order.id">
                <th>{{ order.order_id }}</th>
                <td>{{ order.name }}</td>
                <td>{{ order.first_name }}</td>
                <td>{{ order.last_name }}</td>
                <td>₱{{ order.payment }}</td>
                <td>{{ order.status }}</td>
                <td>{{ order.order_created }}</td>

                <td class="">
                  <router-link
                    :to="`/order/detail/${order.order_id}`"
                    class="btn btn-outline-primary btn-sm"
                    >View</router-link
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- PAGINATION START -->
      <div class="container py-5">
        <div class="px-2 pb-2 table-pagination" style="margin-top: -40px; width: 100%">
          <div class="d-flex align-items-center pagination-label">
            Showing {{ pagination.firstVal }} to {{ pagination.secondVal }} of
            {{ pagination.total }}
          </div>

          <!-- Imported Pagination Component -->
          <Pagination
            :maxVisibleButtons="pagination.maxVisibleButtons"
            :totalPages="pagination.totalPages"
            :perPage="pagination.perPage"
            :currentPage="pagination.currentPage"
            @pagechanged="getPage"
            class="d-flex justify-content-end float-end"
            style="width: fit-content; margin-left: 0px"
            v-if="pagination.total >= 8"
          >
          </Pagination>
        </div>
      </div>
      <!-- PAGINATION END -->
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import Pagination from '../components/Pagination.vue'
import { useProductStore } from '../stores/product'

import { useRouter } from 'vue-router'
import jsPDF from 'jspdf'
import 'jspdf-autotable'
const productStore = useProductStore()

// const route = useRoute()

const router = useRouter()

const isLoading = ref(false)

const total = ref(0)

const orders = ref(computed(() => productStore.orders))
const id = ref('')

const filter = ref({
  type: 'Done',
  startDate: '',
  endDate: '',
  search: '',
  order_by: '0'
})

const salesDataHolder = ref([])
const salesDataDisplay = ref([])

const pagination = ref({
  maxVisibleButtons: 10,
  totalPages: 1,
  perPage: 8,
  currentPage: 1,
  total: 0,
  showPagination: true,
  firstVal: 1,
  secondVal: 8
})

const getPage = (page) => {
  pagination.value.currentPage = page

  let val1 = 1
  let val2 = 8

  val2 = val2 * pagination.value.currentPage
  val1 = val2 - 7

  pagination.value.firstVal = val1
  pagination.value.secondVal = val2

  salesDataDisplay.value = salesDataHolder.value.slice(val1 - 1, val2)

  if (pagination.value.totalEvents < 8) {
    pagination.value.secondVal = pagination.value.total
  } else if (pagination.value.firstVal + 8 > pagination.value.total) {
    pagination.value.secondVal = pagination.value.total
  }
}

const setPagination = () => {
  salesDataHolder.value = orders.value
  salesDataDisplay.value = salesDataHolder.value.slice(0, 8)

  pagination.value.total = salesDataHolder.value.length
  pagination.value.totalPages = parseInt(salesDataHolder.value.length / 8)

  if (salesDataHolder.value.length % 8 !== 0) {
    pagination.value.totalPages += 1
  }
  getPage(1)
}

const exportToPDF = () => {
  // convert to array of array

  let data = []

  for (var i = 0; i < orders.value.length; i++) {
    data.push([
      orders.value[i].order_id,
      orders.value[i].name,
      orders.value[i].first_name,
      orders.value[i].last_name,
      orders.value[i].payment,
      orders.value[i].status,
      orders.value[i].order_created
    ])
  }

  data.push(['', '', '', '', '', '', ''])

  data.push(['', '', '', '', , '', 'Total', total.value])

  const doc = new jsPDF({
    orientation: 'l',
    unit: 'mm',
    format: 'tabloid',
    putOnlyUsedFonts: true
  })

  doc.autoTable({
    head: [
      [
        'Order Id',
        'Product',
        'Buyer First Name',
        'Buyer Last Name',
        'Payment',
        'Status',
        'Order date'
      ]
    ],
    body: data,
    startY: 15,
    tableWidth: 'auto',
    /* apply styling to table body */
    bodyStyles: {
      valign: 'top'
    },
    /* apply global styling */
    styles: {
      // cellWidth: "wrap",
      rowPageBreak: 'auto',
      halign: 'justify'
    },
    headerStyles: {
      theme: 'grid'
    }
    /* apply styling specific to table columns */
    // columnStyles: {
    //   text: {
    //     cellWidth: "auto",
    //     columnWidth: "wrap",
    //     cellPadding: 8,
    //     overflowColumns: "linebreak",
    //   },
    //   0: {
    //     columnWidth: 10,
    //   },
    //   1: {
    //     columnWidth: 45,
    //   },
    //   2: {
    //     columnWidth: 60,
    //   },
    //   3: {
    //     columnWidth: 35,
    //   },
    //   4: {
    //     columnWidth: 35,
    //   },
    //   5: {
    //     columnWidth: 40,
    //   },
    //   6: {
    //     columnWidth: 30,
    //   },
    //   7: {
    //     columnWidth: 45,
    //   },
    //   8: {
    //     columnWidth: 30,
    //   },
    //   9: {
    //     columnWidth: 30,
    //   },
    //   10: {
    //     columnWidth: 35,
    //   },
    //   11: {
    //     columnWidth: 30,
    //   },
    // },
  })

  doc.save('sales.pdf')
}

const getOrders = () => {
  productStore
    .getOrders({
      filter: filter.value
    })
    .then((data) => {
      setPagination()
    })
    .catch((err) => {
      console.log('Error', err)
    })
}

watch(orders, () => {
  let sum = 0
  for (var i = 0; i < orders.value.length; i++) {
    sum += orders.value[i].payment
  }
  total.value = sum
})

onMounted(() => {
  getOrders()
})
</script>

<style scoped>
.color1 {
  color: var(--color1);
}
.search-container {
  position: relative;
  width: 500px;
}
.search-btn {
  background: var(--color1);
  border-radius: 10px;
  color: #fff;
}

.search-btn:hover {
  background: var(--color3);
  border-radius: 10px;
  color: #fff;
}
.add-user-btn {
  font-size: 18px;
  background: var(--color1);
  color: #fff;
  width: 120px;
  padding: 7px;
}
.add-user-btn:hover {
  font-size: 18px;
  background: var(--color3);
  color: #fff;
}
.bi-person-check {
  font-size: 110px;
  width: fit-content;
  margin-left: 35%;
  padding: 10px;
  margin-top: -15%;
  margin-bottom: 5%;
  background-color: #fff;
  border: 3px solid #9fa5a1;
  border-radius: 50%;
}
.modal-header-edit {
  background: var(--color1) !important;
  color: #fff !important;
}
.input {
  background: var(--color4);
  padding: 7px;
}
.input-label {
  font-size: 16px;
  font-weight: 600;
}
</style>
