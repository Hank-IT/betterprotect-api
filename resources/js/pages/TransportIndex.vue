<template>
    <div class="transport.index">
        <b-row>
            <b-col md="3" >
                <b-button-group>
                    <button type="button" class="btn btn-primary" @click="openModal()"><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="getTransports"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>

            <b-col md="4" offset="5" >
                <b-form-group >
                    <b-input-group>
                        <b-form-input v-model="filter" placeholder="Suche" />
                        <b-input-group-append>
                            <b-btn :disabled="!filter" @click="filter = ''">Leeren</b-btn>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>

        <template v-if="!loading">
            <b-table hover :items="transports" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage" v-if="transports.length">
                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-warning btn-sm" @click="deleteTransport(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>

            <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" v-if="totalRows > 10"></b-pagination>
        </template>

        <div class="text-center" v-if="loading">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>

        <transport-store-modal v-on:transport-stored="getTransports"></transport-store-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getTransports();
        },
        data() {
            return {
                transports: [],

                /**
                 * Loader
                 */
                loading: false,

                /**
                 * Pagination
                 */
                currentPage: 1,
                perPage: 10,
                totalRows: null,

                /**
                 * Table
                 */
                fields: [
                    {
                        key: 'domain',
                        label: 'Domain',
                        sortable: true,
                    },
                    {
                        key: 'transport',
                        label: 'Transport',
                        sortable: true,
                    },
                    {
                        key: 'nexthop',
                        label: 'Nexthop',
                        sortable: true,
                    },
                    {
                        key: 'nexthop_port',
                        label: 'Nexthop Port',
                    },
                    {
                        key: 'nexthop_mx',
                        label: 'Nexthop MX',
                        sortable: true,
                    },
                    {
                        key: 'created_at',
                        label: 'Erstellt am',
                        sortable: false,
                    },
                    {
                        key: 'app_actions',
                        label: ''
                    },
                ],

                /**
                 * Table search
                 */
                filter: null,
            }
        },
        methods: {
            openModal() {
                this.$bvModal.show('transport-store-modal');
            },
            getTransports() {
                this.loading = true;
                axios.get('/transport').then((response) => {
                    this.transports = response.data.data;
                    this.totalRows = this.transports.length;
                    this.loading = false;
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                    this.loading = false;
                });
            },
            deleteTransport(data) {
                axios.delete('/transport/' + data.item.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getTransports();
                    }).catch((error) => {
                        if (error.response) {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }
                });
            }
        }
    }
</script>
