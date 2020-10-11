<template>
    <div class="row">
        <ul class="list__group" v-if="!$store.state.teamChartData.length">
            <li>No Records in the database</li>
        </ul>
        <div class="chartBtn-box" v-if="$store.state.teamChartData.length">
            <button class="chartBtn line" @click="selected = 'line'">Line Chart Analysis</button> 
            <button class="chartBtn pie" @click="selected = 'pie'">Pie Chart Analysis</button> 
            <button class="chartBtn bar" @click="selected = 'bar'">Column Chart Analysis</button> 
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
    /* background: #ba265d; */
    color: #fff;
    border: none;
    margin: 0 0.1rem;
    cursor: pointer;
    outline: none;
}

.line{
    /* background: #90ed7d; */
    background: #7cb5ec;

    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}

.pie {
    background: #f7a35c;
}

.bar {
    /* background: #7cb5ec; */
    background: #90ed7d;

    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
}

</style>


