<template>
  <b-modal id="userModal" :title="title">
    <div class="form-group">
      <h4 class="title">Sign Up For Free</h4>
      <div class="alert alert-danger" v-if="error_msg">{{ error_msg }}</div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <input
            v-validate="'required|alpha'"
            type="text"
            class="form-control"
            name="first_name"
            v-model="first_name"
            placeholder="First Name"
            @keyup.enter="register($event)"
          >
          <p class="error">{{ errors.first('first_name') }}</p>
        </div>
        <div class="form-group col-md-6">
          <input
            v-validate="'required|alpha'"
            type="text"
            class="form-control"
            name="last_name"
            v-model="last_name"
            placeholder="Last Name"
            @keyup.enter="register($event)"
          >
          <p class="error">{{ errors.first('last_name') }}</p>
        </div>
      </div>
      <div class="form-group">
        <input
          type="email"
          v-validate="'required|email'"
          class="form-control"
          name="email"
          v-model="email"
          placeholder="Email Address"
          @keyup.enter="register($event)"
        >
        <p class="error">{{ errors.first('email') }}</p>
      </div>
      <div class="form-group">
        <input
          type="password"
          v-validate="'required|min:6'"
          class="form-control"
          name="password"
          v-model="password"
          placeholder="Set A Password"
          @keyup.enter="register($event)"
        >
        <p class="error">{{ errors.first('password') }}</p>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: {
    title: String
  }
};
</script>

<style>
</style>
