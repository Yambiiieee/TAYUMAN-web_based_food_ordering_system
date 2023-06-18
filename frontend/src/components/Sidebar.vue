<template>
  <div class="wrapper">
      <div class="sidebar shadow">
        <div class="logo-container" align="center">
          <img class="img-logo" src="../assets/logos/logo.png" alt="logo">
          <p class="text-black mt-1 logo-name">CLSU DOCUMENTS<br> RECORD PORTAL </p>
        </div>
        <div class="underline px-1">
          <hr />
        </div>
          <ul>
            <li class="nav-bar " v-if="user?.type=='department_head' || user?.type=='document_controller'">
              <router-link to="/department/dashboard" ><i class="bi bi-file-earmark-text"></i> <span class="px-4">Dashboard</span></router-link>
            </li>
          
            <li class="nav-bar " v-if="user?.type=='admin' || user?.type=='auditor'">
                <router-link to="/academic" ><i class="bi bi-building"></i> <span class="px-4">Academic</span></router-link>
            </li>
            <li class="nav-bar " v-if="user?.type=='admin' || user?.type=='auditor'">
                <router-link to="/non-academic" ><i class="bi bi-building"></i> <span class="px-2">Non-Academic</span></router-link>
            </li>
            <li class="nav-bar " v-if="user?.type=='admin'">
                <router-link to="/auditor" ><i class="bi bi-card-checklist"></i> <span class="px-4">Auditor</span></router-link>
            </li>
            <li class="nav-bar " v-if=" user?.type=='document_controller'">
                <router-link to="/archive/1" ><i class="bi bi-archive"></i> <span class="px-4">Archive</span></router-link>
            </li>
            <li class="nav-bar"  v-if="user?.type=='auditor' || user?.type=='document_controller' || user?.type=='admin'" >
                <router-link to="/audit-reports" ><i class="bi bi-book"></i> <span class="px-4">Audit Report</span></router-link>
            </li>
            <li class="nav-bar" >
                <router-link to="/announcement" ><i class="bi bi-megaphone"></i> <span class="px-3">Announcement</span></router-link>
            </li>
          </ul>
      </div>
  </div>
  
</template>

<script setup>
import store from "../store";
 
import { computed, ref } from "vue";
import { useRoute } from "vue-router";
 
 
const user = ref(computed(() => store.state.auth.user))|| {
  fullname:"user"
};

const route = useRoute()
const currentRouteName = computed(() => route.name)

 

</script>

<style scoped>
*{
margin: 0;
padding: 0;
box-sizing: border-box;
list-style: none;
text-decoration: none;
font-family: 'Poppins', sans-serif;
}
.img-logo{
height: 90px;
width: auto;
transition: all .4s ease-in-out;
}
.underline{
margin-top: 10px;
color: #444444;
}
.wrapper{
display: flex;
position: relative;
}

.wrapper .sidebar{
width: 207px;
height: 100%;
/* background: #16796F; */
padding: 30px 0px;
position: relative;
transition: all .4s ease-in-out;

}
.wrapper .sidebar ul{
margin-top: 100px;
padding: 5px;
transition: all .4s ease-in-out;

}
.wrapper .sidebar ul .nav-bar{
padding: 10px;
border-radius: 5px;
/* transition: all .4s ease; */
}    
.wrapper .sidebar ul .nav-bar a{
color: #000000;
display: block;
/* transition: all .2s ease-out; */

}
.wrapper .sidebar ul .nav-bar:hover{
background-color: rgb(0, 165, 142);
}
a{
color: inherit;
}
.wrapper .sidebar ul .nav-bar:hover a{
color: #fff;
}
@media only screen and (max-device-width: 500px) {
    .img-logo{
      height: 50px;
    }
    .wrapper .sidebar{
      width: 100px;
      height: 100%;
      padding: 30px 0px;
      position: relative;
      transition: all .4s ease-in-out;
    }
    .logo-name{
      font-size: 10px;
    }
    .nav-bar{
      justify-content: center;
      display: flex;
    }
    .nav-bar span{
      display: none;
      transition: all .4s ease-in-out;
    }
}
</style>
