<template>
  <div class="auth-form form-group">
    <h4 class="title">Sign In</h4>
    <div class="alert alert-danger" v-if="error_msg">{{ error_msg }}</div>
    <div class="form-group">
      <input
        v-validate="'required|email'"
        name="email"
        type="email"
        class="form-control"
        v-model="email"
        placeholder="Email Address"
        @keyup.enter="login($event)"
      >
      <p class="error">{{ errors.first('email') }}</p>
    </div>
    <div class="form-group">
      <input
        v-validate="'required|min:6'"
        name="password"
        type="password"
        class="form-control"
        v-model="password"
        placeholder="Password"
        @keyup.enter="login($event)"
      >
      <p class="error">{{ errors.first('password') }}</p>
    </div>
    <button class="submit-btn" @click="login($event)">LOGIN</button>
  </div>
</template>

<script>
import Vue from "vue";
import VeeValidate from "vee-validate";

Vue.use(VeeValidate, {
  events: "blur"
});
export default {
  data() {
    return {
      email: null,
      password: null,
      old_email: null,
      old_password: null,
      error_msg: null
    };
  },
  methods: {
    fullName(first_name, last_name) {
      return first_name + " " + last_name;
    },
    login(e) {
      e.preventDefault();
      this.error_msg = null;
      this.$validator.validate().then(result => {
        if (!result) {
          return;
        }
        if (
          this.email == this.old_email &&
          this.password == this.old_password
        ) {
          this.error_msg =
            "Please change the input fields before submitting again.";
          return;
        }
        this.$http
          .post(
            "login",
            {
              email: this.email,
              password: this.password
            },
            { emulateJSON: true }
          )
          .then(
            response => {
              if (response.body.result_code == 200) {
                this.email = null;
                this.password = null;
                this.old_email = null;
                this.old_password = null;
                localStorage.setItem("access_token", response.body.data.token);
                localStorage.setItem(
                  "email",
                  response.body.data.user_info.email
                );
                localStorage.setItem(
                  "full_name",
                  this.fullName(
                    response.body.data.user_info.first_name,
                    response.body.data.user_info.last_name
                  )
                );
                localStorage.setItem(
                  "first_name",
                  response.body.data.user_info.first_name
                );
                localStorage.setItem(
                  "last_name",
                  response.body.data.user_info.last_name
                );
                localStorage.setItem(
                  "role",
                  response.body.data.user_info.role
                );
                if (response.body.data.user_info.avatar_img)
                  localStorage.setItem(
                    "avatar",
                    this.$helpers.image(response.body.data.user_info.avatar_img)
                  );
                this.$root.$emit("setAuthenticated");
                this.$root.$emit("bv::hide::modal", "authModal");
              }
            },
            response => {
              this.old_email = this.email;
              this.old_password = this.password;
              this.error_msg = response.body.result_message;
            }
          );
      });
    }
  }
};
</script>
