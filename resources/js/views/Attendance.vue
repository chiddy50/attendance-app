<template>
  <div class="row">
    <h1>CHOOSE A TEAM</h1>
    <div class="form__container">
      <form class="attendance__form">
        <div class="flex">
          <!-- <div class="col-1-3"> -->
            <select name="team" id="team" 
            :disabled="loading" class="staff__input team" 
            v-model="teamId" @change="getTeamEmployees">
              <option :value="null" disabled selected>Please Select a Team</option>
              <option :value="team.id" v-for="team in $store.state.teams" :key="team.id">
                  {{ team.name }}
              </option>
            </select>
          <!-- </div> -->
          <!-- <div class="col-1-3"> -->
            <select name="month" id="month" :disabled="loading" class="staff__input month" v-model="month">
              <option :value="null" disabled selected>Choose Month</option>
              <option :value="month.id" v-for="month in $store.state.months" :key="month.id">
                  {{ month.name }}
              </option>
            </select>
          <!-- </div> -->
          <!-- <div class="col-1-3"> -->
            <input type="date" name="date" id="date" v-model="date" class="staff__input date">
          <!-- </div> -->
          
        </div>
      </form>
    </div>

    <div class="pagination-box" v-if="noEmployee != 'nothing'">
      <button class="paginationBtn firstPage" @click="firstPage" :class="{fade: disableFirst}" :disabled="disableFirst">First</button> 
      <button class="paginationBtn" @click="prevPage" :class="{fade: disablePrev}" :disabled="disablePrev">Previous</button> 
      <button class="paginationBtn" v-if="noEmployee == 'There is data'" disabled>Page {{ currentPage }} of {{ getNumberOfPages }}</button> 
      <button class="paginationBtn" @click="nextPage" :class="{fade: disableNext}" :disabled="disableNext">Next</button> 
      <button class="paginationBtn lastPage" @click="lastPage" :class="{fade: disableLast}" :disabled="disableLast">Last</button> 
    </div>


    <div style="overflow-x:auto;" v-if="noEmployee != 'nothing'">
      <form @submit.prevent="recordAll($event)">
        <table class="attendance__table">
          <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Present?</th>
            <th>Record</th>
          </tr>
          <tr v-for="employee in paginationUsers" :key="employee.id">
            <td>{{ employee.firstname }}</td>
            <td>{{ employee.lastname }}</td>
            <td>
              <label>Present</label>
              <input type="radio" class="present_field" :name="'attendance['+[employee.id]+']'" value="Present">
              <label>Absent</label>
              <input type="radio" class="present_field" :name="'attendance['+[employee.id]+']'" value="Absent">
            </td>
            <td>
              <button type="button" class="single_attendance" @click.prevent="record($event,employee.id)">
                  <i class="fas fa-check"></i>
              </button>
              <i class="fas fa-spinner fa-spin spinner-md"></i>

            </td>
          </tr>
        </table>

        <button type="submit" :disabled="loading" 
        class="button-submit mm-y" v-if="employees.length"
        v-text="loading ? 'Recording...': 'Record' "
        ></button>

      </form>
    </div>

    <ul class="list__group" v-if="noEmployee == 'nothing'">
      <li>No employees in this team</li>
    </ul>
    <ul class="list__group" v-if="dataLoading">
      <li>Loading data.....</li>
    </ul>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import Swal from 'sweetalert2'

export default {
  name: 'attendance',
  data(){
    return {
      date: null,
      month: null,
      teamId: null,
      loading: false,
      dataLoading: false,
      employees: [],
      noEmployee: 'waiting',

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
      this.resetPagination()
      this.dataLoading = true
      axios.get(`api/team-employees/${this.teamId}`)
      .then(response => {
        console.log(response);
        self.employees = response.data
        if (!response.data.length) {
            self.noEmployee = "nothing"
        }else{
            self.noEmployee = "There is data"
            self.addPagination(response.data)
        }
      })
      .catch(error => {
        console.log(error.response)
        Swal.fire({
            position: 'top-end',
            icon: `error`,
            title: 'Network error please try again',
            showConfirmButton: false,
            timer: 1500
          })
      })
      .finally(() => self.dataLoading = false)
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
      this.noEmployee = 'waiting'
      this.paginationUsers = []
      this.dataOffset = 0
      this.currentPage = 1
      this.dataLength = 0
      this.disablePrev = true
      this.disableNext = true
      this.disableLast = true
      this.disableFirst = true
    },

    recordAll(e){
      let self = this
      if (!this.date) {
        this.dateError()
        return
      }else if (!this.month) {
        this.monthError()
        return
      }

      this.loading = true
      let formData = new FormData(e.target);
      formData.append('date', this.date)
      formData.append('month', this.month)
      axios.post("api/attendance-all", formData)
      .then(response => {
        console.log(response);
        Swal.fire({
          title: '<strong>SUCCESS</strong>',
          icon: 'success',
          html:
              `${response.data.created[0]} record/s created` + '<br>' +
              `${response.data.updated[0]} record/s updated`,
          showCloseButton: true,
          showCancelButton: true,
          focusConfirm: false,
          confirmButtonText:
              '<i class="fa fa-thumbs-up"></i> Great!',
          confirmButtonAriaLabel: 'Thumbs up, great!',
          cancelButtonText:
              '<i class="fa fa-thumbs-down"></i>',
          cancelButtonAriaLabel: 'Thumbs down'
        });
      })
      .catch(err => {
        console.log(err);
      })
      .finally(() => self.loading = false)
    },
  
    record(e, id){
      var status, target, spinner;
      if (!this.date) {
        this.dateError()
        return
      }else if (!this.month) {
        this.monthError()
        return
      }

      if (e.target.classList.contains('single_attendance')) {
        target = e.target;
        target.hidden = true;
        spinner = e.target.nextElementSibling;
        spinner.classList.remove('spinner-md');
        if (e.target.parentElement.previousElementSibling.children[1].checked) {
          status = e.target.parentElement.previousElementSibling.children[1].value;
        }else if(e.target.parentElement.previousElementSibling.children[3].checked){
          status = e.target.parentElement.previousElementSibling.children[3].value;
        }             
      //Check if icon tag inside button was clicked
      }else if (e.target.classList.contains('fa-check')) {
        target = e.target.parentElement;
        target.hidden = true;
        spinner = e.target.parentElement.nextElementSibling;
        spinner.classList.remove('spinner-md');
        if (e.target.parentElement.parentElement.previousElementSibling.children[1].checked) {
          status = e.target.parentElement.parentElement.previousElementSibling.children[1].value;
        }else if(e.target.parentElement.parentElement.previousElementSibling.children[3].checked){
          status = e.target.parentElement.parentElement.previousElementSibling.children[3].value;
        }                     
      }

      // console.log(status, target, spinner);
      // return

      let formData = new FormData();

      axios.post("api/attendance", {
        employee_id: id,
        present: status,
        date: this.date,
        month: this.month
      })
      .then(response => {
        console.log(response);
        if (!response.data.error) {
          Swal.fire({
            position: 'top-end',
            icon: `success`,
            title: response.data.message,
            showConfirmButton: false,
            timer: 1500
          })
        }else{
          Swal.fire({
            position: 'top-end',
            icon: `error`,
            title: response.data.message,
            showConfirmButton: false,
            timer: 1500
          })
        }
      })
      .catch(err => console.log(err))
      .finally(() => {
        this.singleRecordLoading = false
        target.hidden = false;
        spinner.classList.add('spinner-md');
      })

    },

    dateError(){
      Swal.fire({
        position: 'top-end',
        icon: `error`,
        title: `You have to pick a date`,
        showConfirmButton: false,
        timer: 1500
      })
    },

    monthError(){
      Swal.fire({
        position: 'top-end',
        icon: `error`,
        title: `You have to pick a month`,
        showConfirmButton: false,
        timer: 1500
      })
    }
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
.mm-y{
  margin: 2rem 0 2rem;
}

.row h1{
  margin: 1rem 0 3rem;
  color: #000;
  font-size: 3rem;
}

.spinner{
  font-size: 2rem;
}

ul.list__group li button {
  float: right;
}


table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th{
  color: #000;
  font-size: 1.6rem;
}

td{
  font-size: 1.4rem;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

i.spinner-md{
    display: none;
}

/* BUTTON STYLES */
.pagination-box{
  display: flex;
  margin-bottom: 2rem;
}

button.paginationBtn,
button.paginationBtn:visited,
button.paginationBtn:active
{
    display: block;
    padding: 0.4rem 1.4rem;
    background: #4b90d4;
    color: #fff;
    border: none;
    margin: 0 0.1rem;
    cursor: pointer;
    outline: none;
}

.fade {
  opacity: 0.5;
}

.firstPage {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}

.lastPage {
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
</style>