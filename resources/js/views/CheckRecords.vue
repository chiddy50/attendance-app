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

        <div class="pagination-box" v-if="noEmployee != 'nothing'">
            <button class="paginationBtn firstPage" @click="firstPage" :class="{fade: disableFirst}" :disabled="disableFirst">First</button> 
            <button class="paginationBtn" @click="prevPage" :class="{fade: disablePrev}" :disabled="disablePrev">Previous</button> 
            <!-- <button class="paginationBtn">This of That</button>  -->
            <button class="paginationBtn" @click="nextPage" :class="{fade: disableNext}" :disabled="disableNext">Next</button> 
            <button class="paginationBtn lastPage" @click="lastPage" :class="{fade: disableLast}" :disabled="disableLast">Last</button> 
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
                    <tr v-for="employee in paginationUsers" :key="employee.id">
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
    name: 'checkRecords',
    
    data(){
        return {
            teamId: null,
            loading: false,
            searchText: null,
            employees: [],
            noEmployee: 'no',

            paginationUsers: [],
            dataOffset: 0,
            perpage: 4, //but 3 actually bcus index starts from 0 not 1
            currentPage: 1,
            dataLength: 0,
            disablePrev: true,
            disableNext: true,
            disableLast: true,
            disableFirst: true
        }
    },
    computed: {
        getNumberOfPages() {
            return Math.ceil(this.employees.length / this.perpage);
        },
    },
    methods: {
        ...mapActions(['getTeams']),
        getTeamEmployees(){
            if (!this.teamId) {
                return
            }
            let self = this
            this.loading = true
            this.resetPagination()

            axios.get(`api/team-employees/${this.teamId}`)
            .then(response => {
                console.log(response);
                self.employees = response.data
                if (!response.data.length) {
                    self.noEmployee = "yes"
                }else{
                    self.noEmployee = "no"
                    self.addPagination(response.data)
                }
            })
            .catch(error => console.log(error))
            .finally(() => self.loading = false)
        },

        liveSearch(){
            let self = this
            this.loading = true
            this.resetPagination()

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
                    self.addPagination(response.data)
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => self.loading = false)
        },

        addPagination(data){
            this.dataLength = data.length;

            var pageData = data.slice(this.dataOffset, this.perpage);

            if (this.dataLength > this.perpage) {
                this.disableNext = false
                this.disableLast = false
            }else{
                this.disableNext = true
                this.disableLast = true
            }

            if (this.currentPage == 1) {
                this.disablePrev = true
            }

            this.paginationUsers = pageData;
        },

        nextPage(){
        this.currentPage += 1
        this.load()
        },

        prevPage(){
        this.currentPage -= 1
        this.load()
        },

        firstPage() {
        this.currentPage = 1;
        this.load();
        },

        lastPage() {
        this.currentPage = this.getNumberOfPages;
        this.load();
        },

        load(){
        var begin = ((this.currentPage - 1) * this.perpage);
        var end = begin + this.perpage;
        this.paginationUsers = this.employees.slice(begin, end);
        },

        resetPagination(){
        this.paginationUsers = []
        this.dataOffset = 0
        this.currentPage = 1
        this.dataLength = 0
        this.disablePrev = true
        this.disableNext = true
        this.disableLast = true
        this.disableFirst = true
        },

        
    },

    watch: {
        currentPage(val){
        if (this.currentPage == this.getNumberOfPages) {
            this.disableNext = true
            this.disableLast = true
        }else{
            this.disableNext = false
            this.disableLast = false
        }

        if (val != 1 || this.currentPage != 1 || this.currentPage > 1) {
            this.disablePrev = false
            this.disableFirst = false
        }else{
            this.disablePrev = true
            this.disableFirst = true
        }
        }
    }
}
</script>


<style scoped>
.flex{
  display: flex;
  justify-content: space-between;
}
</style>


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


