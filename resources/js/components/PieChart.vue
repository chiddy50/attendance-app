<template>
  <div id="team-chart-container"></div>
</template>

<script>
import { mapState } from 'vuex'
import Highcharts from 'highcharts'
import Exporting from 'highcharts/modules/exporting';
// Initialize exporting module.
Exporting(Highcharts);
export default {
  name: 'pieChart',
  computed: {
    ...mapState(['teamChartData']),
    teamData(){
        let chartData = []
        this.teamChartData.forEach(data => {
            chartData.push({
                name: data.name,
                y: data.population
            })
        })
        return chartData
    }
  },
  mounted(){
    this.setup()
    
  },
  watch: {
    teamData(){
      this.setup()
    }
  },
  methods: {
    setup(obj){
        let data = this.teamData
        Highcharts.chart('team-chart-container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Population of staffs per team'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            series: [{
                name: 'Teams',
                colorByPoint: true,
                data: data
            }]
        });
    }
  }
}
</script>