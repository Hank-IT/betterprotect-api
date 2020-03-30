<template>
    <div class="charts.mail-flow">
        <div class="toolbar">
            <b-row>
                <b-col md="4">
                    <b-btn variant="secondary" @click="renderChart"><i class="fas fa-sync"></i></b-btn>
                    <date-range-picker
                            :maxDate="maxDate"
                            :locale-data="locale"
                            :opens="opens"
                            :timePicker="timePicker"
                            :timePicker24Hour="timePicker24Hour"
                            v-model="dateRange"
                            @update="updateSelectedDate"
                    >
                        <div slot="input" slot-scope="picker">{{ currentStart | date }} - {{ currentEnd | date }}</div>
                    </date-range-picker>
                </b-col>
            </b-row>
        </div>

        <line-chart v-if="loaded" :chartdata="chartdata" :options="options"/>

        <div class="text-center" v-else>
            <div class="spinner-border spinner-3x3" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import LineChart from '../../components/LineChart';

    export default {
        name: 'LineChartContainer',
        components: {LineChart},
        data() {
            return {
                loaded: false,

                chartdata: null,

                options: {
                    responsive: true,
                    maintainAspectRatio: false
                },



                /**
                 * Datepicker
                 */
                maxDate: this.moment().format('YYYY/MM/DD HH:mm'),
                currentStart: this.moment().subtract(24, 'hours'),
                currentEnd: this.moment().add(1, 'minutes'),
                dateRange: {
                    startDate: this.moment().subtract(24, 'hours').format('YYYY/MM/DD HH:mm'),
                    endDate: this.moment().add(1, 'minutes').format('YYYY/MM/DD HH:mm'),
                },
                opens: 'right',
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    direction: 'ltr', //direction of text
                    format: 'YYYY/MM/DD HH:mm', // format of the dates displayed
                    separator: ' - ', //separator between the two ranges
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    weekLabel: 'W',
                    daysOfWeek: this.moment.weekdaysMin(), //array of days - see moment documentation for details
                    monthNames: this.moment.monthsShort(), //array of month names - see moment documentation for details
                    firstDay: 1 //ISO first day of week - see moment documentation for details
                },
            }
        },
        filters: {
            date: function(value) {
                if (!value) return '';

                return value.format('YYYY/MM/DD HH:mm');
            }
        },
        mounted() {
            this.renderChart();
        },
        methods: {
            async renderChart() {
                this.loaded = false;
                axios.get('/charts/mail-flow', {
                    params: {
                        startDate: this.currentStart.format('YYYY/MM/DD HH:mm'),
                        endDate: this.currentEnd.format('YYYY/MM/DD HH:mm'),
                    }
                }).then((response) => {
                    this.chartdata = response.data;
                    this.loaded = true;
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    } else {
                        this.$notify({
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }

                    this.loaded = false;
                });
            },
            async updateSelectedDate(data) {
                this.currentStart = this.moment(data.startDate);
                this.currentEnd = this.moment(data.endDate);

                this.renderChart();
            },
        }
    }
</script>|