import Vue from "vue";
import Router from "vue-router";
import Home from "./views/Home.vue";
import UserList from "./views/UserList.vue"
import ProductList from "./views/ProductList.vue"
import CampaignList from "./views/CampaignList.vue"
import CampaignAdd from "./views/CampaignAdd.vue"
import Profile from "./views/Profile.vue"
import Report from "./views/Report.vue"
import OrderLisr from "./views/OrderList.vue"

Vue.use(Router);

export default new Router({
  mode: "history",
  base: process.env.BASE_URL,
  routes: [
    {
      path: "/",
      name: "home",
      component: Home
    },
    {
      path: "/admin/user",
      name: "user-list",
      component: UserList
    },
    {
      path: "/product",
      name: "product-list",
      component: ProductList
    },
    {
      path: "/campaign",
      name: "campaign-list",
      component: CampaignList
    },
    {
      path: "/campaign/add",
      name: "campaign-add",
      component: CampaignAdd
    },
    {
      path: "/profile",
      name: "profile",
      component: Profile
    },
    {
      path: "/profile/report",
      name: "report",
      component: Report
    },
    {
      path: "/order-list",
      name: "order-list",
      component: OrderLisr
    }
  ],
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
});
