<template>
  <!-- <div class="col-sm-12 col-lg-6">
    <h1 class="text-muted">Sign In</h1>
    <form @submit.prevent="login">
      <label for="email">Email</label>
      <input type="email" v-model="email" class="form-control">
      <label for="password">Password</label>
      <input type="password" v-model="password" class="form-control">
      <button type="submit" class="btn btn-info mt-4">Login</button>
    </form>
  </div> -->
    <div class="row">
      <div class="form__container">
        <h1 class="text-muted">Sign In</h1>
        <form @submit.prevent="login">
          <label for="email">Email</label>
          <input type="email" v-model="email">
          <label for="password">Password</label>
          <input type="password" v-model="password">
          <button type="submit" :disabled="!email || !password" class="button-submit">Login</button>
        </form>
      </div>
    </div>
</template>

<script>
export default {
    name: 'login',
    data(){
      return {
        email: null,
        password: null
      }
    },
    methods: {
      login(){
        // console.log(this.$store);
        let self = this;
        let user = {
          email: self.email,
          password: self.password
        }

        this.$store.dispatch("login", user)
        .then(response => {
          // console.log(response);
          self.$router.push('/')
          window.location.reload()
        },
        error => {
          console.log(error);
        })
        .catch(err => console.error(err))
      }
    },
    beforeMount(){
      if (this.$store.getters.loggedIn) {
        this.$router.push('/')
      }else{
        console.log('You are not logged in');
      }

    }
}
</script>

<style>
.form__container{
  /* border: 1px dashed #000; */
}

.form__container h1{
  font-size: 4rem;
  margin-bottom: 3rem;
}

.form__container label,
.form__container input,
.form__container select
{
  display: block;
}

.form__container label{
  font-size: 1.7rem;
  margin: 0.4rem 0;
}

.form__container input{
  padding: 0.8rem;
}

.form__container select{
  padding: 1rem;
}


.form__container input,
.form__container select
 {
  border: 1px solid #888;
  border-radius: 5px;
  margin: 0 0 3rem;
  transition: all .2s;
}

.form__container input:focus {
  outline: none;
  box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
}

.button-submit, .button-submit:link, .button-submit:visited {
  text-transform: uppercase;
  text-decoration: none;
  padding: 1.4rem 2rem;
  display: inline-block;
  border-radius: 10rem;
  transition: all 0.2s;
  position: relative;
  font-size: 1.4rem;
  border: none;
  outline: none;
  cursor: pointer; 
  /* background: orangered; */
  background-image: linear-gradient(
    to right bottom, 
    var(--color-primary-light), 
    var(--color-primary-dark));
  color: #fff;
}

.btn:active, .btn:focus {
  outline: none;
}
</style>