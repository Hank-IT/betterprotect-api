<template>
    <div class="milter.exception.index">
        <b-row class="mb-2">
            <b-col md="3">
                <b-button-group>
                    <button :disabled="! $auth.check(['editor', 'administrator'])" type="button" class="btn btn-primary" v-b-modal.milter-store-exception-modal><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="getMilterExceptions"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>
        </b-row>

        <div v-if="!milterExceptionsLoading">
            <b-table hover :items="milterExceptions" :fields="fields" v-if="milterExceptions.length">
                <template v-slot:cell(app_actions)="data">
                    <button :disabled="! $auth.check(['editor', 'administrator'])" class="btn btn-danger btn-sm" @click="deleteRow(data)"><i class="fas fa-trash-alt"></i></button>
                </template>

                <template v-slot:cell(milters)="data">
                    <span v-if="milterList(data).length">
                        {{ milterList(data).join(', ') }}
                    </span>
                    <span v-else>
                        <span class="text-danger font-italic">Deaktiviert</span>
                    </span>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>
        </div>

        <div class="text-center" v-if="milterExceptionsLoading">
            <div class="spinner-border spinner-3x3" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>

        <are-you-sure-modal v-on:answered-yes="deleteMilterException" v-on:answered-no="row = null"></are-you-sure-modal>

        <b-modal id="milter-store-exception-modal" ref="milterStoreExceptionModal" size="lg" title="Milter Ausnahme hinzufügen" @ok="handleOk" @shown="modalShown">
            <b-form>
                <b-form-group label="Client Typ *">
                    <b-form-select :class="{ 'is-invalid': errors.client_type }" v-model="form.client_type" :options="clientTypeOptions"></b-form-select>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.client_type" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Client *">
                    <b-form-input :class="{ 'is-invalid': errors.client_payload }" ref="client_payload" type="text" v-model="form.client_payload" placeholder="Client"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.client_payload" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Milter *">
                    <b-form-select :class="{ 'is-invalid': errors.milter_id }" v-model="form.milter_id" :options="milters" ref="milter_id" multiple :select-size="4"></b-form-select>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.milter_id" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Beschreibung">
                    <b-form-textarea :class="{ 'is-invalid': errors.description }" type="text" v-model="form.description" rows="4" placeholder="Beschreibung"></b-form-textarea>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.description" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getMilterExceptions();
            this.getMilters();
        },
        data() {
            return {
                milterExceptions: [],
                milterExceptionsLoading: false,
                fields: [
                    {
                        key: 'client_type',
                        label: 'Typ',
                    },
                    {
                        key: 'client_payload',
                        label: 'Client',
                    },
                    {
                        key: 'milters',
                        label: 'Milter',
                    },
                    {
                        key: 'app_actions',
                        label: 'Optionen'
                    }
                ],

                clientTypeOptions: [
                    { value: null, text: 'Bitte Typ auswählen' },
                    { value: 'client_ipv4', text: 'Client IPv4' },
                    { value: 'client_ipv6', text: 'Client IPv6' },
                    { value: 'client_ipv4_net', text: 'Client IPv4 Netzwerk' },
                ],

                milters: [],

                /**
                 * Are you sure modal
                 */
                row: null,

                /**
                 * Modal
                 */
                form: {
                    client_type: null,
                    milter_id: [],
                },
                errors: [],
            }
        },
        methods: {
            milterList(data) {
                return data.item.milters.map(function(value,index) {
                    return value.name;
                });
            },
            getMilterExceptions() {
                this.milterExceptionsLoading = true;

                axios.get('/milter/exception').then((response) => {
                    this.milterExceptions = response.data;
                    this.milterExceptionsLoading = false;
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
                    this.milterExceptionsLoading = false;
                });
            },
            getMilters() {
                axios.get('/milter').then((response) => {
                    this.milters = response.data.map(function(item) {
                        return { value: item.id, text: item.name };
                    });
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
                });
            },
            deleteRow(data) {
                this.row = data.item;
                this.$bvModal.show('are-you-sure-modal');
            },
            deleteMilterException() {
                axios.delete('/milter/exception/' + this.row.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getMilterExceptions();
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
                });
            },
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                this.storeMilterException();
            },
            modalShown() {
                this.form.client_payload = null;
                this.form.client_type = null;
                this.form.milter_id = [];

                this.$refs.client_payload.focus();

                this.errors = [];
            },
            storeMilterException() {
                axios.post('/milter/exception', this.form).then((response) => {
                    this.getMilterExceptions();

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.milterStoreExceptionModal.hide();
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
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