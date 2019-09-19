<template>
    <div class="recipient.index">
        <b-row>
            <b-col md="3" >
                <b-button-group>
                    <button type="button" class="btn btn-primary" v-b-modal.recipient-store-modal><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="getRecipients"><i class="fas fa-sync"></i></b-btn>
                    <button type="button" class="btn btn-secondary" v-b-modal.ldap-query-modal>LDAP</button>
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
            <b-table hover :items="recipients" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage" v-if="recipients.length">
                <template slot="action" slot-scope="data">
                    <p :class="{ 'text-success': data.value === 'ok', 'text-danger': data.value === 'reject' }">{{ data.value }}</p>
                </template>

                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-warning btn-sm" @click="deleteRecipient(data)"><i class="fas fa-trash-alt"></i></button>
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

        <recipient-store
                v-on:recipient-stored="getRecipients"
        ></recipient-store>
        <query-ldap-recipients></query-ldap-recipients>
    </div>
</template>

<script>
    export default {
        created() {
            this.getRecipients();

            window.Echo.private('task')
                .listen('Task', (e) => {
                    let task = e.task;

                    if (task.task === 'query-ldap-recipients' && task.status === 3) {
                        this.getRecipients();
                    }
                });
        },
        data() {
            return {
                /**
                 * Loader
                 */
                loading: false,

                currentPage: 1,
                perPage: 10,
                totalRows: null,
                recipients: [],
                filter: null,
                fields: [
                    {
                        key: 'payload',
                        label: 'Eintrag',
                        sortable: true,
                    },
                    {
                        key: 'action',
                        label: 'Aktion',
                        sortable: true
                    },
                    {
                        key: 'data_source',
                        label: 'Quelle',
                        sortable: false,
                    },
                    {
                        key: 'app_actions',
                        label: ''
                    }
                ]
            }
        },
        methods: {
            getRecipients() {
                this.loading = true;
                axios.get('/recipient').then((response) => {
                    this.recipients = response.data.data;
                    this.totalRows = this.recipients.length;
                    this.loading = false;
                }).catch(function (error) {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                    this.loading = false;
                });
            },
            deleteRecipient(data) {
                axios.delete('/recipient/' + data.item.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getRecipients();
                    }).catch(function (error) {
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }
                    } else {
                        this.$notify({
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }
                });
            }
        }
    }
</script>
