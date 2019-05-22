<template>
  <div id="productList" class="wrapper">
    <h2>Product Management</h2>

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
          <th>Id</th>
          <th>order_date</th>
          <th>category_order</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>total_detail</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>{{ order.id }}</td>
          <td>{{ order.order_date }}</td>
          <td>{{ order.category_order }}</td>
          <td>{{ order.price }}</td>
          <td>{{ order.quantity }}</td>
            <td>{{ order.total_detail }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
// import { setInterval } from 'timers';

export default {
  data() {
    return {
      orders: null,
      current_page: null,
      next_page_url: null,
      prev_page_url: null,
      total_pages: 1,
    //   timer: null
    };
  },
  created() {
    this.fetch();
    // this.timer = setInterval(this.fetch,3000)
  },
  methods: {
    fetch() {
      this.$http
        .get("orders", {
        })
        .then(response => {
          if (response.body.result_code == 200) {
            this.orders = response.body.data.data_orders.data;
            this.current_page = response.body.data.data_orders.current_page;
            this.next_page_url = response.body.data.data_orders.next_page_url;
            this.prev_page_url = response.body.data.data_orders.prev_page_url;
            this.total_pages = response.body.data.total_pages;
          } 
        });
    },
  }
};
</script>

<style scoped>
#productList {
  padding-top: 20px;
}

.table {
  text-align: left;
}

.table tr > *:nth-child(6) {
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
  width: 50px;
  height: 50px;
}
</style>
