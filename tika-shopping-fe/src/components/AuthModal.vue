<template>
  <b-modal id="authModal" hide-header hide-footer @hidden="onHidden">
    <ul class="form-switcher">
      <li :class="{ 'active': active == 'register' }" @click.prevent="flip('register')">
        <a href>Sign Up</a>
      </li>
      <li :class="{ 'active': active == 'login' }" @click.prevent="flip('login')">
        <a href>Login</a>
      </li>
    </ul>
    <RegisterForm v-if="active == 'register'"/>
    <LoginForm v-else/>
  </b-modal>
</template>

<script>
import RegisterForm from "@/components/RegisterForm.vue";
import LoginForm from "@/components/LoginForm.vue";

export default {
  name: "AuthModal",
  components: {
    RegisterForm,
    LoginForm
  },
  data: function() {
    return {
      active: "register"
    };
  },
  methods: {
	resetModal() {
	  if (this.active == "register") this.flip('login');
	  else this.flip('register');
    },
    flip: function(which) {
      if (which !== this.active) {
        this.active = which;
      }
	},
	onHidden() {
      this.resetModal();
    }
  }
};
</script>

<style>
#authModal {
  text-align: center;
}

#authModal div.modal-content {
  background-color: #212c37;
}

#authModal div.modal-header {
  border-bottom: 0;
}

#authModal div.modal-footer {
  border-top: 0;
}

#authModal ul.form-switcher {
  margin: 30px 0;
  padding: 0;
  width: 100%;
}

#authModal ul.form-switcher li {
  list-style: none;
  display: inline-block;
  width: 50%;
  margin: 0;
  background-color: grey;
}

#authModal ul.form-switcher li.active {
  background-color: #083b4c;
}

#authModal ul.form-switcher li a {
  width: 100%;
  display: block;
  height: 50px;
  line-height: 50px;
  color: #fff;
  text-decoration: none;
}
</style>

