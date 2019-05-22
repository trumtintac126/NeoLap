<template>
  <div id="site-banner" :style="{ 'background-color': background }">
    <div class="wrapper">
      <CategoryList/>
      <Slideshow @changeBackground="onChangeBackground"/>
    </div>
  </div>
</template>

<script>
import Slideshow from "@/components/Slideshow.vue";
import CategoryList from "@/components/CategoryList.vue";

export default {
  components: {
    Slideshow,
    CategoryList
  },
  data() {
    return {
      background: "rgb(239, 240, 245)"
    };
  },
  methods: {
    onChangeBackground(color) {
      this.background = color;
    }
  }
};
</script>

<style scoped>
</style>
