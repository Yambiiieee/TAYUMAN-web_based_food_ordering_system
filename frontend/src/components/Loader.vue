<template>
  <div v-show="isShowLoading">
    <div class="fluid-container loader-container">
        <div class="loader">
            <div class="sq1">
                <div class="sq2">
                    <div class="sq3">
                        <div class="sq4"></div>
                    </div>
                </div>
            </div>
            <p >Loading . . .</p>
        </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import store from "../store";

 const isShowLoading = ref(computed(() => store.state.showLoading))
</script>

<style scoped>
*{
    padding: 0;
    margin: 0;
}
.loader-container{
    z-index: 100;
    min-width: 100%;
    min-height: 100%;
    background-color: #fafafa;
    /* opacity: .8; */
    position:absolute;
}
.loader{
  
    /* background-color: transparent; */
    opacity: 1 !important;
    height: 160px;
    width: 160px;
    position: absolute;
    margin: auto;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    perspective: 350;
    -webkit-perspective: 350;
}
.loader p{
    margin-top: 90px;
    display: flex;
    justify-content: center;
    opacity: 1 !important;
    color: #000;
    /* border: solid 1px red; */
}
.sq1, .sq2, .sq3, .sq4{  
    opacity: 1 !important;
    height: 50px;
    width: 50px;
    position: relative;
    transform-style: preserve-3d;
    transition: 2s all ease-in;
    border-radius: 50%;
}
@media (-webkit-transform-3d) {
  .sq1, .sq2, .sq3, .sq4 {
    transform-style: preserve-3d;
  }
}
.sq1{
    opacity: 1 !important;
    top: 80px;
    left: 80px;
    background-color: #FAFAFA;
    box-shadow: 0 0.2rem 0.3rem rgba(0, 0, 0, 0.15) !important;
    border: solid .8px #019267;
}
.sq2{
    opacity: 1;

    bottom: 54.5px;
    animation:fold2 3s infinite;
    transform-origin: bottom;
    background-color: #019267;
    box-shadow: 0 0.2rem 0.3rem rgba(0, 0, 0, 0.15) !important;
    border: solid .8px #019267;
}
.sq3{
    opacity: 1 !important;
    right: 54.5px;
    animation:fold1 3s infinite;
    transform-origin: right;
    background-color: #FAFAFA;
    box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.15) !important;
    border: solid .8px #019267;
}
.sq4{
    opacity: 1 !important;
    top:54.5px;
    animation:fold 3s infinite;
    transform-origin: top;
    background-color: #019267;
    box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.15) !important;
    border: solid .8px #019267;
    }
    @keyframes fold{
        0%{
            transform: rotateX(0deg);
        }
        10%{
            transform: rotateX(-180deg) translateY(4px);

        }
        70%{
            transform: rotateX(-180deg) translateY(4px);
        }
        80%{
            transform: rotateX(0deg);
        }
    }
    @keyframes fold1{
        10%{
            transform: rotateY(0deg);
        }
        20%{
            transform: rotateY(-180deg) translateX(-4px);

        }
        60%{
            transform: rotateY(-180deg) translateX(-4px);
        }
        70%{
            transform: rotateY(0deg);
        }
    }
    @keyframes fold2{
        20%{
            transform: rotateX(0deg);
        }
        30%{
            transform: rotateX(180deg) translateY(-4px);

        }   
        50%{
            transform: rotateX(180deg) translateY(-4px);
        }
        60%{
            transform: rotateX(0deg);
        }
    }



</style>