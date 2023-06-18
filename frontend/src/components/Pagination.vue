<template>
  <nav class="p-0 mt-0">
    <ul class="pagination justify-content-end m-0">
      <li class="page-item" :class="{ disabled: isInFirstPage }">
        <a class="page-link" href="#" aria-label="First" v-on:click.prevent="onClickFirstPage">
          First page
        </a>
      </li>
      <li
        v-for="page in pages"
        :key="page.name"
        class="page-item"
        :class="{ active: isPageActive(page.name) }"
      >
        <a
          class="page-link"
          href="#"
          @click.prevent="onClickPage(page.name)"
          :disabled="page.isDisabled"
        >
          {{ page.name }}
        </a>
      </li>
      <li class="page-item" :class="{ disabled: isInLastPage }">
        <a class="page-link" href="#" aria-label="Last" @click.prevent="onClickLastPage">
          Last page
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  /* eslint-disable */
  name: 'Pagination',
  /* eslint-enable */
  props: {
    maxVisibleButtons: {
      type: Number,
      required: false,
      default: 3
    },
    totalPages: {
      type: Number,
      required: true
    },
    perPage: {
      type: Number,
      required: true
    },
    currentPage: {
      type: Number,
      required: true
    }
  },
  computed: {
    startPage() {
      // When on the first page
      if (this.currentPage === 1) {
        return 1
      }
      // When on the last page
      if (this.currentPage === this.totalPages) {
        return this.totalPages - (this.maxVisibleButtons - 1)
      }
      // When inbetween
      return this.currentPage - 1
    },
    pages() {
      const range = []

      for (
        let i = this.startPage < 1 ? 1 : this.startPage;
        i <= Math.min(this.startPage + this.maxVisibleButtons - 1, this.totalPages);
        i++
      ) {
        range.push({
          name: i,
          isDisabled: i === this.currentPage
        })
      }
      return range
    },
    isInFirstPage() {
      return this.currentPage === 1
    },
    isInLastPage() {
      return this.currentPage === this.totalPages
    }
  },
  methods: {
    onClickFirstPage() {
      this.$emit('pagechanged', 1)
    },
    onClickPreviousPage() {
      this.$emit('pagechanged', this.currentPage - 1)
    },
    onClickPage(page) {
      this.$emit('pagechanged', page)
    },
    onClickNextPage() {
      this.$emit('pagechanged', this.currentPage + 1)
    },
    onClickLastPage() {
      this.$emit('pagechanged', this.totalPages)
    },
    isPageActive(page) {
      return this.currentPage === page
    }
  }
}
</script>

<style>
a.page-link {
  /* font: normal normal normal 12px/14px Clear Sans; */
  font-size: 14px;
  color: var(--color1);
  padding: 7px 15px;
}

.active > .page-link {
  background-color: var(--color1) !important;
  border-color: var(--color1) !important;
  color: #ffffff !important;
}
.pagination-details {
  color: #8b9da8;
  font-size: 14px;
}

.disabled > .page-link {
  color: #8b9da8;
}
</style>
