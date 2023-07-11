
<template>
  <el-row :gutter="12">
    <el-col :span="6">
      <el-card shadow="always" :body-style="{ padding: '0px' }">
        <div style="padding-top: 8px; padding-left: 8px">
          <el-row>
            <el-col :span="4">
              <el-icon :size="size" :color="color">
                <Avatar />
              </el-icon>
            </el-col>
            <el-col :span="10">
              <h5 style="font-weight: bold; margin-bottom: 0.1rem">
                Total Bom Sales
              </h5>
              <span>{{ company?.bom_sales_count }}</span>
            </el-col>
          </el-row>
        </div>
      </el-card>
    </el-col>

    <el-col :span="6">
      <el-card shadow="always" :body-style="{ padding: '0px' }">
        <div style="padding-top: 8px; padding-left: 8px">
          <el-row>
            <el-col :span="4">
              <el-icon :size="size" :color="color">
                <ShoppingCartFull />
              </el-icon>
            </el-col>
            <el-col :span="6">
              <h5 style="font-weight: bold; margin-bottom: 0.1rem">
                Total Sells
              </h5>
              <span>{{ company?.sales_count }}</span>
            </el-col>
          </el-row>
        </div>
      </el-card>
    </el-col>

    <el-col :span="6">
      <el-card shadow="always" :body-style="{ padding: '0px' }">
        <div style="padding-top: 8px; padding-left: 8px">
          <el-row>
            <el-col :span="4">
              <el-icon :size="size" :color="color">
                <List />
              </el-icon>
            </el-col>
            <el-col :span="6">
              <h5 style="font-weight: bold; margin-bottom: 0.1rem">
                Total Boms
              </h5>
              <span>{{ company?.boms_count }}</span>
            </el-col>
          </el-row>
        </div>
      </el-card>
    </el-col>

    <el-col :span="6">
      <el-card shadow="always" :body-style="{ padding: '0px' }">
        <div style="padding-top: 8px; padding-left: 8px">
          <el-row>
            <el-col :span="4">
              <el-icon :size="size" :color="color">
                <Coin />
              </el-icon>
            </el-col>
            <el-col :span="6">
              <h5 style="font-weight: bold; margin-bottom: 0.1rem">
                Total Bills
              </h5>
              <span>{{ company?.bills_count }}</span>
            </el-col>
          </el-row>
        </div>
      </el-card>
    </el-col>
  </el-row>
  <div class="mt-5">
    <el-row :gutter="12">
      <el-col :span="12">
        <apexchart
          width="800"
          type="bar"
          :options="chartOptions"
          :series="series"
        ></apexchart>
      </el-col>
      <el-col :span="12">
        <apexchart width="650" type="donut" :options="donatChartOptions" :series="donatSeries"></apexchart>
      </el-col>
    </el-row>
  </div>
</template>

<script>

export default {
  name: "Dashboard",
  data() {
    return {
      company: null,
      size: "50px",
      color: "green",
      donatChartOptions: {
        labels: ['Bom Sales','Total Bom', 'Bills', 'Sales']
      },
      donatSeries: [],
      chartOptions: {
        chart: {
          id: "vuechart-example",
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        },
      },
      series: [
        {
          name: "series-1",
          data: [30, 40, 35, 50, 49, 60, 70, 91],
        },
      ],
      cards: [
        { title: "Card 1", content: "This is some sample content for Card 1." },
        { title: "Card 2", content: "This is some sample content for Card 2." },
        { title: "Card 3", content: "This is some sample content for Card 3." },
        { title: "Card 3", content: "This is some sample content for Card 3." },
      ],
    };
  },
  async created() {
    try {
            await axios.get(`/api/company/overview?slug=` + this.$route.params.slug).
                    then((res) => {
                        console.log('res:', res);
                        this.company = res.data.company;
                        this.donatSeries.push(res.data.company.bom_sales_count);
                        this.donatSeries.push(res.data.company.sales_count);
                        this.donatSeries.push(res.data.company.boms_count);
                        this.donatSeries.push(res.data.company.bills_count);
                    });
        } catch (error) {
            console.error(error);
        }
  }
};
</script>

<style scoped>
.warning-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
}

.content-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.content-wrapper img {
  max-width: 100%;
  height: auto; /* Maintain the aspect ratio of the image */
  margin-bottom: 16px; /* Add margin below the image */
}
</style>
