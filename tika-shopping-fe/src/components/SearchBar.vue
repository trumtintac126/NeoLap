<template>
  <div id="searchBar">
    <div class="wrapper">
      <div class="search-wrapper">
        <div class="logo">
          <router-link to="/">
            <img src="../assets/logo.png" alt>
          </router-link>
        </div>
        <input type="text" placeholder="Tìm kiếm">
        <button class="search-btn">
          <img src="../assets/search.svg" alt>
        </button>
        <div class="nav-cart">
          <a href="#">
            <span class="cart-icon"></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
#searchBar {
  text-align: center;
  background-image: linear-gradient(to right, #013e53, #128099);
}

.logo {
  display: inline-block;
  width: 200px;
}

.logo img {
  height: 36px;
}

.search-wrapper {
  line-height: 30px;
  padding-top: 10px;
  padding-bottom: 40px;
  display: flex;
}

.search-btn {
  background-color: #f67124;
  width: 36px;
  height: 36px;
  border: unset;
}

.search-btn img {
  width: 50%;
  height: 50%;
}

input[type="text"] {
  width: 688px;
  border: unset;
  height: 36px;
  font-size: 14px;
  box-shadow: none;
  line-height: 100%;
  padding-left: 20px;
}

.nav-cart {
  display: inline-block;
  height: 30px;
  width: 36px;
  position: relative;
  margin: 0 0 0 20px;
}

.cart-icon {
  width: 29px;
  height: 26px;
  background-image: url("https://laz-img-cdn.alicdn.com/tfs/TB1nQJUcwmTBuNjy1XbXXaMrVXa-986-930.png");
  background-position: -380px -714px;
  position: absolute;
  left: 50%;
  top: 50%;
  margin: -9px 0 0 -14px;
}
</style>
