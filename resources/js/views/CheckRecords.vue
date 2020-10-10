<template>
    <div class="row">
        <div class="form__container">
            <form class="staff__form">
                <label for="firstname">Search here</label>
                <input type="text" @keyup="liveSearch" v-model="searchText" class="staff__input search_input" placeholder="Enter Firstname or Lastname">
            </form>
        </div>

        <div class="form__container">
            <form class="staff__form">
                <label for="firstname">Search By team</label>
                <select name="team" id="team" :disabled="loading" class="staff__input search_input team" v-model="teamId" @change="getTeamEmployees">
                    <option :value="null" selected>Please Select a Team</option>
                    <option :value="team.id" v-for="team in $store.state.teams" :key="team.id">
                        {{ team.name }}
                    </option>
                </select>
            </form>
        </div>
        

        <div>
            <table class="attendance__table">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Designation</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody v-if="!loading">
                    <tr v-for="employee in employees" :key="employee.id">
                        <td>{{ employee.firstname }} {{ employee.lastname }}</td>
                        <td>{{ employee.designation }}</td>
                        
                        <td>
                            <!-- <button type="button" class="single_attendance">
                                <i class="fas fa-eye"></i>
                            </button> -->
                            <router-link :to="`/staff/${employee.id}`" tag="button" class="btn-own">
                                <!-- <i class="fas fa-eye"></i> -->
                                Check Records
                            </router-link>

                        </td>
                    </tr>
                </tbody>
                <tr v-else class="spinner-row">
                    <td colspan="3">
                        Searching....
                    </td>       
                </tr> 
                <tr v-if="noEmployee === 'yes'">
                    <td colspan="3">No employee found.</td>
                </tr>
            </table>

            <!-- <button @click="test">Test</button> -->
            
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    name: 'dashboard',
    
    data(){
        return {
            teamId: null,
            loading: false,
            searchText: null,
            employees: [],
            noEmployee: 'no'
        }
    },
    methods: {
        ...mapActions(['getTeams']),
        getTeamEmployees(){
            if (!this.teamId) {
                return
            }
            let self = this
            this.loading = true
            axios.get(`api/team-employees/${this.teamId}`)
            .then(response => {
                console.log(response);
                self.employees = response.data
                if (!response.data.length) {
                    self.noEmployee = "yes"
                }else{
                    self.noEmployee = "no"
                }
            })
            .catch(error => console.log(error))
            .finally(() => self.loading = false)
        },

        liveSearch(){
            let self = this
            this.loading = true

            axios.post("api/live-search", {
                search: this.searchText
            })
            .then(response => {
                console.log(response);
                self.employees = response.data
                if (!response.data.length) {
                    self.noEmployee = "yes"
                }else{
                    self.noEmployee = "no"
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => self.loading = false)
        },

        
    }
}
</script>

<style>
input.search_input,
select.search_input {
    width: 70%;
}

.spinner-row{
    text-align: center;
}

.spinner-row td{
    color: #ba265d;
    font-size: 1.6rem;
}

.btn-own {
    padding: 0.5rem;
    border: 2px solid #ff3366;
    background: transparent;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.2rem;
}

.btn-own:hover {
    background: #ff3366;
    color: #fff;
}
</style>


