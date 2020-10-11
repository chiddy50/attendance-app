<template>
    <div class="row">
        <div class="form__container">
            <h1 class="text-muted">Add a Role</h1>
            <form @submit.prevent="addRole">
            <label for="roleName">Role Name</label>
            <input type="text" id="roleName" class="roleName" v-model="roleName">
            <button type="submit" :disabled="loading" class="button-submit" v-text="loading?'Creating..':'Add'"></button>
            </form>
        </div>

        <div class="roles">
            <ul class="list__group">
                <li class="role" v-for="role in $store.state.roles" :key="role.id">{{ role.name }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import { mapActions } from 'vuex'

export default {
    name: 'role',
    data(){
        return {
            roleName: null,
            loading: false
        }
    },
    beforeMount(){
        this.getRoles()
    },
    methods: {
        ...mapActions(['getRoles']),
        addRole(){
            let self = this
            if (!this.roleName) {
                this.showError('error', 'Please provide a name')   
                return
            }

            this.loading = true
            this.clearError()

            axios.post("api/role", {
                name: self.roleName
            })
            .then(response => {
                // console.log(response);
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
                        title: `Successfully added a role`,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    self.$store.state.roles.push(response.data)
                }
                    self.roleName = ""
            })
            .catch(err => console.log(err))
            .finally(() => self.loading = false )
        },

        showError(type, message){
            this.clearError()
            document.querySelector(".roleName").classList.add("error");

            Swal.fire({
                position: 'top-end',
                icon: `${type}`,
                title: `${message}`,
                showConfirmButton: false,
                timer: 1500
            })    
        },

        clearError(){
            document.querySelector(".roleName").classList.remove('error')        
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