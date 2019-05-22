<template>
  <div class="home">
    <Banner/>
    <ProductList/>
  </div>
</template>

<script>
import Banner from "@/components/Banner.vue";
import ProductList from "@/components/ProductList.vue";

export default {
  name: "home",
  components: {
    Banner,
    ProductList
  }
};
</script>
