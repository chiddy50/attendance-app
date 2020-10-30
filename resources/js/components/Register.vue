<template>

  <div class="row">
    <div class="form__container">
      <h1 class="text-muted">Please register</h1>
      <form @submit.prevent="register($event)">
        <label for="name">Name</label>
        <input type="text" name="name" v-model="name" required>

        <label for="email">Email</label>
        <input type="email" name="email" v-model="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" v-model="password" required>

        <label for="confirm_password">Confirm password</label>
        <input type="password" name="confirm_password" v-model="confirm_password" class="staff__input" required>

        <h1 class="assign__text">Assign Role/s</h1>
        <input class="checkbox" 
              type="checkbox" 
              id="checkbox-1" 
              name="admin"
              :value="2"
              />
        <label for="checkbox-1">Admin</label>

        <input class="checkbox" 
              type="checkbox" 
              id="checkbox-2" 
              name="user"
              :value="1"
              />
        <label for="checkbox-2">User</label>
        
        <button type="submit" 
          :disabled="!email || !password || loading" 
          class="button-submit btn-register" 
          v-text="loading?'Please wait..':'Register'"></button>
      </form>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import VueCookies from 'vue-cookies'
export default {
    name: 'register',
    data(){
      return {
        name: null,
        email: null,
        password: null,
        confirm_password: null,
        admin: null,
        user: null,
        loading: false
      }
    },
    methods: {
      register(e){
        if (this.password !== this.confirm_password) {
          this.alert('error', `Password mismatch`, 2000);
          return;
        }
        this.loading = true
        let self = this
        let formData = new FormData(e.target);
        // axios.post("api/register", {
        //   name: this.name,
        //   email: this.email,
        //   password: this.password
        // })
        axios.post("api/register", formData)
        .then(response => {
          // console.log(response);
          self.$router.push('/')
          let token = response.data.access_token;
          VueCookies.set('token', JSON.stringify(token));
          localStorage.setItem('user', JSON.stringify(response.data.user.name));
          localStorage.setItem('roles', JSON.stringify(response.data.roles));
          self.$store.commit('LOGIN_SUCCESS', response.data)
          window.location.reload()

        })
        .catch(err => {
          console.log(err.response.status)
          if (err.response.status === 401) {
            this.alert('error', err.response.data.error, 1500)
            return;
          }
          this.alert('error', `Network error`, 1500)
          
        })
        .finally(() => self.loading = false)
      },

      alert(type, message, duration){
        Swal.fire({
          position: 'top-end',
          icon: type,
          title: message,
          showConfirmButton: false,
          timer: duration
        }) 
      }
    }
}
</script>



<style scoped>
button.btn-register {
  display: block;
  margin-top: 2rem;
}

.assign__text {
  font-size: 1.7rem;
  margin: 1rem 0 0;
}

.checkbox {
  position: absolute;
  left: -9999px;
}
.checkbox + label {
  position: relative;
  display: inline-block;
  padding: 6px 30px;
  cursor: pointer;
}
.checkbox + label:before, .checkbox + label:after {
  position: absolute;
  top: 0;
  content: '';
  border-radius: 1px;
}
.checkbox + label:before {
  top: 4px;
  left: 0;
  width: 18px;
  height: 18px;
  border: 1px solid #CCC;
  background-color: #fff;
}
.checkbox + label:after {
  /* hidden */
  width: 0;
  height: 0;
}
.checkbox:checked + label:before {
  border-color: #0096D9;
  background-color: #0096D9;
}
.checkbox:checked + label:after {
  width: 6px;
  height: 13px;
  top: 3px;
  left: 5px;
  color: #fff;
  border-top: 2px solid transparent;
  border-left: 2px solid transparent;
  border-right: 2px solid #fff;
  border-bottom: 2px solid #fff;
  -webkit-transform: rotate(37deg);
  -ms-transform: rotate(37deg);
  -o-transform: rotate(37deg);
  transform: rotate(37deg);
}
.checkbox:disabled + label {
  color: #CCC;
}
.checkbox:disabled + label:before {
  border-color: #CCC;
  background-color: #F2F2F2;
  box-shadow: none;
}
.checkbox:disabled + label:after {
  /* hidden */
  width: 0;
  height: 0;
}

</style>