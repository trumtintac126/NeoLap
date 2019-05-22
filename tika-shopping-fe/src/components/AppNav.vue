<template>
  <div id="app-nav" @setAuthenticated="setAuthenticated">
    <div class="wrapper right">
      <div v-if="!authenticated">
        <router-link :to="{path: '/order-list'}">Management order</router-link>
        <AuthModal/>
      </div>
      <div v-else>
        <b-dropdown id="ddown-left" ref="ddown" variant="link" size="sm" no-caret>
          <template slot="button-content">
            <img
              :src="avatar"
              alt="Avatar"
              class="avatar"
              onError="this.onerror=null;this.src='https://i.pinimg.com/236x/40/41/fa/4041faa0a8989e5787f9b164ca3b2650--occupational-therapist-physical-therapist.jpg';"
            >
            <a>{{ full_name }}</a>
          </template>
          <b-dropdown-item to="/profile">Thông tin cá nhân</b-dropdown-item>
          <b-dropdown-item v-if="role == 1" @click.prevent="becomeShop">Trở thành nhà bán hàng</b-dropdown-item>
          <b-dropdown-item v-if="role == 3" to="/admin/user">Quản lý user</b-dropdown-item>
          <b-dropdown-item v-if="role == 2 || role == 3" to="/product">Quản lý sản phẩm</b-dropdown-item>
          <b-dropdown-item v-if="role == 2 || role == 3" to="/campaign">Quản lý chiến dịch</b-dropdown-item>
          <b-dropdown-item @click.prevent="logout">Đăng xuất</b-dropdown-item>
        </b-dropdown>
      </div>
    </div>
    <SearchBar/>
  </div>
</template>

<script>
import AuthModal from "@/components/AuthModal.vue";
import SearchBar from "@/components/SearchBar.vue";

export default {
  components: {
    AuthModal,
    SearchBar
  },
  data() {
    return {
      authenticated: false,
      full_name: null,
      avatar: "https://i.pinimg.com/236x/40/41/fa/4041faa0a8989e5787f9b164ca3b2650--occupational-therapist-physical-therapist.jpg",
      role: null
    };
  },
  created: function() {
    if (localStorage.getItem("access_token")) {
      this.setAuthenticated(true);
    }
    this.$root.$on("setAuthenticated", () => {
      this.setAuthenticated(true);
    });
    this.$root.$on("reloadComponent", () => {
      this.$http
        .get("profile", {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        })
        .then(response => {
          if (response.body.result_code == 200) {
            this.full_name = fullName(response.body.data.first_name, response.body.data.last_name);
            this.avatar = this.$helpers.image(response.body.data.avatar_img);
          }
        });
    });
  },
  methods: {
    fetchLocalStorage() {
      this.full_name = localStorage.getItem("full_name");
      this.role = localStorage.getItem("role");
      if (localStorage.getItem("avatar"))
        this.avatar = localStorage.getItem("avatar");
    },
    resetLocalStorage() {
      localStorage.removeItem("access_token");
      localStorage.removeItem("email");
      localStorage.removeItem("full_name");
      localStorage.removeItem("first_name");
      localStorage.removeItem("last_name");
      localStorage.removeItem("avatar");
      localStorage.removeItem("role");
    },
    setAuthenticated(isAuthenticated) {
      this.authenticated = isAuthenticated;
      if (isAuthenticated) {
        this.fetchLocalStorage();
        return;
      }
      this.resetLocalStorage();

      
    },
    fullName(first_name, last_name) {
      return first_name + ' ' + last_name;
    },
    logout() {
      this.$refs.ddown.hide(true);
      this.$http
        .get("logout", {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        })
        .then(response => {
          if (response.body.result_code == 200) {
            this.resetLocalStorage();
            this.setAuthenticated(false);
            this.$router.push("/");
          }
        });
    },
    becomeShop() {
      var shopName = prompt("Please enter your shop name");
      if (shopName === null) {
        return;
      }
      this.$http
        .post(
          "shops",
          {
            shop_name: shopName
          },
          {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`
            },
            emulateJSON: true
          }
        )
        .then(response => {
          if (response.body.result_code == 200) {
            alert("Bạn vừa đăng ký shop thành công!");
            localStorage.setItem("role", 2);
            this.role = 2;
          }
        });
    }
  }
};
</script>

<style>
#app-nav {
  line-height: 30px;
  font-size: 0.825em;
  background-color: #212c37;
}

#app-nav a {
  color: #fff;
  padding-right: 20px;
}

#ddown-left .dropdown-item {
  color: #013e53;
  font-size: 13px;
  padding: 0 1rem;
}

#app-nav .avatar {
  vertical-align: middle;
  width: 25px;
  height: 25px;
  border-radius: 50%;
  margin-right: 5px;
}
</style>
