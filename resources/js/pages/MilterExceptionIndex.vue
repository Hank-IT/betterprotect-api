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
            <b-table hover :items="milterExceptions" :fields="fields" v-if="milterExceptions.length" :tbody-tr-class="rowClass">
                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-secondary btn-sm" :disabled="! $auth.check(['editor', 'administrator'])" @click="moveUp(data)"><i class="fas fa-chevron-up"></i></button>
                    <button class="btn btn-secondary btn-sm" :disabled="! $auth.check(['editor', 'administrator'])" @click="moveDown(data)"><i class="fas fa-chevron-down"></i></button>
                    <button class="btn btn-warning btn-sm" :disabled="! $auth.check(['editor', 'administrator'])" @click="activation(data)"><i class="fas" :class="{ 'fa-lock': data.item.active === 1, 'fa-unlock': data.item.active === 0 }"></i></button>
                    <button :disabled="! $auth.check(['editor', 'administrator'])" class="btn btn-danger btn-sm" @click="deleteRow(data)"><i class="fas fa-trash-alt"></i></button>
                </template>

                <template v-slot:cell(client)="data">
                    {{ data.item.client_payload }} ({{ data.item.client_type }})
                </template>

                <template v-slot:cell(milters)="data">
                    <span v-if="milterList(data).length">
                        {{ milterList(data).join(', ') }}
                    </span>
                    <span v-else>
                        <span class="text-danger font-italic">{{ translate('misc.disabled') }}</span>
                    </span>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">{{ translate('misc.no-data-available') }}</h4>
            </b-alert>
        </div>

        <div class="text-center" v-if="milterExceptionsLoading">
            <div class="spinner-border spinner-3x3" role="status">
                <span class="sr-only">{{ translate('misc.loading') }}...</span>
            </div>
        </div>

        <are-you-sure-modal v-on:answered-yes="deleteMilterException" v-on:answered-no="row = null"></are-you-sure-modal>

        <b-modal id="milter-store-exception-modal" ref="milterStoreExceptionModal" size="lg" :title="translate('features.policy.milter.title')" @ok="handleOk" @shown="modalShown">
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
                    <b-form-input :class="{ 'is-invalid': errors.client_payload }" ref="client_payload" type="text" v-model="form.client_payload" :placeholder="translate('validation.attributes.client_payload')"></b-form-input>

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
                    <b-form-textarea :class="{ 'is-invalid': errors.description }" type="text" v-model="form.description" rows="4" :placeholder="translate('validation.attributes.description')"></b-form-textarea>

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
                        key: 'client',
                        label: this.translate('validation.attributes.client_payload')
                    },
                    {
                        key: 'milters',
                        label: this.translate('validation.attributes.milter_id'),
                    },
                    {
                        key: 'description',
                        label: this.translate('validation.attributes.description'),
                    },
                    {
                        key: 'app_actions',
                        label: this.translate('misc.options'),
                    }
                ],

                clientTypeOptions: [
                    { value: null, text: this.translate('misc.choose_entry') },
                    { value: 'client_ipv4', text: this.translate('features.policy.access.client_types.client_ipv4') },
                    { value: 'client_ipv6', text: this.translate('features.policy.access.client_types.client_ipv6') },
                    { value: 'client_ipv4_net', text: this.translate('features.policy.access.client_types.client_ipv4_net') },
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
                        model: 'MilterException',
                    }).then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getMilterExceptions();
                    }).catch((error) => {
                        let title = error.response
                            ? error.response.data.message
                            : this.translate('misc.errors.unknown');

                        this.$notify({
                            title: title,
                            type: 'error'
                        });
                    });
                } else {
                    axios.patch('/activation/' + data.item.id, {
                        model: 'MilterException',
                    }).then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getMilterExceptions();
                    }).catch((error) => {
                        let title = error.response
                            ? error.response.data.message
                            : this.translate('misc.errors.unknown');

                        this.$notify({
                            title: title,
                            type: 'error'
                        });
                    });
                }
            },
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
                    let title = error.response
                        ? error.response.data.message
                        : this.translate('misc.errors.unknown');

                    this.$notify({
                        title: title,
                        type: 'error'
                    });

                    this.milterExceptionsLoading = false;
                });
            },
            getMilters() {
                axios.get('/milter').then((response) => {
                    this.milters = response.data.map(function(item) {
                        return { value: item.id, text: item.name };
                    });
                }).catch((error) => {
                    let title = error.response
                        ? error.response.data.message
                        : this.translate('misc.errors.unknown');

                    this.$notify({
                        title: title,
                        type: 'error'
                    });
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
                        let title = error.response
                            ? error.response.data.message
                            : this.translate('misc.errors.unknown');

                        this.$notify({
                            title: title,
                            type: 'error'
                        });
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
            },
            moveUp(data) {
                axios.post('/milter/exception/' + data.item.id + '/move-up').then((response) => {
                    this.getMilterExceptions();

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
                axios.post('/milter/exception/' + data.item.id + '/move-down').then((response) => {
                    this.getMilterExceptions();

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
