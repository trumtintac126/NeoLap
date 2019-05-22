<template>
  <div id="app">
    <AppNav/>
    <router-view/>
    <div>
      <small>
        Icons made by
        <a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from
        <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by
        <a
          href="http://creativecommons.org/licenses/by/3.0/"
          title="Creative Commons BY 3.0"
          target="_blank"
        >CC 3.0 BY</a>
      </small>
    </div>
  </div>
</template>

<script>
import AppNav from "@/components/AppNav.vue";

export default {
  components: {
    AppNav
  }
};
</script>

<style>
* {
  margin: 0;
  padding: 0;
}

*,
li,
ol,
ul {
  box-sizing: border-box;
}

#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  background-color: #f5f5f5;
}

.wrapper {
  width: 1188px;
  margin: 0 auto;
}

.right {
  text-align: right;
}

li,
ol,
ul {
  list-style: none;
}

#app a:hover {
  cursor: pointer;
  text-decoration: none;
}
</style>
