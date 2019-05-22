<template>
  <div>
    <b-carousel
      id="carousel1"
      style="text-shadow: 1px 1px 2px #333;"
      indicators
      background="#ababab"
      :interval="4000"
      img-width="988"
      img-height="344"
      v-model="slide"
      @sliding-start="onSlideStart"
      @sliding-end="onSlideEnd"
    >
      <a v-for="item in items" :key="item.id" :href="item.url" target="_blank">
        <b-carousel-slide>
          <img
            slot="img"
            class="d-block img-fluid w-100"
            width="988"
            height="344"
            :src="$helpers.image(item.campaign_img)"
            onError="this.onerror=null;this.src='https://laz-img-cdn.alicdn.com/images/ims-web/TB1kht.I4YaK1RjSZFnXXa80pXa.jpg_1200x1200Q100.jpg_.webp';"
            alt="image slot"
            :style="'height: 344px'"
          >
        </b-carousel-slide>
      </a>
    </b-carousel>
  </div>
</template>

<script>
export default {
  data() {
    return {
      slide: 0,
      sliding: null,
      items: [
        {
          campaign_img:
            "https://laz-img-cdn.alicdn.com/images/ims-web/TB1kht.I4YaK1RjSZFnXXa80pXa.jpg_1200x1200Q100.jpg_.webp",
          url: "",
          backgroup_color: "rgb(239, 240, 245)"
        },
        {
          campaign_img:
            "https://laz-img-cdn.alicdn.com/images/ims-web/TB1NDJ_I4YaK1RjSZFnXXa80pXa.jpg_1200x1200.jpg",
          url: "",
          backgroup_color: "rgb(244, 201, 221)"
        },
        {
          campaign_img:
            "https://laz-img-cdn.alicdn.com/images/ims-web/TB1W0p.I4YaK1RjSZFnXXa80pXa.jpg_1200x1200Q100.jpg_.webp",
          url: "",
          backgroup_color: "rgb(133, 201, 210)"
        }
      ]
    };
  },
  created() {
    this.$http
      .get("campaign-client")
      .then(response => {
        if (response.body.result_code == 200) {
          this.items = response.body.data;
          this.$emit(
            "changeBackground",
            this.items[this.slide].backgroup_color
          );
        }
      });
  },
  methods: {
    onSlideStart(slide) {
      this.$emit("changeBackground", this.items[slide].backgroup_color);
      this.sliding = true;
    },
    onSlideEnd(slide) {
      this.sliding = false;
    }
  }
};
</script>

<style scoped>
#carousel1 {
  margin-left: 200px;
}
</style>
