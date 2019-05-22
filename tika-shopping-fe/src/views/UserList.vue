<template>
  <div id="userList" class="wrapper">
    <h2>User Management</h2>
    <div class="clearfix">
      <a href v-b-modal.userModal class="btn btn-outline-info float-right" @click.prevent>Add a user</a>
      <UserFormModal title="Add a new user"/>
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
          <th>Email</th>
          <th>Name</th>
          <th>Role</th>
          <th>Status</th>
          <th class="center-align">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.email }}</td>
          <td>{{ user.first_name + ' ' + user.last_name }}</td>
          <td>{{ user.role }}</td>
          <td>{{ user.status }}</td>
          <td class="center-align action">
            <button class="btn btn-outline-warning btn-sm">Update</button>
            <button v-if="user.status" class="btn btn-outline-danger btn-sm">Deactivate</button>
            <button v-else class="btn btn-outline-primary btn-sm">Activate</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import UserFormModal from "@/components/UserFormModal.vue";

export default {
  components: {
    UserFormModal
  },
  data() {
    return {
      users: null,
      current_page: null,
      next_page_url: null,
      prev_page_url: null,
      total_pages: 1
    };
  },
  created() {
    this.$http
      .get("users", {
        headers: {
          Authorization: `${localStorage.getItem("access_token")}`
        }
      })
      .then(response => {
        if (response.body.result_code == 200) {
          this.assignData(response.body.data);
        }
      });
  },
  methods: {
    assignData(data) {
      this.users = data.data_users.data;
      this.current_page = data.data_users.current_page;
      this.next_page_url = data.data_users.next_page_url;
      this.prev_page_url = data.data_users.prev_page_url;
      this.total_pages = data.data_users.total_pages;
    },
    fetch(pg) {
      if (pg > this.total_pages || pg < 1) return;
      this.$http
        .get(`users?page=${pg}`, {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        })
        .then(response => {
          if (response.body.result_code == 200) {
            this.assignData(response.body.data);
          }
        });
    }
  }
};
</script>


<style scoped>
#userList {
  padding-top: 20px;
}

.table {
  text-align: left;
}

.center-align {
  text-align: center;
}

.action button {
  width: 85px;
}

.action button + button {
  margin-left: 5px;
}
</style>