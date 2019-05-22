<template>
  <div class="auth-form form-group">
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
    <button class="submit-btn" @click="register($event)">GET STARTED</button>
  </div>
</template>

<script>
import Vue from "vue";
import VeeValidate from "vee-validate";
import { Validator } from "vee-validate";

const dict = {
  custom: {
    first_name: {
      required: "First name is required.",
      alpha: "Type letters only."
    },
    last_name: {
      required: "Last name is required.",
      alpha: "Type letters only."
    }
  }
};

Validator.localize("en", dict);
Vue.use(VeeValidate, {
  events: "blur"
});
export default {
  data() {
    return {
      first_name: null,
      last_name: null,
      email: null,
      password: null,
      old_email: null,
      error_msg: null
    };
  },
  methods: {
    register(e) {
      e.preventDefault();
      this.error_msg = null;
      this.$validator.validate().then(result => {
        if (!result) {
          return;
        }
        if (this.email == this.old_email) {
          this.error_msg = "Please change email before submitting again.";
          return;
        }
        this.$http
          .post(
            "users",
            {
              email: this.email,
              password: this.password,
              first_name: this.first_name,
              last_name: this.last_name
            },
            { emulateJSON: true }
          )
          .then(
            response => {
              if (response.body.result_code == 200) {
                this.email = null;
                this.password = null;
                this.old_email = null;
                localStorage.setItem("access_token", response.body.data.token);
                localStorage.setItem(
                  "email",
                  response.body.data.user_info.email
                );
                localStorage.setItem(
                  "full_name",
                  response.body.data.user_info.first_name +
                    " " +
                    response.body.data.user_info.last_name
                );
                localStorage.setItem(
                  "first_name",
                  response.body.data.user_info.first_name
                );
                localStorage.setItem(
                  "last_name",
                  response.body.data.user_info.last_name
                );
                if (response.body.data.user_info.avatar_img)
                  localStorage.setItem(
                    "avatar",
                    this.$helpers.image(response.body.data.user_info.avatar_img)
                  );
                localStorage.setItem("role", response.body.data.user_info.role);
                this.$root.$emit("setAuthenticated");
                this.$root.$emit("bv::hide::modal", "authModal");
              }
            },
            response => {
              this.old_email = this.email;
              this.error_msg = response.body.result_message;
            }
          );
      });
    }
  }
};
</script>


<style>
.auth-form input.form-control {
  border-radius: 0;
  background-clip: unset;
  background-color: #212c37;
}

.auth-form input.form-control:focus {
  background-color: inherit;
}

.auth-form .title,
.auth-form input.form-control,
.auth-form input.form-control:focus {
  color: #fff;
}

.error {
  color: darkred;
  text-align: left;
}

.auth-form .submit-btn {
  width: 100%;
  background-color: #083b4c;
  border: unset;
  color: #fff;
  line-height: 3;
}
</style>