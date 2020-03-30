<template>
    <div class="access.index">
        <b-row>
            <b-col md="3" >
                <access-store
                        v-on:access-stored="getAccessRules"
                        v-on:reload-table="getAccessRules"
                ></access-store>
            </b-col>

            <b-col md="4" offset="5">
                <b-form-group >
                    <b-input-group>
                        <b-form-input v-model="search" placeholder="Suche Eintrag" @change="getAccessRules"/>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>

        <template v-if="!loading">
            <b-table hover :items="rules" :fields="fields" @row-clicked="showModal" v-if="rules.length" :tbody-tr-class="rowClass">
                <template v-slot:cell(client_full)="data">
                    <span v-if="data.item.client_payload === '*'">
                        <span class="text-danger font-italic">Alles</span>
                    </span>
                    <span v-else>
                        {{ data.item.client_payload }} ({{ data.item.client_type }})
                    </span>
                </template>

                <template v-slot:cell(sender_full)="data">
                    <span v-if="data.item.sender_payload === '*'">
                        <span class="text-danger font-italic">Alles</span>
                    </span>
                    <span v-else>
                        {{ data.item.sender_payload }} ({{ data.item.sender_type }})
                    </span>
                </template>

                <template v-slot:cell(action_formatted)="data">
                    <span v-if="data.item.action.toLowerCase() === 'ok'">
                        <span class="text-success">{{ translate('postfix.mail.action.' + data.item.action.toLowerCase()) }}</span>
                    </span>
                    <span v-else>
                        <span class="text-danger">{{ translate('postfix.mail.action.' + data.item.action.toLowerCase()) }}</span>
                    </span>
                </template>

                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-secondary btn-sm" :disabled="! $auth.check(['editor', 'administrator'])" @click="moveUp(data)"><i class="fas fa-chevron-up"></i></button>
                    <button class="btn btn-secondary btn-sm" :disabled="! $auth.check(['editor', 'administrator'])" @click="moveDown(data)"><i class="fas fa-chevron-down"></i></button>
                    <button class="btn btn-warning btn-sm" :disabled="! $auth.check(['editor', 'administrator'])" @click="activation(data)"><i class="fas" :class="{ 'fa-lock': data.item.active === 1, 'fa-unlock': data.item.active === 0 }"></i></button>
                    <button class="btn btn-danger btn-sm" :disabled="! $auth.check(['authorizer', 'editor', 'administrator'])" @click="deleteRow(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>
        </template>

        <b-modal title="Beschreibung" ok-only id="access-description-modal">
            <p>{{ this.modalDescription }}</p>
            <hr>
            <p v-if="this.modalMessage">Nachricht: {{ this.modalMessage }}</p>
        </b-modal>

        <are-you-sure-modal v-on:answered-yes="deleteAccess" v-on:answered-no="row = null"></are-you-sure-modal>

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
            this.getAccessRules();
        },
        data() {
            return {
                /**
                 * Loader
                 */
                loading: false,

                /**
                 * Search
                 */
                search: null,

                modalDescription: null,
                modalMessage: null,
                rules: [],
                filter: null,
                fields: [
                    {
                        key: 'client_full',
                        label: 'Client',
                    },
                    {
                        key: 'sender_full',
                        label: 'Absender',
                    },
                    {
                        key: 'action_formatted',
                        label: 'Aktion',
                    },
                    {
                        key: 'created_at',
                        label: 'Erstellt am',
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
            }
        },
        methods: {
            rowClass(item, type) {
                if (!item) {
                    return;
                }

                if (item.active === 0) {
                    return 'table-secondary'
                }
            },
            activation(data) {
                if (data.item.active === 0) {
                    axios.post('/activation/' + data.item.id, {
                        model: 'ClientSenderAccess',
                    }).then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getAccessRules();
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
                        this.loading = false;
                    });
                } else {
                    axios.patch('/activation/' + data.item.id, {
                        model: 'ClientSenderAccess',
                    }).then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getAccessRules();
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
                        this.loading = false;
                    });
                }
            },
            deleteRow(data) {
                this.row = data.item;
                this.$bvModal.show('are-you-sure-modal');
            },
            showModal(row) {
                this.modalDescription = row.description;

                this.modalMessage = row.message;

                this.$bvModal.show('access-description-modal');
            },
            getAccessRules() {
                this.loading = true;
                axios.get('/access', {
                    params: {
                        search: this.search,
                    }
                }).then((response) => {
                    this.rules = response.data.data;
                    this.loading = false;
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
                    this.loading = false;
                });
            },
            deleteAccess() {
                axios.delete('/access/' + this.row.id).then((response) => {
                    this.getAccessRules();

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                });
            },
            moveUp(data) {
                axios.post('/access/' + data.item.id + '/move-up').then((response) => {
                    this.getAccessRules();

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                });
            },
            moveDown(data) {
                axios.post('/access/' + data.item.id + '/move-down').then((response) => {
                    this.getAccessRules();

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });
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
