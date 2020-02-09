<template>
    <div class="server.queue">
        <div class="toolbar">
            <b-row class="mb-2">
                <b-col md="2">
                    <b-button-group>
                        <b-btn variant="primary" :disabled="! $auth.check(['editor', 'administrator'])" @click="flushQueue"><i class="fas fa-paper-plane"></i> Flush</b-btn>
                        <b-btn variant="secondary" @click="getQueue"><i class="fas fa-sync"></i></b-btn>
                    </b-button-group>
                </b-col>
            </b-row>
        </div>

        <template v-if="!loading">
            <b-table hover
                     :items="queue"
                     :fields="fields"
                     :sort-by.sync="sortBy"
                     :sort-desc.sync="sortDesc"
                     v-if="queue.length"
                     @row-clicked="showModal"
            >
                <template v-slot:cell(arrival_time)="data">
                    {{ moment.unix(data.item.arrival_time).format("YYYY-MM-DD HH:mm:ss") }}
                </template>

                <template v-slot:cell(sender)="data">
                    <span v-b-popover.hover="data.item.sender">
                        {{ data.item.sender.trunc(40) }}
                    </span>
                </template>

                <template v-slot:cell(app_actions)="data">
                    <button :disabled="! $auth.check(['editor', 'administrator'])" class="btn btn-danger btn-sm" @click="deleteRow(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>

            <b-row v-if="totalRows > 10">
                <b-col cols="2">
                    <b-form-select v-model="perPage" :options="displayedRowsOptions" v-if="!loading" @change="getQueue"></b-form-select>
                </b-col>
                <b-col cols="2" offset="3">
                    <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" @change="changePage" v-if="!loading"></b-pagination>
                </b-col>
                <b-col cols="2" offset="3" v-if="queue.length">
                    <p class="mt-1">Zeige Zeile {{ from }} bis {{ to }} von {{ totalRows }} Zeilen.</p>
                </b-col>
            </b-row>
        </template>

        <are-you-sure-modal v-on:answered-yes="deleteMail" v-on:answered-no="row = null"></are-you-sure-modal>

        <b-modal id="queue-detail-modal" ref="queueDetailModal" size="xl" title="Queue Detail" hide-footer>
            <table class="table">
                <tr>
                    <th>Empfänger</th>
                    <th>Grund</th>
                </tr>
                <tr v-for="recipient in detailRow.recipients">
                    <td>{{ recipient.address }}</td>
                    <td>{{ recipient.delay_reason }}</td>
                </tr>
            </table>
        </b-modal>

        <div class="text-center" v-if="loading">
            <div class="spinner-border spinner-3x3" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            this.getQueue();
        },
        data() {
            return {
                /**
                 * Pagination
                 */
                currentPage: 1,
                perPage: 10,
                to: 0,
                from: 0,
                totalRows: null,
                sortBy: 'arrival_time',
                sortDesc: true,
                displayedRowsOptions: [
                    { value: 10, text: 10 },
                    { value: 25, text: 25 },
                    { value: 50, text: 50 },
                    { value: 100, text: 100 },
                ],

                /**
                 * Search
                 */
                search: null,

                queue: [],
                loading: false,

                /**
                 * Table
                 */
                fields: [
                    {
                        key: 'server',
                        label: 'Server',
                    },
                    {
                        key: 'arrival_time',
                        label: 'Empfangen',
                        sortable: true,
                    },
                    {
                        key: 'sender',
                        label: 'Absender',
                    },
                    {
                        key: 'message_size',
                        label: 'Größe',
                    },
                    {
                        key: 'queue_name',
                        label: 'Queue',
                    },
                    {
                        key: 'queue_id',
                        label: 'Queue ID',
                    },
                    {
                        key: 'app_actions',
                        label: 'Optionen'
                    }
                ],
                /**
                 * Are you sure modal
                 */
                row: null,

                detailRow: {
                    recipients: [],
                },
            }
        },
        methods: {
            deleteRow(data) {
                this.row = data.item;
                this.$bvModal.show('are-you-sure-modal');
            },
            changePage(data) {
                this.currentPage = data;
                this.getQueue();
            },
            showModal(record, index) {
                this.$refs.queueDetailModal.show();
                this.detailRow = record;
            },
            getQueue() {
                this.loading = true;

                axios.get('/server/queue', {
                    params: {
                        currentPage: this.currentPage,
                        perPage: this.perPage,
                        search: this.search,
                    }
                })
                    .then((response) => {
                        if (response.data.data.length !== 0) {
                            this.queue = response.data.data.data;
                            this.totalRows = response.data.data.total;
                            this.from = response.data.data.from;
                            this.to = response.data.data.to;
                        } else {
                            this.queue = [];
                        }

                        this.loading = false;
                    })
                    .catch((error) => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.$notify({
                                    title: error.response.data.errors.payload[0],
                                    type: 'error'
                                });
                            } else {
                                this.$notify({
                                    title: error.response.data.message,
                                    type: 'error'
                                });
                            }
                        }

                        this.loading = false;
                    });
            },
            flushQueue() {
                axios.post('/server/queue')
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getQueue();
                    })
                    .catch((error) => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.$notify({
                                    title: error.response.data.errors.payload[0],
                                    type: 'error'
                                });
                            } else {
                                this.$notify({
                                    title: error.response.data.message,
                                    type: 'error'
                                });
                            }
                        }
                    });
            },
            deleteMail() {
                axios.delete('/server/' + this.row.server_id + '/queue?queue_id=' + this.row.queue_id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getQueue();
                    })
                    .catch((error) => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.$notify({
                                    title: error.response.data.errors.payload[0],
                                    type: 'error'
                                });
                            } else {
                                this.$notify({
                                    title: error.response.data.message,
                                    type: 'error'
                                });
                            }
                        }
                    });
            }
        }
    }
</script>
