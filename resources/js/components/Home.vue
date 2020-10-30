<template>
  <div class="container" id="wrapper">
    <div class="side-menu" id="sidebar-wrapper">
      <div class="sidebar-heading">CODEWELL</div>
      <div class="side-menu--box">
        <span>
          <router-link to="/" tag="a" class="accordion">Dashboard</router-link>
        </span>
        <!-- <span>
          <a href="#" class="accordion dropdown" v-if="loggedIn">Staffs</a>
          <div class="panel">
            <router-link v-if="role=='Admin'" tag="a" to="/add-staff" class="inner-dropdown">Add Staff</router-link>
            <router-link to="/attendance" tag="a" class="inner-dropdown">Take Attendance</router-link>
            <a href="#" class="inner-dropdown">Home</a>
          </div>
        </span> -->
        <span>
          <router-link v-if="isAdmin && loggedIn" tag="a" to="/attendance" class="accordion">Take Attendance</router-link>
        </span>  
        <span>
          <router-link v-if="isAdmin && loggedIn" tag="a" to="/add-staff" class="accordion">Add Staff</router-link>
        </span>      
        <span>
          <router-link v-if="isAdmin && loggedIn" tag="a" to="/check-records" class="accordion">Check Records</router-link>
        </span>     
        <span>
          <router-link v-if="isAdmin && loggedIn" tag="a" to="/teams" class="accordion">Teams</router-link>
        </span>
        <span>
          <router-link v-if="isAdmin && loggedIn" tag="a" to="/month" class="accordion">Add Month</router-link>
        </span>
        
        <!-- <span>
          <a href="#" class="accordion">Section 1</a>
        </span> -->
      </div>
    </div>
    <div class="page-content">
      
      <!-- <div class="top-nav">
        <i class="fas fa-bars" id="menu-toggle"></i>
        <a class="active" href="#home">Home</a>
        <a href="#news">Login</a>
        <a href="#news">Register</a>
        <a href="#news">Logout</a>
      </div> -->
      <nav-bar></nav-bar>
      
      <section class="header">
        <router-view></router-view>
      </section>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import NavBar from "./Navbar"
import { mapActions } from 'vuex'
import VueJwtDecode from 'vue-jwt-decode'
import VueCookies from 'vue-cookies'

export default {
    name: 'home',
    components: {
      NavBar
    },  
    data(){
      return {            
        userRole: JSON.parse(localStorage.getItem('roles')) || [] 
      }
    },
    computed: {
      ...mapGetters(['role', 'loggedIn']),
      isAdmin(){
        let admin = this.userRole.some(role => { return role === 'admin' });
        return admin ? true : false
      }
      
    },
    beforeMount(){
      this.getTeams()
      this.getMonths()
      this.getChartData()
    },
    updated(){
      this.inspect()
    },
    
    methods: {
      ...mapActions(['getTeams', 'getMonths', 'getChartData']),
      inspect(){
        console.log("CHECKING...");
        const token = JSON.parse(this.$store.state.jwt);
        if (token) {
            try {
                var decoded = VueJwtDecode.decode(token);
                const exp = decoded.exp;//Issued time

                let now = Date.now() / 1000
                let currentTimestamp = parseInt(now.toFixed())
                
                let timeExpire = exp - currentTimestamp;
                // console.log(timeExpire);
                if (timeExpire <= 5) {
                  console.log("TIME IS UP!!");
                  this.$store.dispatch("logout");
                }                

            }catch (error) {
                console.log(error);
            }
        }
      },
    },
    mounted(){
      this.inspect()
      // const token = this.$store.state.jwt;
      // if (token) {
      //   let parse = JSON.parse(token)
      //   console.log(parse);
      //   var decoded = VueJwtDecode.decode(parse);
      //   console.log(decoded);
      //   this.inspect()
      // }
    }
}
</script>

<style>
:root {
  --color-primary: #eb2f64;
  --color-primary-light: #ff3366;
  --color-primary-dark: #BA265D;
}

.side-menu,
.accordion 
{
  background-image: linear-gradient(
    to right bottom, 
    var(--color-primary-light), 
    var(--color-primary-dark));
}
.sidebar-heading{
  color: #fff;
}

</style>