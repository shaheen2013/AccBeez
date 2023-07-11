
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
            <el-col :span="6">
              <h5 style="font-weight: bold; margin-bottom: 0.1rem">
                Total Users
              </h5>
              <span>202</span>
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
              <span>202</span>
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
              <span>202</span>
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
              <span>202</span>
            </el-col>
          </el-row>
        </div>
      </el-card>
    </el-col>
  </el-row>

 
</template>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      size: "50px",
      color: "green",
      chartOptions: {
        chart: {
          id: "vuechart-example",
        },
        xaxis: {
          categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
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
