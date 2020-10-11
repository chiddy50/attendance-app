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
  name: 'chart',
  computed: {
    ...mapState(['teamChartData']),

    teamData(){
        let name = []
        let population = []
        this.teamChartData.forEach(data => {
            name.push(data.name)
            population.push(data.population)
        })
        return { name, population }
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
        // console.log(this.teamData);
        let { name, population } = this.teamData
   
        Highcharts.chart('team-chart-container', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Population of Staffs per Team'
            },
            xAxis: {
                categories: name
            },

            plotOptions: {
                series: {
                    fillOpacity: 0.1
                }
            },
            yAxis: {
                min: 0,
                max: 40,
                title: {
                    text: 'Population'
                },
            },

            series: [{
                name: 'Team Population',
                data: population
                // data: [99, 34, 290, 65, 145, 105]
            }]
        });
        
    }
  }
}
</script>