<script>
    import { Line } from 'vue-chartjs';
    const { reactiveProp } = mixins;

    export default {
        extends: Line,
        mixins: [reactiveProp],
        data () {
            return {
                gradient: null,
                gradient2: null
            }
        },
        props: {
            chartdata: {
                type: Object,
                default: null
            },
            options: {
                type: Object,
                default: null
            }
        },
        mounted () {
            this.renderChart(this.chartdata, this.options)
        }
    }
</script>