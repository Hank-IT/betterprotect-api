<template>
    <div class="access.store">
        <b-row class="mb-2">
            <b-col md="1">
                <b-button-group>
                    <button type="button" :disabled="! $auth.check(['authorizer', 'editor', 'administrator'])" class="btn btn-primary" v-b-modal.access-store-modal><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="$emit('reload-table')"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>
        </b-row>

        <!-- Modal Component -->
        <b-modal id="access-store-modal" ref="accessStoreModal" size="lg" title="Blacklist/Whitelist Eintrag" @ok="handleOk" @shown="modalShown">
            <b-form @submit.stop.prevent="storeAccess">
                <b-form-group label="Eintrag *">
                    <b-form-input :class="{ 'is-invalid': errors.payload }" ref="payload" type="text" v-model="form.payload" placeholder="Eintrag"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.payload" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Typ *">
                    <b-form-select :class="{ 'is-invalid': errors.type }" v-model="form.type" :options="typeOptions"></b-form-select>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.type" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Aktion *">
                    <b-form-select :class="{ 'is-invalid': errors.action }" v-model="form.action" :options="actionOptions"></b-form-select>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.action" v-text="error"></li>
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
        data() {
            return {
                typeOptions: [
                    { value: null, text: 'Bitte Typ auswählen' },
                    { value: 'client_hostname', text: 'Client Hostname' },
                    { value: 'client_ipv4', text: 'Client IPv4' },
                    { value: 'client_ipv4_net', text: 'Client IPv4 Netzwerk' },
                    { value: 'mail_from_address', text: 'Mail From Adresse' },
                    { value: 'mail_from_domain', text: 'Mail From Domäne' },
                    { value: 'mail_from_localpart', text: 'Mail From Localpart' },
                ],
                actionOptions: [
                    { value: null, text: 'Bitte Aktion auswählen' },
                    { value: 'ok', text: 'Ok' },
                    { value: 'reject', text: 'Ablehnen' },
                ],
                form: {
                    type: null,
                    action: null,
                },
                errors: [],
            }
        },
        methods: {
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                this.storeAccess();
            },
            modalShown() {
                this.form = {
                    type: null,
                    action: null,
                };

                this.$refs.payload.focus();

                this.errors = [];
            },
            storeAccess() {
                axios.post('/access', this.form)
                    .then((response) => {
                        this.$emit('access-stored', response.data.data);

                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.$refs.accessStoreModal.hide();
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
