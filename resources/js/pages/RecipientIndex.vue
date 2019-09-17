<template>
    <div class="recipient.index">
        <b-row>
            <b-col md="1" >
                <b-button-group>
                    <button type="button" class="btn btn-primary" v-b-modal.recipient-store-modal><i class="fas fa-plus"></i></button>
                    <button type="button" class="btn btn-secondary" v-b-modal.ldap-query-modal>LDAP</button>
                </b-button-group>
            </b-col>

            <b-col md="6" offset="5" >
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

        <b-table hover :items="recipients" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage">
            <template slot="action" slot-scope="data">
                <p :class="{ 'text-success': data.value === 'ok', 'text-danger': data.value === 'reject' }">{{ data.value }}</p>
            </template>

            <!-- A virtual composite column -->
            <template slot="app_actions" slot-scope="data">
                <!-- <button class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button> -->
                <button class="btn btn-warning btn-sm" @click="deleteRecipient(data)"><i class="fas fa-trash-alt"></i></button>
            </template>
        </b-table>

        <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" v-if="totalRows > 10"></b-pagination>

        <recipient-store
                v-on:recipient-stored="addRecipient"
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
                axios.get('/recipient').then((response) => {
                    this.recipients = response.data.data;

                    this.totalRows = this.recipients.length;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            deleteRecipient(data) {
                axios.delete('/recipient/' + data.item.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        let recipientIndex = this.recipients.findIndex(x => x.id === data.item.id);

                        this.$delete(this.recipients, recipientIndex);
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
            },
            addRecipient(data) {
                this.recipients.push(data);
            }
        }
    }
</script>
