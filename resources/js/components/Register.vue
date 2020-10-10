<template>
  <!-- <div>
      <div class="container">
        <div class="row">
          <div class="col-sm-12 col-md-12 col-lg-6">
            <h1>Register</h1>
            <form @submit.prevent="register">
              <label for="name">Name</label>
              <input type="text" v-model="name" class="form-control">
              <label for="email">Email</label>
              <input type="email" v-model="email" class="form-control">
              <label for="password">Password</label>
              <input type="password" v-model="password" class="form-control">
              <label for="confirm_password">Confirm password</label>
              <input type="password" v-model="confirm_password" class="form-control">
              <button type="submit" class="btn btn-secondary mt-4">Register</button>
            </form>
          </div>
        </div>
      </div>
  </div> -->

  <div class="row">
    <div class="form__container">
      <h1 class="text-muted">Please register</h1>
      <form @submit.prevent="register">
        <label for="name">Name</label>
        <input type="text" v-model="name">
        <label for="email">Email</label>
        <input type="email" v-model="email">
        <label for="password">Password</label>
        <input type="password" v-model="password">
        <label for="confirm_password">Confirm password</label>
        <input type="password" v-model="confirm_password" class="form-control">
        <button type="submit" class="button-submit">Register</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
    name: 'register',
    data(){
      return {
        name: null,
        email: null,
        password: null,
        confirm_password: null
      }
    },
    methods: {
      register(){
        axios.post("api/register", {
          name: this.name,
          email: this.email,
          password: this.password
        })
        .then(response => {
          console.log(response);
        })
        .catch(err => console.error(err))
      }
    }
}
</script>

<style>

</style>