<template>
    <div class="row">
        <h1 v-if="staff">Check {{ staff.firstname }} {{ staff.lastname }}'s record</h1>
        <div class="flex staff__record">
            <div class="form__container">
                <form class="staff__form">
                    <label for="date">Pick a month</label>
                    <select v-model="month" id="month" @change="getMonthRecord" :disabled="!$store.state.months.length">
                        <option :value="null" disabled selected>Choose a month</option>
                        <option :value="month.id" v-for="month in $store.state.months" :key="month.id">
                            {{ month.name }}
                        </option>
                    </select>
                </form>
            </div>

            <div class="form__container">
                <form class="staff__form">
                    <label for="date">Pick a date</label>
                    <input type="date" @change="getDayRecords" v-model="date" class="staff__input date">
                </form>
            </div>

            
        </div>
        <table class="attendance__table" v-if="!hideTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody v-if="!loading">
                <tr v-for="record in records" :key="record.id">
                    <td v-text="formatDate(record.date)"></td>
                    <td :class="stylePresent(record.present)" v-text="formatPresent(record.present)"></td>
                </tr>
            </tbody>
            <tr v-else class="spinner-row">
                <td colspan="3">
                    Searching....
                </td>       
            </tr> 
            <tr v-if="noRecord === 'yes'">
                <td colspan="3">No record found.</td>
            </tr>
        </table>

        <table v-else>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <tr v-if="record">
                <td v-text="formatDate(record.date)"></td>
                <td :class="stylePresent(record.present)" v-text="formatPresent(record.present)"></td>
            </tr>
        </table>

        <h4 class="loadingText" v-if="firstLoading">Loading, please wait....</h4>

    </div>
</template>

<script>
import moment from 'moment';

export default {
    name: 'staffRecord',
    data(){
        return {
            staff: null,
            date: null,
            month: null,
            loading: false,
            noRecord: 'waiting',
            hideTable: false,
            records: [],
            record: null,
            firstLoading: true
        }
    },
    mounted(){
        console.log(this.$route.params.id);
        // this.getRecords()
    },
    beforeCreate(){
        axios.get(`api/staff/${this.$route.params.id}`)
        .then(response => {
            // console.log(response);
            this.staff = response.data
        })
        .catch(err => console.log(err))
        .finally(() => this.firstLoading = false)
    },
    beforeDestroy(){
        this.staff = null
    },
    methods: {
        getMonthRecord(){
            let self = this
            this.firstLoading = true
            console.log(this.month);
            this.hideTable = false
            axios.post('api/month-records', {
                employee_id: this.$route.params.id,
                month_id: this.month
            })
            .then(response => {
                // console.log(response)
                self.records = response.data.records
                if (!response.data.records.length) {
                    self.noRecord = "yes"
                }else{
                    self.noRecord = "no"
                }
            })
            .catch(err => {
                console.error(err.response);
            })
            .finally(() => this.firstLoading = false)

        },
        getDayRecords(){
            let self = this
            this.hideTable = true
            this.firstLoading = true

            axios.post('api/date-record', {
                employee_id: this.$route.params.id,
                date: this.date
            })
            .then(response => {
                console.log(response.data[0])

                self.record = response.data[0]
            })
            .catch(err => {
                console.error(err.response);
            })
            .finally(() => this.firstLoading = false)
        },
        
        stylePresent(status){
            if (status == 1) {
                return "present"
            }else{
                return "absent"
            }
        },

        formatPresent(status){
            return status == 1 ? "Present" : "Absent";
        },
        formatDate(date) {
            return moment(date).format('LL');
        }, 
    }
}
</script>

<style scoped>
#month,
.date{
    width: 100%;

}

.loadingText{
    font-size: 2rem;
    margin-top: 2rem;
    text-align: center;
}

.flex{
  display: flex;
  justify-content: space-evenly;
}

td.present{
    color: rgb(40, 151, 40);    
}

td.absent {
    color: rgb(218, 31, 31);
}
</style>