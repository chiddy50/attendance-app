<template>
    <div class="row">
        <div class="chartBtn-box">
            <button class="chartBtn" @click="selected = 'line'">Line</button> 
            <button class="chartBtn" @click="selected = 'pie'">Pie</button> 
            <button class="chartBtn" @click="selected = 'bar'">Column</button> 
        </div>
        <div class="chart-box" v-if="selected === 'line'">
            <team-chart v-if="$store.state.teamChartData.length"></team-chart>
        </div>
        <div class="chart-box" v-if="selected === 'pie'">
            <pie-chart v-if="$store.state.teamChartData.length"></pie-chart>
        </div>
        <div class="chart-box" v-if="selected === 'bar'">
            <bar-chart v-if="$store.state.teamChartData.length"></bar-chart>
        </div>
        
    </div>
</template>

<script>
import { mapActions } from 'vuex'
import TeamChart from '../components/Chart'
import PieChart from '../components/PieChart'
import BarChart from '../components/BarChart'
export default {
    name: 'dashboard',
    components: {
        TeamChart,
        PieChart,
        BarChart
    },
    data(){
        return {
            selected: 'line'
        }
    },
    methods: {
      ...mapActions(['getChartData']),

    },
    beforeMount(){
        this.getChartData()
    }
}
</script>

<style scoped>
.chart-box{
    width: 100%;
}

.chartBtn-box{
    display: flex;
    margin-bottom: 2rem;
}

button.chartBtn,
button.chartBtn:visited,
button.chartBtn:active
{
    display: block;
    padding: 0.4rem 1.4rem;
    background: #ba265d;
    color: #fff;
    border: none;
    margin: 0 0.1rem;
    cursor: pointer;
    outline: none;
}

</style>


