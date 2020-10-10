<template>
    <div class="row">
        <div class="form__container">
            <h1 class="text-muted">Add Staff details</h1>
            <form class="staff__form" @submit.prevent="addStaff">
                <label for="firstname">Firstname</label>
                <input type="text" class="staff__input firstname" name="firstname" v-model="firstname">

                <label for="lastname">Lastname</label>
                <input type="text" class="staff__input lastname"  name="lastname" v-model="lastname">

                <label for="username">Username</label>
                <input type="text" class="staff__input username" name="username" v-model="username">

                <label for="email">E-mail</label>
                <input type="email" class="staff__input email" name="email" v-model="email">

                <label for="designation">Designation</label>
                <input type="text" class="staff__input designation" name="designation" v-model="designation">

                <label for="team">Team</label>
                <select name="team" id="team" class="staff__input team" v-model="team">
                    <option disabled selected :value="null">Please Select a Team</option>
                    <option :value="team.id" v-for="team in $store.state.teams" :key="team.id">
                        {{ team.name }}
                    </option>
                </select>
                <button type="submit" :disabled="loading" class="button-submit">Create</button>
            </form>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
// import { mapState } from 'vuex'

export default {
    name: 'addStaff',
    data(){
        return {         
            firstname: null,
            lastname: null,
            username: null,
            email: null,
            designation: null,
            team: null,

            loading: false
        }
    },
    
    methods: {
        
        addStaff(){
            let self = this
            if (!this.firstname) {
                this.showError('error', 'Firstname is required', 'firstname')   
                return
            }else if (!this.lastname) {
                this.showError('error', 'Lastname is required', 'lastname')                   
                return
            }else if (!this.username) {
                this.showError('error', 'Username is required', 'username')                   
                return
            }else if (!this.email) {
                this.showError('error', 'Email is required', 'email')                   
                return
            }else if (!this.designation) {
                this.showError('error', 'Designation is required', 'designation')                   
                return
            }else if (!this.team) {
                this.showError('error', 'Team is required', 'team')                   
                return
            }

            this.loading = true
            this.clearError()

            axios.post("api/add-staff", {
                firstname: this.firstname,
                lastname: this.lastname,
                username: this.username,
                email: this.email,
                designation: this.designation,
                team: this.team
            })
            .then(response => {
                console.log(response);
                self.resetForm();
                Swal.fire({
                    position: 'top-end',
                    icon: `success`,
                    title: `Successfully added a staff`,
                    showConfirmButton: false,
                    timer: 1500
                })
            })
            .catch(err => console.log(err))
            .finally(() => self.loading = false )
        },

        showError(type, message, name){
            this.clearError()
            let inputname = name.toString();
            let input = document.querySelector(`.${inputname}`);
            input.classList.add("error");

            Swal.fire({
                position: 'top-end',
                icon: `${type}`,
                title: `${message}`,
                showConfirmButton: false,
                timer: 1500
            })    
        },

        clearError(){
            const staffInputs = document.getElementsByClassName("staff__input");
            for (let index = 0; index < staffInputs.length; index++) {
                staffInputs[index].classList.remove('error')
            }
        },

        resetForm(){
            this.firstname = null
            this.lastname = null
            this.username = null
            this.email = null
            this.designation = null
            this.team = null
        }
    }
}
</script>

<style>
input.error{
    border: 1px solid red;
}
</style>
