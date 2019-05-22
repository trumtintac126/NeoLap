<template>
  <div id="productList">
    <div class="wrapper" v-show="items">
      <div class="product-list-header">
        <h3 class="header-title">Just For You</h3>
      </div>
      <div class="card-wrapper">
        <div class="card-row" v-for="row in total_rows" :key="row">
          <div
            class="card-item-wrapper"
            v-for="item in items.slice((row-1)*6, row*6)"
            :key="item.id"
          >
            <a href class="card-content">
              <div class="card-item">
                <div class="card-image">
                  <img
                    :src="$helpers.image(item.product_img)"
                    alt
                    onError="this.onerror=null;this.src='https://2txq013ih6mt3axzj331a09x-wpengine.netdna-ssl.com/wp-content/uploads/2019/01/default-pro.jpg';"
                  >
                </div>
                <div class="card-description">
                  <div class="card-title">{{ item.product_name }}</div>
                  <div class="card-price">
                    <span class="currency">$</span>
                    <span class="price">{{ item.price }}</span>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="card-load-more" v-show="hasMore">
        <a class="load-more-btn" @click.prevent="loadMore">LOAD MORE</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      items: null,
      next_page: 1,
      hasMore: false
    };
  },
  computed: {
    total_rows() {
      if (this.items) return Math.ceil(this.items.length / 6);
      return 0;
    }
  },
  created() {
    this.items = null;
    this.next_page = 1;
    this.hasMore = false;
    this.$http
      .get(
        "product-client?per_page=12",
        {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        }
      )
      .then(
        response => {
          if (response.body.result_code == 200) {
            this.items = response.body.data.data_products.data;
            this.hasMore = response.body.data.data_products.next_page_url
              ? true
              : false;
            this.next_page += 1;
          }
        },
        response => {}
      );
  },
  methods: {
    loadMore() {
      this.$http
        .get(
          `product-client?per_page=12&page=${
            this.next_page
          }`,
          {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`
            }
          }
        )
        .then(
          response => {
            if (response.body.result_code == 200) {
              this.items = this.items.concat(
                response.body.data.data_products.data
              );
              this.hasMore = response.body.data.data_products.next_page_url
                ? true
                : false;
              this.next_page += 1;
            }
          },
          response => {}
        );
    }
  }
};
</script>

<style scoped>
#productList {
  padding-top: 24px;
}

.product-list-header {
  height: 38px;
  line-height: 38px;
}

.header-title {
  float: left;
  font-size: 22px;
  color: #424242;
}

.card-wrapper {
  width: 100%;
}

.card-row {
  height: 275px;
  margin-bottom: 20px;
  display: flex;
  justify-content: flex-start;
}

.card-item-wrapper {
  display: inline-block;
  vertical-align: top;
  width: 189px;
  min-height: 189px;
  background: #fff;
  height: 100%;
}

.card-item-wrapper:hover {
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.25);
}

.card-item-wrapper + .card-item-wrapper {
  margin-left: 10px;
}

.card-content {
  height: 100%;
  width: 100%;
  display: block;
}

.card-item {
  height: 100%;
}

.card-image {
  width: 189px;
  height: 189px;
  box-sizing: border-box;
  line-height: 0;
  background-position: 50% 50%;
  background-size: contain;
}

.card-image img {
  width: 100%;
  height: 100%;
}

.card-description {
  padding: 4px 8px 12px;
  text-align: left;
}

.card-title {
  font-size: 14px;
  height: 36px;
  line-height: 18px;
  color: #212121;
  text-overflow: ellipsis;
  overflow: hidden;
}

.card-price {
  line-height: 22px;
  height: 22px;
  font-size: 18px;
  color: #f57224;
}

.card-load-more {
  padding-top: 20px;
  padding-bottom: 9px;
  clear: both;
}

.card-load-more a.load-more-btn {
  margin: 0 auto;
  display: block;
  width: 387px;
  height: 40px;
  line-height: 40px;
  text-align: center;
  font-size: 14px;
  color: #1a9cb8;
  border: 1px solid #1a9cb8;
  cursor: pointer;
}
</style>
