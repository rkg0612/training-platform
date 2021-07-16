<script>
import { HorizontalBar, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins;

export default {
  extends: HorizontalBar,
  mixins: [reactiveProp],
  mounted() {
    this.renderChart(this.chartData, {
      title: {
        display: true,
        text: [
          "Team's Total Course Progress",
          'Based on the percentage of units completed',
        ],
        position: 'top',
        fontFamily: 'Roboto',
        fontColor: 'rgba(0, 0, 0, 0.50)',
        fontSize: 16,
      },
      scales: {
        xAxes: [
          {
            ticks: {
              min: 0,
              callback: function (value, index, values) {
                return value + '%';
              },
            },
          },
        ],
      },
      responsive: true,
    });
  },
};
</script>
