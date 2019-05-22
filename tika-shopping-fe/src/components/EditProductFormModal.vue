<template>
  <b-modal @shown="onShown" id="editProductModal" :title="title" hide-footer>
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
          @keyup="onChange"
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
          @keyup="onChange"
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
          @keyup="onChange"
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
          @keyup="onChange"
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
        <img :src="img_src" ref="file" alt v-if="img_src" class="preview">
        <p class="error">{{ errors.first('product_img') }}</p>
      </div>
      <div class="row justify-content-center">
        <button class="btn btn-light" @click.prevent="cancel">Cancel</button>
        <button
          class="btn btn-info"
          @click.prevent="submit"
          :disabled="!isDifferent"
        >{{ submit_button }}</button>
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
    title: String,
    product_info: Object
  },
  data() {
    return {
      product_name: "",
      category_id: 0,
      price: "",
      quantity_in_stock: "",
      description: "",
      product_img: null,
      img_src: "",
      isDifferent: false,
      error_msg: ""
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
      this.isDifferent = false;
    },
    handleFileChange(e) {
      const image = e.target.files[0];
      if (image) {
        this.isDifferent = true;
        this.product_img = image;
        this.img_src = URL.createObjectURL(image);
      }
    },
    onShown() {
      this.$refs.file.value = "";
      this.product_name = this.product_info.product_name;
      this.category_id = this.product_info.category_id;
      this.price = this.product_info.price;
      this.quantity_in_stock = this.product_info.quantity_in_stock;
      this.description = this.product_info.description;
      this.img_src = this.$helpers.image(this.product_info.product_img);
    },
    onHidden() {
      this.resetModal();
    },
    onChangeCategory(category_id) {
      this.category_id = category_id;
      this.isDifferent = this.product_info.category_id != category_id;
    },
    onChange() {
      this.isDifferent =
        this.product_info.product_name != this.product_name ||
        this.product_info.price != this.price ||
        this.product_info.quantity_in_stock != this.quantity_in_stock ||
        this.product_info.description != this.description;
    },
    cancel() {
      this.$root.$emit("bv::hide::modal", "editCampaignModal");
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
          .post(
            `edit-product/${
              this.product_info.id
            }`,
            formData,
            {
              headers: {
                Authorization: `${localStorage.getItem("access_token")}`,
                "Content-Type": "multipart/form-data"
              }
            }
          )
          .then(
            response => {
              if (response.body.result_code == 200) {
                this.$root.$emit("bv::hide::modal", "editProductModal");
                alert(
                  "Bạn vừa sửa sản phẩm " + this.product_name + " thành công!"
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
