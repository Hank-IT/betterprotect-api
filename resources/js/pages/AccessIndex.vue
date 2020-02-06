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
                <template slot="action" slot-scope="data">
                    <p :class="{ 'text-success': data.value === 'ok', 'text-danger': data.value === 'reject' }">{{ data.value }}</p>
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

            <b-row v-if="totalRows > 10">
                <b-col cols="2">
                    <b-form-select v-model="perPage" :options="displayedRowsOptions" @change="getAccessRules"></b-form-select>
                </b-col>
                <b-col cols="2" offset="3">
                    <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" @change="changePage"></b-pagination>
                </b-col>
                <b-col cols="2" offset="3" v-if="rules.length">
                    <p class="mt-1">Zeige Zeile {{ from }} bis {{ to }} von {{ totalRows }} Zeilen.</p>
                </b-col>
            </b-row>
        </template>

        <b-modal title="Beschreibung" ok-only id="access-description-modal">
            <p>{{ this.modalDescription }}</p>
        </b-modal>

        <are-you-sure-modal v-on:answered-yes="deleteAccess" v-on:answered-no="row = null"></are-you-sure-modal>

        <div class="text-center" v-if="loading">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
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
                 * Pagination
                 */
                currentPage: 1,
                perPage: 10,
                totalRows: 0,
                from: 0,
                to: 0,
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

                modalDescription: null,
                rules: [],
                filter: null,
                fields: [
                    {
                        key: 'client_payload',
                        label: 'Client',
                    },
                    {
                        key: 'client_type',
                        label: 'Client Type',
                    },
                    {
                        key: 'sender_payload',
                        label: 'Sender',
                    },
                    {
                        key: 'sender_type',
                        label: 'Sender Type',
                    },
                    {
                        key: 'action',
                        label: 'Aktion',
                    },
                    {
                        key: 'created_at',
                        label: 'Erstellt am',
                    },
                    {
                        key: 'app_actions',
                        label: ''
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

                this.$bvModal.show('access-description-modal');
            },
            changePage(data) {
                this.currentPage = data;
                this.getAccessRules();
            },
            getAccessRules() {
                this.loading = true;
                axios.get('/access', {
                    params: {
                        currentPage: this.currentPage,
                        perPage: this.perPage,
                        search: this.search,
                    }
                }).then((response) => {
                    this.rules = response.data.data.data;
                    this.totalRows = response.data.data.total;
                    this.from = response.data.data.from;
                    this.to = response.data.data.to;
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
