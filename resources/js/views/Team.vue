<template>
    <div class="row">
        <div class="form__container">
            <h1 class="text-muted">Add a Team</h1>
            <form @submit.prevent="addTeam">
            <label for="teamName">Team Name</label>
            <input type="text" id="teamName" class="teamName" v-model="teamName">
            <button type="submit" :disabled="loading" class="button-submit" v-text="loading?'Creating..':'Add'"></button>
            </form>
        </div>

        <div class="teams">
            <ul class="list__group">
                <li class="team" v-for="team in $store.state.teams" :key="team.id">{{ team.name }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import { mapActions } from 'vuex'

export default {
    name: 'team',
    data(){
        return {
            teamName: null,
            loading: false
        }
    },
    beforeMount(){
        this.getTeams()
    },
    methods: {
        ...mapActions(['getTeams']),
        addTeam(){
            let self = this
            if (!this.teamName) {
                this.showError('error', 'Please provide a name')   
                return
            }

            this.loading = true
            this.clearError()

            axios.post("api/team", {
                name: self.teamName
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
                        title: `Successfully added a team`,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    self.$store.state.teams.push(response.data)
                }
                    self.teamName = ""
            })
            .catch(err => console.log(err))
            .finally(() => self.loading = false )
        },

        showError(type, message){
            this.clearError()
            document.querySelector(".teamName").classList.add("error");

            Swal.fire({
                position: 'top-end',
                icon: `${type}`,
                title: `${message}`,
                showConfirmButton: false,
                timer: 1500
            })    
        },

        clearError(){
            document.querySelector(".teamName").classList.remove('error')        
        },

        
    }
}
</script>

<style>
.teams{
    /* border: 1px dashed; */
    width: 100%;
    margin: 5rem 0 0;
}

/* li.team {
    list-style: none;
    font-size: 1.6rem;
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    padding: 2rem 0rem;
    text-align: center;
    font-weight: bold;
    margin: 2rem 0;
    transition: all .3s;
    cursor: pointer;
    text-transform: uppercase;
}

li.team:hover {
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
} */

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