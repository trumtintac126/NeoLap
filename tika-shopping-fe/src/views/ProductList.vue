<template>
  <div id="productList" class="wrapper">
    <h2>Product Management</h2>
    <div class="clearfix" v-if="my_role == 2">
      <a
        href
        v-b-modal.createProductModal
        class="btn btn-outline-info float-right"
        @click.prevent
      >Add a product</a>
      <CreateProductFormModal @reload="reloadComponent" title="Add a product"/>
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
          <th>Product</th>
          <th>Category</th>
          <th>Shop</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Image</th>
          <th class="center-align">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.product_name }}</td>
          <td>{{ product.category_name }}</td>
          <td>{{ product.shop_name }}</td>
          <td>{{ product.price }}</td>
          <td>{{ product.quantity_in_stock }}</td>
          <td>
            <img
              :src="$helpers.image(product.product_img)"
              alt
              class="preview-img"
              onError="this.onerror=null;this.src='https://2txq013ih6mt3axzj331a09x-wpengine.netdna-ssl.com/wp-content/uploads/2019/01/default-pro.jpg';"
            >
          </td>
          <td class="center-align action">
            <button class="btn btn-outline-warning btn-sm" @click.prevent="onUpdate(product)">Update</button>
            <button
              v-if="product.status"
              class="btn btn-outline-danger btn-sm"
              @click.prevent="lock(product)"
            >Lock</button>
            <button
              v-else
              class="btn btn-outline-primary btn-sm"
              @click.prevent="unlock(product)"
            >Unlock</button>
          </td>
        </tr>
      </tbody>
    </table>
    <EditProductFormModal
      @reload="reloadComponent"
      :title="'Edit product: ' + current_edit_product.product_name"
      :product_info="current_edit_product"
    />
  </div>
</template>

<script>
import CreateProductFormModal from "@/components/CreateProductFormModal.vue";
import EditProductFormModal from "@/components/EditProductFormModal.vue";

export default {
  components: {
    CreateProductFormModal,
    EditProductFormModal
  },
  data() {
    return {
      my_role: null,
      products: null,
      current_page: null,
      next_page_url: null,
      prev_page_url: null,
      total_pages: 1,
      current_edit_product: {}
    };
  },
  created() {
    this.my_role = localStorage.getItem("role");
    this.reloadComponent();
  },
  methods: {
    fetch(pg) {
      if (pg > this.total_pages || pg < 1) return;
      this.$http
        .get(
          `product-shop?page=${pg}`,
          {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`
            }
          }
        )
        .then(response => {
          if (response.body.result_code == 200) {
            this.products = response.body.data.data_product.data;
            this.current_page = response.body.data.data_product.current_page;
            this.next_page_url = response.body.data.data_product.next_page_url;
            this.prev_page_url = response.body.data.data_product.prev_page_url;
            this.total_pages = response.body.data.total_pages;
          }
        });
    },
    onUpdate(product) {
      this.current_edit_product = product;
      this.$root.$emit("bv::show::modal", "editProductModal");
    },
    reloadComponent() {
      this.$http
        .get("product-shop", {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        })
        .then(response => {
          if (response.body.result_code == 200) {
            this.products = response.body.data.data_product.data;
            this.current_page = response.body.data.data_product.current_page;
            this.next_page_url = response.body.data.data_product.next_page_url;
            this.prev_page_url = response.body.data.data_product.prev_page_url;
            this.total_pages = response.body.data.total_pages;
          }
        });
    },
    lock(product) {
      if (
        !confirm("Bạn có muốn khóa sản phẩm: " + product.product_name + "?")
      ) {
        return false;
      }
      this.$http
        .post(
          `lock_product/${
            product.id
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
            alert("Bạn vừa khóa sản phẩm: " + product.product_name);
            this.reloadComponent();
          }
        });
    },
    unlock(product) {
      if (
        !confirm("Bạn có muốn mở khóa sản phẩm: " + product.product_name + "?")
      ) {
        return false;
      }
      this.$http
        .post(
          `unlock_product/${
            product.id
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
            alert("Bạn vừa mở khóa sản phẩm: " + product.product_name);
            this.reloadComponent();
          }
        });
    }
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
