<template>
  <div id="campaignList" class="wrapper">
    <h2>Campaign Management</h2>
    <div class="clearfix" v-if="my_role == 2">
      <router-link to="/campaign/add" class="btn btn-outline-info float-right">Add a campaign</router-link>
    </div>
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: !prev_page_url }">
          <a class="page-link" href aria-label="Previous" @click.prevent="fetch(current_page - 1)">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
          </a>
        </li>
        <li
          class="page-item"
          v-for="page in total_pages"
          :key="page"
          :class="{ disabled: current_page == page }"
        >
          <a class="page-link" href @click.prevent="fetch(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: !next_page_url }">
          <a class="page-link" href aria-label="Next" @click.prevent="fetch(current_page + 1)">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      </ul>
    </nav>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Campaign</th>
          <th>Shop</th>
          <th>Status</th>
          <th>Start date</th>
          <th>End date</th>
          <th>Budget</th>
          <th>Bid amount</th>
          <th>Creative</th>
          <th>Title</th>
          <th class="center-align">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="campaign in campaigns" :key="campaign.id">
          <td>{{ campaign.campaign_name }}</td>
          <td>{{ campaign.shop_name }}</td>
          <td>{{ status(campaign.status) }}</td>
          <td>{{ campaign.start_day }}</td>
          <td>{{ campaign.end_day }}</td>
          <td>{{ campaign.budget }}</td>
          <td>{{ campaign.bid_amount }}</td>
          <td>
            <img
              :src="$helpers.image(campaign.campaign_img)"
              alt
              class="preview-img"
              onError="this.onerror=null;this.src='https://2txq013ih6mt3axzj331a09x-wpengine.netdna-ssl.com/wp-content/uploads/2019/01/default-pro.jpg';"
            >
          </td>
          <td>{{ campaign.title }}</td>
          <td class="center-align action" v-if="campaign.status">
            <button
              v-if="my_role == 2"
              class="btn btn-outline-warning btn-sm"
              @click.prevent="onUpdate(campaign)"
            >Update</button>
            <button
              v-if="campaign.user_status"
              class="btn btn-outline-danger btn-sm"
              @click.prevent="lock(campaign)"
            >Pause</button>
            <button
              v-else
              class="btn btn-outline-primary btn-sm"
              @click.prevent="unlock(campaign)"
            >Replay</button>
          </td>
          <td class="center-align action" v-else>
            <button
              class="btn btn-outline-primary btn-sm"
              @click.prevent="unlock(campaign)"
              v-if="my_role == 3"
            >Restart</button>
          </td>
        </tr>
      </tbody>
    </table>
    <EditCampaignFormModal
      @reload="reloadComponent"
      :modal_title="'Edit campaign: ' + current_edit_campaign.campaign_name"
      :campaign_info="current_edit_campaign"
    />
  </div>
</template>

<script>
import EditCampaignFormModal from "@/components/EditCampaignFormModal.vue";

export default {
  components: {
    EditCampaignFormModal
  },
  data() {
    return {
      my_role: null,
      campaigns: null,
      current_page: null,
      next_page_url: null,
      prev_page_url: null,
      total_pages: 1,
      current_edit_campaign: {}
    };
  },
  created() {
    this.my_role = localStorage.getItem("role");
    this.reloadComponent();
  },
  methods: {
    status(n) {
      switch (n) {
        case 1:
          return "ACTIVE";
        case 0:
          return "PAUSED";
      }
    },
    fetch(pg) {
      if (pg > this.total_pages || pg < 1) return;
      this.$http
        .get(
          `campaign-shop?page=${pg}`,
          {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`
            }
          }
        )
        .then(response => {
          if (response.body.result_code == 200) {
            this.campaigns = response.body.data.data_campaigns.data;
            this.current_page = response.body.data.data_campaigns.current_page;
            this.next_page_url =
              response.body.data.data_campaigns.next_page_url;
            this.prev_page_url =
              response.body.data.data_campaigns.prev_page_url;
            this.total_pages = response.body.data.total_pages;
          }
        });
    },
    onUpdate(campaign) {
      this.current_edit_campaign = campaign;
      this.$root.$emit("bv::show::modal", "editCampaignModal");
    },
    reloadComponent() {
      this.$http
        .get("campaign-shop", {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        })
        .then(response => {
          if (response.body.result_code == 200) {
            this.campaigns = response.body.data.data_campaigns.data;
            this.current_page = response.body.data.data_campaigns.current_page;
            this.next_page_url =
              response.body.data.data_campaigns.next_page_url;
            this.prev_page_url =
              response.body.data.data_campaigns.prev_page_url;
            this.total_pages = response.body.data.total_pages;
          }
        });
    },
    lock(campaign) {
      if (
        !confirm("Bạn có muốn dừng chiến dịch: " + campaign.campaign_name + "?")
      ) {
        return false;
      }
      this.$http
        .post(
          `campaign-lock/${
            campaign.id
          }`,
          {},
          {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`
            }
          }
        )
        .then(response => {
          if (response.body.result_code == 200) {
            alert("Bạn vừa dừng chiến dịch: " + campaign.campaign_name);
            this.reloadComponent();
          }
        });
    },
    unlock(campaign) {
      if (
        !confirm(
          "Bạn có muốn chạy lại chiến dịch: " + campaign.campaign_name + "?"
        )
      ) {
        return false;
      }
      this.$http
        .post(
          `campaign-unlock/${
            campaign.id
          }`,
          {},
          {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`
            }
          }
        )
        .then(response => {
          if (response.body.result_code == 200) {
            alert("Bạn vừa chạy lại chiến dịch: " + campaign.campaign_name);
            this.reloadComponent();
          }
        });
    }
  }
};
</script>

<style scoped>
#campaignList {
  padding-top: 20px;
}

.table {
  text-align: left;
}

.table tr > *:nth-child(10) {
  min-width: 160px;
}

.center-align {
  text-align: center;
}

.action button {
  width: 65px;
}

.action button + button {
  margin-left: 5px;
}

.preview-img {
  vertical-align: middle;
  max-width: 50px;
}
</style>
