<template>
  <div>
    <div
      class="dropdown"
      v-if="role != 3"
      style="margin-top: 30px; text-align: left; margin-left: 50px"
    >
      <button
        class="btn btn-primary dropdown-toggle"
        type="button"
        id="dropdownMenuButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >Filter by {{filterMode}}</button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a
          class="dropdown-item"
          v-for="mode in filterBag"
          @click.prevent="changeFilter(mode)"
          :key="mode"
        >{{mode}}</a>
      </div>
    </div>
    <div>
      <div style="margin-top: 30px; margin-left: 65px">
        <div class="row">
          <h5 style="margin-right: 15px">From</h5>
          <input
            type="date"
            name="startDate"
            v-model="startDate"
            @input="loadChart()"
            style="margin-right: 15px"
          >
          <h5 style="margin-right: 15px">To</h5>
          <input type="date" name="endDate" v-model="endDate" @input="loadChart()">
        </div>
      </div>
    </div>
    <section
      class="container"
      style="position: relative; margin-top: 50px; margin-bottom: 50px; max-width: 2560px; width: 90%"
    >
      <bar-chart
        v-if="!isLoading && hasCampaign"
        :chart-data="this.chartData"
        :options="this.options"
      ></bar-chart>
    </section>
    <div class="spinner-border" role="status" v-if="isLoading && hasCampaign">
      <span class="sr-only">Loading...</span>
    </div>
    <div
      v-if="!hasCampaign && campaignData.length > 0"
      class="alert alert-danger"
      role="alert"
      style="width: 600px; margin-left: auto; margin-right: auto"
    >There are no campaigns during this timeline. Please repick another one.</div>
    <div
      v-if="!isLoading && campaignData.length == 0"
      class="alert alert-danger"
      role="alert"
      style="width: 400px; margin-left: auto; margin-right: auto"
    >This shop does not have any campaigns.</div>
  </div>
</template>

<script>
import BarChart from "@/components/BarChart";

export default {
  data() {
    return {
      filterBag: ["Date", "Product"],
      filterMode: "Date",
      startDate: this.getCurrentDate(),
      endDate: this.getCurrentDate(),
      isTimeRangeLegit: true,
      campaignData: [],
      isLoading: true,
      hasCampaign: true,
      chartData: null,
      options: null,
      role: localStorage.getItem("role")
    };
  },
  methods: {
    changeFilter(filterMode) {
      this.filterMode = filterMode;
    },
    getCurrentDate() {
      return (
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() > 10
          ? new Date().getMonth() + 1
          : "0" + (new Date().getMonth() + 1)) +
        "-" +
        new Date().getDate()
      );
    },
    getAllCampaigns() {
      this.campaignData = [];
      this.$http
        .get("profile/report", {
          headers: {
            Authorization: `${localStorage.getItem("access_token")}`
          }
        })
        .then(
          response => {
            if (response.body.result_code == 200) {
              this.campaignData = response.body.data.data_campaigns;
              this.loadChart();
            }
          },
          response => {}
        );
    },
    loadChart() {
      if (this.startDate > this.endDate) {
        return;
      }

      this.isLoading = true;
      let currentLabels = [];
      let currentData = [];
      let labelType = this.role == 3 ? "Profit" : "Views";
      this.campaignData.forEach(campaign => {
        if (
          !(campaign.start_day > this.endDate) &&
          !(campaign.end_day < this.startDate)
        ) {
          currentLabels.push(campaign.campaign_name);
          if (this.role == 3) {
            currentData.push(campaign.spend_money);
          } else {
            currentData.push(campaign.spend_money / campaign.bid_amount);
          }
        }
        this.chartData = {
          labels: currentLabels,
          datasets: [
            {
              label: labelType,
              backgroundColor: "#f87979",
              pointBackgroundColor: "white",
              borderWidth: 1,
              pointBorderColor: "#249EBF",
              data: currentData
            }
          ]
        };
      });
      this.isLoading = false;
      this.adjustChart(currentData);
    },
    adjustChart(data = []) {
      if (data.length > 0) {
        //Get max and average of views from all campaigns to smooth the chart. Min is assumed = 0.
        let maxValue = Math.max.apply(Math, data);
        let average = maxValue / data.length;
        if (maxValue > 0) {
          //Round average to nearest 10. We don't want 9.6 value between each column on x axis.
          average = Math.ceil(average / 10) * 10;
          //Make some surplus for maximum chart value.
          maxValue = Math.ceil(maxValue / average) * average + average;
        } else {
          maxValue = 10;
          average = 2;
        }

        this.options = {
          scales: {
            yAxes: [
              {
                maxBarThickness: 40,
                barPercentage: 0.8,
                gridLines: {
                  display: false
                }
              }
            ],
            xAxes: [
              {
                ticks: {
                  min: 0,
                  max: maxValue,
                  stepSize: average
                },
                gridLines: {
                  display: true
                }
              }
            ]
          },
          legend: {
            display: true
          },
          responsive: true,
          maintainAspectRatio: false
        };

        this.hasCampaign = true;
      } else {
        this.hasCampaign = false;
      }
    }
  },
  created() {
    this.getAllCampaigns();
  },
  watch: {
    startDate: function(newDate, oldDate) {
      if (newDate > this.endDate) {
        alert("Start date must be ealier or equal end date!!!");
        this.startDate = oldDate;
      }
    },
    endDate: function(newDate, oldDate) {
      if (newDate < this.startDate) {
        alert("End date must be later or equal start date!!!");
        this.endDate = oldDate;
      }
    }
  },
  name: "VueChartJS",
  components: {
    BarChart
  }
};
</script>