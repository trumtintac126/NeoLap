<template>
  <div id="addProduct" class="wrapper">
    <div class="campaign-container">
      <div class="col-md-12">
        <h4>Create Campaign</h4>
        <hr>
        <div class="campaign-content">
          <div class="alert alert-danger" v-if="error_msg">{{ error_msg }}</div>
          <form>
            <div class="card">
              <div
                class="card-header custom-card-header"
                data-toggle="collapse"
                data-target="#collapseDetail"
                aria-expanded="true"
                aria-controls="collapseDetail"
                role="button"
              >
                <h5>Detail</h5>
                <font-awesome-icon icon="angle-down" pull="right"/>
              </div>
              <div class="collapse show" id="collapseDetail">
                <div class="card-body">
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Name</label>
                    <div class="col-8 custom-style">
                      <div class="input-group">
                        <input
                          type="text"
                          name="campaign_name"
                          v-model="campaign_name"
                          v-validate="'required'"
                          data-vv-as="Campaign Name"
                          placeholder="Name"
                          class="form-control"
                        >
                        <p class="error">{{ errors.first('campaign_name') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">User Status</label>
                    <div class="col-8 custom-style">
                      <div class="input-group">
                        <select name="status" class="form-control" v-model="user_status">
                          <option value="1">ACTIVE</option>
                          <option value="0">PAUSED</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div
                class="card-header custom-card-header"
                data-toggle="collapse"
                data-target="#collapseSchedule"
                aria-expanded="true"
                aria-controls="collapseSchedule"
                role="button"
              >
                <h5>Schedule</h5>
                <font-awesome-icon icon="angle-down" pull="right"/>
              </div>
              <div class="collapse show" id="collapseSchedule">
                <div class="card-body">
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Start date</label>
                    <div class="col-8 custom-style">
                      <div class="input-left">
                        <input
                          type="date"
                          name="start_date"
                          v-model="start_date"
                          class="form-control"
                          v-validate="'date_format:YYYY-MM-DD'"
                          data-vv-as="Start Date"
                          required
                        >
                        <p class="error">{{ errors.first('start_date') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">End date</label>
                    <div class="col-8 custom-style">
                      <div class="input-left">
                        <input
                          type="date"
                          name="end_date"
                          v-model="end_date"
                          class="form-control"
                          v-validate="'date_format:YYYY-MM-DD'"
                          data-vv-as="End Date"
                          required
                        >
                        <p class="error">{{ errors.first('end_date') }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div
                class="card-header custom-card-header"
                data-toggle="collapse"
                data-target="#collapseBudget"
                aria-expanded="true"
                aria-controls="collapseBudget"
                role="button"
              >
                <h5>Budget</h5>
                <font-awesome-icon icon="angle-down" pull="right"/>
              </div>
              <div class="collapse show" id="collapseBudget">
                <div class="card-body">
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Budget</label>
                    <div class="col-8 custom-style">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <font-awesome-icon icon="dollar-sign"/>
                          </span>
                        </div>
                        <input
                          type="number"
                          name="budget"
                          v-model="budget"
                          placeholder="Budget"
                          min="1"
                          class="form-control"
                          v-validate="'required|decimal:2'"
                          data-vv-as="Budget Amount"
                        >
                        <p class="error">{{ errors.first('budget') }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div
                class="card-header custom-card-header"
                data-toggle="collapse"
                data-target="#collapseBidding"
                aria-expanded="true"
                aria-controls="collapseBidding"
                role="button"
              >
                <h5>Bidding</h5>
                <font-awesome-icon icon="angle-down" pull="right"/>
              </div>
              <div class="collapse show" id="collapseBidding">
                <div class="card-body">
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Bid Amount</label>
                    <div class="col-8 custom-style">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <font-awesome-icon icon="dollar-sign"/>
                          </span>
                        </div>
                        <input
                          type="number"
                          name="bid_amount"
                          v-model="bid_amount"
                          placeholder="Bidding Amount"
                          min="1"
                          class="form-control"
                          v-validate="'required|decimal:2'"
                          data-vv-as="Bidding Amount"
                        >
                        <p class="error">{{ errors.first('bid_amount') }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div
                class="card-header custom-card-header"
                data-toggle="collapse"
                data-target="#collapseCreative"
                aria-expanded="true"
                aria-controls="collapseCreative"
                role="button"
              >
                <h5>Creative</h5>
                <font-awesome-icon icon="angle-down" pull="right"/>
              </div>
              <div class="collapse show" id="collapseCreative">
                <div class="card-body">
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Title</label>
                    <div class="col-8">
                      <input
                        type="text"
                        name="title"
                        v-model="title"
                        placeholder="Title"
                        class="form-control"
                        v-validate="'required'"
                        data-vv-as="Title"
                      >
                      <p class="error">{{ errors.first('title') }}</p>
                    </div>
                  </div>

                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Description</label>
                    <div class="col-8">
                      <textarea
                        name="description"
                        v-model="description"
                        placeholder="Description"
                        class="form-control"
                        rows="4"
                        v-validate="'required'"
                        data-vv-as="Description"
                      ></textarea>
                      <p class="error">{{ errors.first('description') }}</p>
                    </div>
                  </div>
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Creative preview</label>
                    <div class="col-8 custom-style">
                      <div class="input-group">
                        <input
                          type="file"
                          ref="file"
                          v-validate.disable="'required|image'"
                          data-vv-as="Creative Image"
                          name="creative_img"
                          class="form-control-file"
                          @change="handleFileChange"
                        >
                        <p class="error">{{ errors.first('creative_img') }}</p>
                      </div>
                    </div>
                    <div class="offset-4 col-8 img-preview">
                      <img :src="img_src" alt v-if="img_src">
                    </div>
                  </div>
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Background color</label>
                    <div class="col-8 custom-style">
                      <ColorPicker :color="background_color" v-model="background_color"/>
                    </div>
                  </div>
                  <div class="form-group row custom-form-row">
                    <label class="col-4 col-form-label">Final URL</label>
                    <div class="col-8">
                      <input
                        type="text"
                        name="finalURL"
                        v-model="url"
                        placeholder="Final URL"
                        class="form-control"
                        v-validate="{url: {require_protocol: true }, required: true}"
                        data-vv-as="Final URL"
                      >
                      <p class="error">{{ errors.first('finalURL') }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group form-contain-button">
              <b-button variant="link" to="/campaign">Back</b-button>
              <button class="btn btn-info" @click.prevent="submit">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ColorPicker from "@/components/ColorPicker.vue";
import Vue from "vue";
import VeeValidate from "vee-validate";

Vue.use(VeeValidate, {
  events: "blur"
});

export default {
  data() {
    return {
      campaign_name: "",
      user_status: "1",
      start_date: "",
      end_date: "",
      budget: "",
      bid_amount: "",
      title: "",
      description: "",
      campaign_img: null,
      img_src: "",
      background_color: "#FF0000",
      url: ""
    };
  },
  components: {
    ColorPicker
  },
  methods: {
    handleFileChange(e) {
      const image = e.target.files[0];
      if (image) {
        this.campaign_img = image;
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
        formData.append("campaign_name", this.campaign_name);
        formData.append("user_status", this.user_status);
        formData.append("start_day", this.start_date);
        formData.append("end_day", this.end_date);
        formData.append("budget", this.budget);
        formData.append("bid_amount", this.bid_amount);
        formData.append("title", this.title);
        formData.append("description", this.description);
        formData.append("campaign_img", this.campaign_img);
        formData.append("backgroup_color", this.background_color);
        formData.append("url", this.url);
        this.$http
          .post(
            "campaigns",
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
                alert(
                  "Bạn vừa thêm chiến dịch " +
                    this.campaign_name +
                    " thành công!"
                );
                this.$router.push("/campaign");
              }
            },
            response => {
              this.error_msg = response.body.result_message;
            }
          );
      });
    }
  }
};
</script>

<style scoped>
.campaign-container {
  display: flex;
  flex-wrap: wrap;
  margin: 0;
  justify-content: center;
  padding: 20px;
  background: white;
}

.card {
  margin-bottom: 20px;
}

.custom-card-header {
  display: inline-flex;
  justify-content: space-between;
}

.custom-card-header:hover {
  cursor: pointer;
}

.custom-card-header h5 {
  margin: 0;
}

.custom-form-row {
  text-align: right;
}

.custom-style {
  display: inherit;
}

.custom-style select {
  display: block;
  width: 30%;
}

.custom-style input[type="text"] {
  width: 40%;
}

.input-left {
  width: 40%;
}

.input-group {
  width: 40%;
}

.form-contain-button {
  display: flex;
  justify-content: flex-end;
}

.img-preview img {
  width: 100%;
  height: auto;
}
</style>
