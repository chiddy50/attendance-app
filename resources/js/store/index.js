const Vue = require('vue');
const Vuex = require('vuex');
const VueCookies = require('vue-cookies');
const Swal = require('sweetalert2');
Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        initialState: {
            loggedIn: false,
            token: null,
            username: null
        },
        jwt: VueCookies.get('token'),
        logoutNow: false,

        staffs: [],
        teams: [],
        months: [],
        teamChartData: []
    },
    getters: {
        loggedIn: function(state){
            if(state.initialState.token === null && VueCookies.get('token') !== null) {
                var token = VueCookies.get('token');
                state.initialState.token = JSON.parse(token);
            }
            
            var loggedIn = state.initialState.token !== null;
            return loggedIn;
        },
        role: function(state){
            let roles = JSON.parse(localStorage.getItem('roles'));
            if (roles) {
                if (roles.some(role => role === 'admin')) {
                    return 'Admin'
                }else{
                    return 'User'
                }
            }else{
                return null
            }
        }
    },
    mutations: {
        LOGIN_SUCCESS(state, user) {
            state.initialState.loggedIn = true;
            state.initialState.token = user.access_token
            state.jwt = VueCookies.get('token')
        },
        REMOVE_TOKEN(state){
            VueCookies.remove('token');
            state.jwt = null;
            state.initialState.loggedIn = false;
            state.initialState.token = null;
            localStorage.removeItem('user');
            localStorage.removeItem('roles');
        },
        UPDATE_TOKEN(state, newToken){
            state.initialState.loggedIn = true;
            state.initialState.token = newToken;
            state.jwt = newToken;
        },

        MUTATE_TEAMS(state, data){
            state.teams = data
        },

        MUTATE_MONTHS(state, data){
            state.months = data
        },

        MUTATE_CHART_DATA(state, data){
            state.teamChartData = data;
        }

        
    },
    actions: {
        authenticate({ state }, user) {
            return axios.post("api/login", {
                email: user.email,
                password: user.password
            })
            .then(response => {
                // console.log(response.data);
                let token = response.data.access_token;
                if (response.data.access_token) {
                    VueCookies.set('token', JSON.stringify(token));
                    localStorage.setItem('user', JSON.stringify(response.data.user.name));
                    localStorage.setItem('roles', JSON.stringify(response.data.roles));
                }
                return response.data;
            });
        },

        login({ commit, dispatch }, user){
            return dispatch("authenticate", user)
            .then(
              user => {
                commit('LOGIN_SUCCESS', user);
                return Promise.resolve(user);
              },
              error => {
                commit('REMOVE_TOKEN');
                return Promise.reject(error);
              }
            )
        },
  
        logout({ commit }) {
            let token = JSON.parse(VueCookies.get('token'));

            axios({
            method: 'get',
            url: 'http://sample.test/api/logout',
            headers: {'Authorization': `Bearer ${token}`}
            })
            .then(response => {
                // console.log(response);
                commit('REMOVE_TOKEN');
                vueRouter.push('/login');
                Swal.fire(response.data.message, '', 'error') 
            })
            .catch(err => {
                console.error(err)
                commit('REMOVE_TOKEN');
                vueRouter.push('/login');
                Swal.fire(response.data.message, '', 'error') 
            })
        },

        getTeams({ commit }){
            axios.get("api/teams")
            .then(response => {
                // console.log(response);
                commit('MUTATE_TEAMS', response.data.data)
            })
            .catch(err => {
                console.error(err);
            })
        },

        getMonths({ commit }){
            axios.get("api/months")
            .then(response => {
                // console.log(response);
                commit('MUTATE_MONTHS', response.data.data)
            })
            .catch(err => {
                console.error(err);
            })
        },

        getChartData({ commit }){
            axios.get("api/team-data")
            .then(response => {
                // console.log(response.data); 
                commit("MUTATE_CHART_DATA", response.data)
            })
            .catch(err => {
                console.error(err);
            })
        }

        
    },
});

// module.exports = store;