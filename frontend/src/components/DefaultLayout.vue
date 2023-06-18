<template>
  <div class="">
    <div class="">
      <Loader v-show="isShowLoading"/>
        <router-view :key="$route.path"  v-show="!isShowLoading"></router-view>
    </div>
  </div>
</template>



<script setup>
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import Loader from '../components/Loader.vue'
import { computed, ref } from "vue";
 
 const isShowLoading = ref(computed(() => store.state.showLoading))

    const store = useStore();
    const router = useRouter();

 
</script>
