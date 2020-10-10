<template>
    <div class="row">
        <div class="form__container">
            <h1 class="text-muted">Create month</h1>
            <form @submit.prevent="addMonth">
            <label for="month">Month</label>
            <input type="text" id="month" class="month" v-model="month">
            <button type="submit" :disabled="loading" class="button-submit" v-text="loading?'Creating..':'Add'"></button>
            </form>
        </div>

        <div class="months">
            <ul class="list__group">
                <li class="month" v-for="month in $store.state.months" :key="month.id">{{ month.name }}</li>
                <li class="month" v-if="!$store.state.months.length">No month has been created.</li>
            </ul>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import { mapActions } from 'vuex'

export default {
    name: 'month',
    data(){
        return {
            month: null,
            loading: false
        }
    },
    beforeMount(){
        this.getMonths()
    },
    methods: {
        ...mapActions(['getMonths']),
        addMonth(){
            let self = this
            if (!this.month) {
                this.showError('error', 'Please provide a month')   
                return
            }

            this.loading = true
            this.clearError()

            axios.post("api/month", {
                name: self.month
            })
            .then(response => {
                console.log(response);
                if (response.data.error) {
                    Swal.fire({
                        position: 'top-end',
                        icon: `error`,
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }else{                    
                    Swal.fire({
                        position: 'top-end',
                        icon: `success`,
                        title: `Successfully added a month`,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    self.$store.state.months.push(response.data)
                }
                    self.month = ""
            })
            .catch(err => console.log(err))
            .finally(() => self.loading = false )
        },

        showError(type, message){
            this.clearError()
            document.querySelector(".month").classList.add("error");

            Swal.fire({
                position: 'top-end',
                icon: `${type}`,
                title: `${message}`,
                showConfirmButton: false,
                timer: 1500
            })    
        },

        clearError(){
            document.querySelector(".month").classList.remove('error')        
        },

        
    }
}
</script>

<style>
.months{
    /* border: 1px dashed; */
    width: 100%;
    margin: 5rem 0 0;
}

ul.list__group {
  list-style-type: none; /* Remove bullets */
  padding: 0; /* Remove padding */
  margin: 0; /* Remove margins */
}

ul.list__group li {
    border: 1px solid #ddd; /* Add a thin border to each list item */
    margin-top: -1px; /* Prevent double borders */
    background-color: #f6f6f6; /* Add a grey background color */
    padding: 12px; /* Add some padding */
    margin: 0.5rem 0;
    font-size: 1.5rem;
    color: #000;
    cursor: pointer;
    text-transform: uppercase;

}

</style>