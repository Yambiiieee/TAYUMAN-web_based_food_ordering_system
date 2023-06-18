<template>
  <div class="fluid-container">
    <div class="loader">
        <div class="d-flex justify-content-center">
          <div class="row ">
            <div class="col d-flex justify-content-center">
                <div>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        
            <div class="w-100"></div>

            <div class="col d-flex justify-content-center">
                <p>Loading please wait..</p>
            </div>
          </div>
        </div>
        
    </div>
    
  </div>
</template>

<script>
export default {

}
</script>

<style scoped>

.loader{
    /* padding: 0;
    margin: 0; */
    /* width: auto; */
    /* border: solid 1px red; */
    /* height: 40px; */
    /* left: 50%; */
    /* position: absolute; */
    margin-top: 20%;
    /* transform: translateX(-50%) translateY(-50%); */
}
.loader span{
    height: 15px;
    width: 15px;
    display: inline-block;
    border-radius: 50%;
    transition: all .5s;
    animation: animate 2s infinite;
    margin: 2px;
}
.loader span:nth-child(1){
    animation-delay: 0;
}
.loader span:nth-child(2){
    animation-delay: .1s;
}
.loader span:nth-child(3){
    animation-delay: .2s;
}
.loader span:nth-child(4){
    animation-delay: .3s;
}
.loader span:nth-child(5){
    animation-delay: .4s;
}
.loader span:nth-child(6){
    animation-delay: .5s;
}
@keyframes animate{
    0%{
        border: 1px solid #303030;
        background: transparent;
        transform: translateY(0);
        border-radius: 10%;
    }
    50%{
        border: 1px solid #ffffff;
        background: #301E67;
        transform: translateY(-25px);
        border-radius: 45%;
        
    }
    100%{
        border: 1px solid #414141;
        background: #5B8FB9;
        transform: translateY(0);
        
    }
}
</style>