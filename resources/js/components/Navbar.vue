<template>
    <!-- <div>
        <nav class="navbar navbar-expand expand-sm navbar-dark bg-info mb-2">
            <div class="container">
                <router-link to="/articles" tag="a" v-if="loggedIn" class="text-light">Articles</router-link>
                <p @click="logout" v-if="loggedIn" class="text-light other-links">Logout</p>
                <p v-if="loggedIn" class="text-light other-links">{{ authUser }}({{ role }})</p>
                <router-link to="/login" tag="a" v-if="!loggedIn" class="text-light">Login</router-link>
                <router-link to="/register" tag="a" v-if="!loggedIn" class="text-light">Register</router-link>
            </div>
        </nav>
    </div> -->

    <div class="top-nav">
        <i class="fas fa-bars" id="menu-toggle"></i>
        <p class="active" v-if="loggedIn" to="/">{{ authUser }}<span id="roleText">{{ role }}</span></p>
        <router-link to="/login" tag="a" v-if="!loggedIn">Login</router-link>
        <router-link to="/register" tag="a" v-if="!loggedIn">Register</router-link>
        <p @click="logout" v-if="loggedIn">Logout</p>
      </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import VueCookies from 'vue-cookies'
import Swal from 'sweetalert2'


export default {
    name: 'navbar',
    data(){
        return {
            user: JSON.parse(localStorage.getItem('user')) || null
        }
    },
    computed: {
        ...mapGetters(['loggedIn']),
        authUser(){
            let user;
            if (JSON.parse(localStorage.getItem('user'))) {
                user = JSON.parse(localStorage.getItem('user'));   
            }else{
                user = null
            }
            
            return user
        },
        role(){
            let roles = JSON.parse(localStorage.getItem('roles'));
            // console.log(roles);
            if (roles) {
                if (roles.some(role => role === 'admin')) {
                    return 'Admin'
                }else{
                    return 'User'
                }
                
            }
        }
    },
    mounted(){
        this.checkAdmin()
        // this.$store.dispatch('tester');
    },
    methods: {
        // logout() {
        //     let token = JSON.parse(VueCookies.get('token'));

        //     axios({
        //         method: 'get',
        //         url: 'http://sample.test/api/logout',
        //         headers: {'Authorization': `Bearer ${token}`}
        //     })
        //     .then(response => {
        //         console.log(response);
        //         this.$store.commit('REMOVE_TOKEN');
        //         this.$router.push('/login');
        //         Swal.fire(response.data.message, '', 'error') 
        //     })
        //     .catch(err => console.error(err))

        // },
        ...mapActions(['logout']),
        checkAdmin(){
            let self = this
            if (VueCookies.isKey('token')) {
                let token = JSON.parse(VueCookies.get('token'));
                axios({
                    method: 'get',
                    url: 'http://sample.test/api/admin',
                    headers: {'Authorization': `Bearer ${token}`}
                })
                .then(res => {
                    console.log(res);
                })
                .catch(error => {
                    if (error.response.status === 500) {
                        self.logout()
                    }
                    console.log(error.response.data);
                    console.log(error.response.status);
                    console.log(error.toJSON());
                })
            }
        }
    }
}
</script>

<style lang="scss" scoped>
p.other-links {
    margin: 0;
    cursor: pointer;
}

p.active {
    text-transform: uppercase;
    position: relative;
}

p.active span {
    text-transform: capitalize;
}

span#roleText {
    position: absolute;
    top: 3.6rem;
    right: 2px;
    font-size: 0.9rem;
    background: #2c6296;
    padding: 3px;
    border-radius: 6px;
}
</style>