<template>
  <div id="profile" class="wrapper">
    <div
      class="avatar-wrapper"
      @mouseover="isHovering = true"
      @mouseout="isHovering = false"
      :class="{hovering: isHovering}"
    >
      <img
        :src="user_info.avatar_img"
        alt
        class="avatar"
        onError="this.onerror=null;this.src='https://i.pinimg.com/236x/40/41/fa/4041faa0a8989e5787f9b164ca3b2650--occupational-therapist-physical-therapist.jpg';"
      >
      <input type="file" ref="file" style="display: none" @change="editAvatar">
      <button class="btn btn-light" v-show="isHovering" @click.prevent="$refs.file.click()">Edit</button>
    </div>
    <div class="form-group row justify-content-center">
      <div class="alert alert-danger col-md-4" v-if="error_msg">{{ error_msg }}</div>
      <div class="alert alert-success col-md-4" v-if="success_msg">{{ success_msg }}</div>
    </div>
    <div class="form-group row justify-content-center">
      <label for="email" class="col-md-2">Email address</label>
      <div class="col-md-3">
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          v-validate="'required|email'"
          v-model="user_info.email"
          :disabled="!editMode"
          @keyup="onChange"
        >
        <p class="error">{{ errors.first('email') }}</p>
      </div>
    </div>
    <div class="form-group row justify-content-center">
      <label for="first_name" class="col-md-2">First Name</label>
      <div class="col-md-3">
        <input
          type="text"
          class="form-control"
          id="first_name"
          name="first_name"
          v-validate="'required|alpha'"
          v-model="user_info.first_name"
          :disabled="!editMode"
          @keyup="onChange"
        >
        <p class="error">{{ errors.first('first_name') }}</p>
      </div>
    </div>
    <div class="form-group row justify-content-center">
      <label for="last_name" class="col-md-2">Last Name</label>
      <div class="col-md-3">
        <input
          type="text"
          class="form-control"
          id="last_name"
          name="last_name"
          v-validate="'required|alpha'"
          v-model="user_info.last_name"
          :disabled="!editMode"
          @keyup="onChange"
        >
        <p class="error">{{ errors.first('last_name') }}</p>
      </div>
    </div>
    <div class="form-group row justify-content-center">
      <label for="last_name" class="col-md-2">Role</label>
      <div class="col-md-3">
        <input type="text" class="form-control" id="role" v-model="role" disabled>
      </div>
    </div>

    <button class="btn btn-info" @click.prevent="onEditClick" v-show="!editMode">Edit</button>
    <div class="row justify-content-center">
      <button class="btn btn-light" @click.prevent="cancel" v-show="editMode">Cancel</button>
      <button
        class="btn btn-info"
        v-show="editMode"
        :disabled="!isDifferent"
        @click.prevent="submit"
      >Submit</button>
    </div>
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
      editMode: false,
      isHovering: false,
      isDifferent: false,
      error_msg: null,
      success_msg: null,
      user_info: {},
      original_user_info: {}
    };
  },
  created() {
    this.original_user_info.email = this.user_info.email = localStorage.getItem(
      "email"
    );
    this.original_user_info.first_name = this.user_info.first_name = localStorage.getItem(
      "first_name"
    );
    this.original_user_info.last_name = this.user_info.last_name = localStorage.getItem(
      "last_name"
    );
    this.user_info.role = parseInt(localStorage.getItem("role"));
    this.user_info.avatar_img = localStorage.getItem("avatar");
  },
  computed: {
    role() {
      switch (this.user_info.role) {
        case 1:
          return "Customer";
        case 2:
          return "Shop";
        case 3:
          return "Admin";
      }
    }
  },
  methods: {
    onEditClick() {
      this.editMode = true;
      this.error_msg = "";
      this.success_msg = "";
    },
    cancel() {
      this.user_info.email = this.original_user_info.email;
      this.user_info.first_name = this.original_user_info.first_name;
      this.user_info.last_name = this.original_user_info.last_name;
      this.editMode = false;
      this.isDifferent = false;
      this.errors.clear();
    },
    onChange() {
      this.isDifferent =
        this.original_user_info.email != this.user_info.email ||
        this.original_user_info.first_name != this.user_info.first_name ||
        this.original_user_info.last_name != this.user_info.last_name;
    },
    submit() {
      this.error_msg = null;
      this.success_msg = null;
      this.$validator.validate().then(result => {
        if (!result) {
          return;
        }
        this.$http
          .post(
            "profile/edit",
            {
              email: this.user_info.email,
              first_name: this.user_info.first_name,
              last_name: this.user_info.last_name
            },
            {
              headers: {
                Authorization: `${localStorage.getItem("access_token")}`
              },
              emulateJSON: true
            }
          )
          .then(
            response => {
              if (response.body.result_code == 200) {
                this.getProfile();
                this.success_msg = response.body.data;
                this.editMode = false;
                this.original_user_info.email = this.user_info.email;
                this.original_user_info.first_name = this.user_info.first_name;
                this.original_user_info.last_name = this.user_info.last_name;
                this.isDifferent = false;
                this.$root.$emit("reloadComponent");
              }
            },
            response => {
              this.error_msg = response.body.data;
            }
          );
      });
    },
    editAvatar() {
      this.error_msg = "";
      this.success_msg = "";
      let file = this.$refs.file.files[0];
      let formData = new FormData();
      formData.append("avatar_img", file);
      this.$http
        .post(
          "profile/edit",
          formData,
          {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`,
              "Content-Type": "multipart/form-data"
            }
          }
        )
        .then(
          response => {
            if (response.body.result_code == 200) {
              this.getProfile();
              this.success_msg = response.body.data;
              this.$root.$emit("reloadComponent");
            }
          },
          response => {
            this.error_msg = response.body.data;
          }
        );
    },
    getProfile() {
      this.$http
        .get("profile", {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        })
        .then(response => {
          if (response.body.result_code == 200) {
            this.user_info = response.body.data;
            this.original_user_info.email = this.user_info.email;
            this.original_user_info.first_name = this.user_info.first_name;
            this.original_user_info.last_name = this.user_info.last_name;
            this.user_info.avatar_img = this.$helpers.image(this.user_info.avatar_img);
            localStorage.setItem("first_name", this.user_info.first_name);
            localStorage.setItem("last_name", this.user_info.last_name);
            localStorage.setItem("email", this.user_info.email);
            localStorage.setItem(
              "full_name",
              this.user_info.first_name + " " + this.user_info.last_name
            );
            localStorage.setItem("avatar", this.user_info.avatar_img);
          }
        });
    }
  }
};
</script>

<style scoped>
#profile {
  text-align: center;
  padding: 50px 0;
}

.avatar-wrapper {
  position: relative;
  margin-bottom: 20px;
  display: inline-block;
}

.avatar-wrapper img {
  vertical-align: middle;
  max-height: 150px;
}

.avatar-wrapper button {
  position: absolute;
  width: 50px;
  height: 30px;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  margin: auto;
}

#profile .hovering {
  opacity: 0.7;
}

.form-group label {
  align-self: center;
  margin-bottom: 0;
}

button + button {
  margin-left: 10px;
}
</style>
