<template>
    <div class="milter.index">
        <b-row class="mb-2">
            <b-col md="3">
                <b-button-group>
                    <button :disabled="! $auth.check(['editor', 'administrator'])" type="button" class="btn btn-primary" v-b-modal.milter-store-modal><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="getMilters"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>
        </b-row>

        <div v-if="!miltersLoading">
            <b-table hover :items="milters" :fields="fields" v-if="milters.length">
                <template v-slot:cell(app_actions)="data">
                    <button :disabled="! $auth.check(['editor', 'administrator'])" class="btn btn-danger btn-sm" @click="deleteRow(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>
        </div>

        <div class="text-center" v-if="miltersLoading">
            <div class="spinner-border spinner-3x3" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>

        <are-you-sure-modal v-on:answered-yes="deleteMilter" v-on:answered-no="row = null"></are-you-sure-modal>

        <b-modal id="milter-store-modal" ref="milterStoreModal" size="lg" title="Milter hinzufügen" @ok="handleOk" @shown="modalShown">
            <b-form>
                <b-form-group label="Name *">
                    <b-form-input :class="{ 'is-invalid': errors.name }" ref="payload" type="text" v-model="form.name" placeholder="Name"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.name" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Definition *">
                    <b-form-input :class="{ 'is-invalid': errors.definition }" ref="definition" type="text" v-model="form.definition" placeholder="Definition"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.payload" v-text="error"></li>
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
            this.getMilters();
        },
        data() {
            return {
                milters: [],
                miltersLoading: false,

                fields: [
                    {
                        key: 'name',
                        label: 'Name',
                    },
                    {
                        key: 'definition',
                        label: 'Definition',
                    },
                    {
                        key: 'description',
                        label: 'Beschreibung',
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

                /**
                 * Modal
                 */
                form: {},
                errors: [],
            }
        },
        methods: {
            getMilters() {
                this.miltersLoading = true;

                axios.get('/milter').then((response) => {
                    this.milters = response.data;
                    this.miltersLoading = false;
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
                    this.miltersLoading = false;
                });
            },
            deleteRow(data) {
                this.row = data.item;
                this.$bvModal.show('are-you-sure-modal');
            },
            deleteMilter() {
                axios.delete('/milter/' + this.row.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getMilters();
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

                this.storeMilter();
            },
            modalShown() {
                this.form.payload = null;

                this.$refs.payload.focus();

                this.errors = [];
            },
            storeMilter() {
                axios.post('/milter', this.form).then((response) => {
                    this.getMilters();

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.milterStoreModal.hide();
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