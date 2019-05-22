<template>
  <div class="menu-dropdown" @mouseleave="onMouseLeave">
    <div class="primary-menu">
      <ul>
        <li
          v-for="(item, index) in items"
          :key="index"
          :class="{ active: index == currentCategoryId }"
        >
          <a @mouseover="onMouseOver(index)">
            <span>{{ item.category_name }}</span>
            <span class="arrow" v-show="item.childs">></span>
          </a>
        </li>
      </ul>
    </div>
    <div
      class="secondary-menu"
      v-if="currentCategoryId != null && items[currentCategoryId].childs != null"
    >
      <ul>
        <li v-for="child in items[currentCategoryId].childs" :key="child.id">
          <a>
            <span>{{ child.category_name }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentCategoryId: 1,
      items: null
    };
  },
  created() {
    this.currentCategoryId = null;
    this.$http
      .get("category")
      .then(
        response => {
          if (response.body.result_code == 200) {
            this.items = response.body.data;
          }
        },
        response => {}
      );
  },
  methods: {
    onMouseOver(index) {
      this.currentCategoryId = index;
    },
    onMouseLeave() {
      this.currentCategoryId = null;
    }
  }
};
</script>


<style scoped>
.menu-dropdown {
  width: 400px;
  height: 344px;
  position: absolute;
  padding: 0;
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.25);
}

.primary-menu {
  width: 200px;
  height: 344px;
  position: absolute;
  background-color: #fff;
  z-index: 200;
  padding: 0;
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.25);
}

.secondary-menu {
  width: 200px;
  height: 344px;
  position: absolute;
  background-color: #fff;
  z-index: 200;
  left: 200px;
  padding: 0;
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.25);
}

.menu-dropdown ul {
  padding: 4px 0 12px;
  text-align: left;
  overflow: hidden;
  height: 100%;
  margin: 0;
}

.menu-dropdown li {
  position: relative;
}

.menu-dropdown li:hover {
  background-color: #eceff1;
}

.menu-dropdown .arrow {
  position: absolute;
  right: 8px;
}

.menu-dropdown a {
  display: block;
  padding-left: 8px;
  height: 27px;
  line-height: 27px;
  text-decoration: none;
  cursor: pointer;
}

.menu-dropdown a > span {
  overflow: hidden;
  display: inline-block;
  font-size: 13px;
  color: #757575;
}

.menu-dropdown a:hover > span,
.menu-dropdown li.active span {
  color: #1a9cb7;
}
</style>
