<template>
  <b-modal id="createProductModal" :title="title" @hidden="onHidden" hide-footer>
    <div class="form-group">
      <div class="alert alert-danger" v-if="error_msg">{{ error_msg }}</div>
      <div class="form-group">
        <input
          type="text"
          v-validate="'required'"
          class="form-control"
          name="product_name"
          v-model="product_name"
          placeholder="Product Name"
        >
        <p class="error">{{ errors.first('product_name') }}</p>
      </div>
      <CategoryFormSelect @changeCategory="onChangeCategory" :current_option="category_id"/>
      <div class="form-group">
        <input
          type="number"
          step="0.01"
          min="0"
          v-validate="'required|decimal:2'"
          class="form-control"
          name="price"
          v-model="price"
          placeholder="Price"
        >
        <p class="error">{{ errors.first('price') }}</p>
      </div>
      <div class="form-group">
        <input
          type="number"
          min="0"
          v-validate="'required|decimal:0'"
          class="form-control"
          name="quantity_in_stock"
          v-model="quantity_in_stock"
          placeholder="Quantity in stock"
        >
        <p class="error">{{ errors.first('quantity_in_stock') }}</p>
      </div>
      <div class="form-group">
        <textarea
          class="form-control"
          v-validate="'required'"
          name="description"
          v-model="description"
          placeholder="Description"
        ></textarea>
        <p class="error">{{ errors.first('description') }}</p>
      </div>
      <div class="form-group">
        <input
          type="file"
          ref="file"
          v-validate="'image'"
          data-vv-as="image"
          name="product_img"
          class="form-control-file"
          @change="handleFileChange"
        >
        <img :src="img_src" alt v-if="img_src" class="preview">
        <p class="error">{{ errors.first('product_img') }}</p>
      </div>
      <div class="row justify-content-center">
        <button class="btn btn-light" @click.prevent="cancel">Cancel</button>
        <button class="btn btn-info" @click.prevent="submit">{{ submit_button }}</button>
      </div>
    </div>
  </b-modal>
</template>

<script>
import CategoryFormSelect from "@/components/CategoryFormSelect.vue";

export default {
  components: {
    CategoryFormSelect
  },
  props: {
    title: String
  },
  data() {
    return {
      product_name: "",
      category_id: 0,
      price: "",
      error_msg: "",
      quantity_in_stock: "",
      description: "",
      product_img: null,
      img_src: ""
    };
  },
  methods: {
    resetModal() {
      this.product_name = "";
      this.category_id = 0;
      this.price = "";
      this.error_msg = "";
      this.quantity_in_stock = "";
      this.description = "";
      this.product_img = null;
      this.img_src = "";
      this.$refs.file.value = "";
    },
    onHidden() {
      this.resetModal();
    },
    handleFileChange(e) {
      const image = e.target.files[0];
      if (image) {
        this.product_img = image;
        this.img_src = URL.createObjectURL(image);
      }
    },
    submit() {
      this.error_msg = "";
      this.$validator.validate().then(result => {
        if (!result) {
          return;
        }
        let formData = new FormData();
        formData.append("product_name", this.product_name);
        formData.append("category_id", this.category_id);
        formData.append("price", this.price);
        formData.append("quantity_in_stock", this.quantity_in_stock);
        formData.append("description", this.description);
        formData.append("product_img", this.product_img);
        this.$http
          .post("products", formData, {
            headers: {
              Authorization: `${localStorage.getItem("access_token")}`,
              "Content-Type": "multipart/form-data"
            }
          })
          .then(
            response => {
              if (response.body.result_code == 200) {
                this.$root.$emit("bv::hide::modal", "createProductModal");
                alert(
                  "Bạn vừa thêm sản phẩm " + this.product_name + " thành công!"
                );
                this.$emit("reload");
                this.resetModal();
              }
            },
            response => {
              this.error_msg = response.body.result_message;
            }
          );
      });
    },
    onChangeCategory(category_id) {
      this.category_id = category_id;
    }
  },
  computed: {
    submit_button() {
      return this.title.split(" ")[0];
    }
  }
};
</script>

<style scoped>
.preview {
  vertical-align: middle;
  max-height: 150px;
}
</style>
