<template>
  <div class="form-group">
    <select class="form-control" id="category" @change="onChange($event)" v-model="current_option">
      <option value="0" disabled hidden>Category</option>
      <optgroup v-for="item in parent_categories" :key="item.id" :label="item.category_name">
        <option
          v-for="child in item.childs"
          :key="child.id"
          :value="child.id"
        >{{child.category_name}}</option>
      </optgroup>
      <option
        v-for="item in non_parent_categories"
        :key="item.id"
        :value="item.id"
      >{{item.category_name}}</option>
    </select>
  </div>
</template>

<script>
export default {
  props: {
    current_option: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      items: []
    };
  },
  computed: {
    parent_categories() {
      return this.items.filter(item => {
        return item.childs;
      });
    },
    non_parent_categories() {
      return this.items.filter(item => {
        return !item.childs;
      });
    }
  },
  methods: {
    onChange(event) {
      this.$emit("changeCategory", this.current_option);
    }
  },
  created() {
    this.$http
      .get("category")
      .then(response => {
        if (response.body.result_code == 200) {
          this.items = response.body.data;
        }
      });
  }
};
</script>

<style>
</style>
