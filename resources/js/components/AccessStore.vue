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
                <b-form-group :label="translate('features.policy.access.any_client')">
                    <b-form-checkbox type="checkbox" :placeholder="translate('features.policy.access.any_client')" v-model="clientVisible" value="false" unchecked-value="true" @change="allClientsCheckboxChanged"></b-form-checkbox>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.client_type') + ' *'" v-if="clientVisible === 'true'">
                    <b-form-select :class="{ 'is-invalid': errors.client_type }" v-model="form.client_type" :options="clientTypeOptions"></b-form-select>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.client_type" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.client_payload') + ' *'" v-if="clientVisible === 'true'">
                    <b-form-input :class="{ 'is-invalid': errors.client_payload }" ref="client_payload" type="text" v-model="form.client_payload" :placeholder="translate('misc.entry')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.client_payload" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <hr>

                <b-form-group :label="translate('features.policy.access.any_sender')">
                    <b-form-checkbox type="checkbox" :placeholder="translate('features.policy.access.any_sender')" v-model="senderVisible" value="false" unchecked-value="true" @change="allSendersCheckboxChanged"></b-form-checkbox>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.sender_type') + ' *'" v-if="senderVisible === 'true'">
                    <b-form-select :class="{ 'is-invalid': errors.sender_type }" v-model="form.sender_type" :options="senderTypeOptions"></b-form-select>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.sender_type" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.sender_payload') + ' *'" v-if="senderVisible === 'true'">
                    <b-form-input :class="{ 'is-invalid': errors.sender_payload }" type="text" v-model="form.sender_payload" :placeholder="translate('misc.entry')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.sender_payload" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <hr>

                <b-form-group :label="translate('validation.attributes.action') + ' *'">
                    <b-form-select :class="{ 'is-invalid': errors.action }" v-model="form.action" :options="actionOptions"></b-form-select>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.action" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.message') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.message }" ref="message" type="text" v-model="form.message" :placeholder="translate('validation.attributes.message')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.message" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <hr>

                <b-form-group :label="translate('validation.attributes.description')">
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
        data() {
            return {
                clientTypeOptions: [
                    { value: null, text: this.translate('misc.choose_entry') },
                    { value: 'client_hostname', text: this.translate('features.policy.access.client_types.client_hostname') },
                    { value: 'client_reverse_hostname', text: this.translate('features.policy.access.client_types.client_reverse_hostname') },
                    { value: 'client_ipv4', text: this.translate('features.policy.access.client_types.client_ipv4') },
                    { value: 'client_ipv6', text: this.translate('features.policy.access.client_types.client_ipv6') },
                    { value: 'client_ipv4_net', text: this.translate('features.policy.access.client_types.client_ipv4_net') },
                ],
                senderTypeOptions: [
                    { value: null, text:this.translate('misc.choose_entry') },
                    { value: 'mail_from_address', text: this.translate('features.policy.access.sender_types.mail_from_address') },
                    { value: 'mail_from_domain', text: this.translate('features.policy.access.sender_types.mail_from_domain') },
                    { value: 'mail_from_localpart', text: this.translate('features.policy.access.sender_types.mail_from_localpart') },
                ],
                actionOptions: [
                    { value: null, text: this.translate('misc.choose_entry') },
                    { value: 'ok', text: this.translate('postfix.mail.action.ok') },
                    { value: 'reject', text: this.translate('postfix.mail.action.reject') },
                ],
                form: {
                    client_type: null,
                    client_payload: null,
                    sender_type: null,
                    sender_payload: null,
                    action: null
                },
                errors: [],
                clientVisible: 'true',
                senderVisible: 'true',
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
                    client_type: null,
                    client_payload: null,
                    sender_type: null,
                    sender_payload: null,
                    action: null,
                };

                this.clientVisible = 'true';
                this.senderVisible = 'true';

                this.$refs.client_payload.focus();

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
            },
            allClientsCheckboxChanged() {
                if (this.clientVisible === 'true') {
                    this.form.client_type = '*';
                    this.form.client_payload = '*';
                } else {
                    this.form.client_type = null;
                    this.form.client_payload  = null;
                }
            },
            allSendersCheckboxChanged() {
                if (this.senderVisible === 'true') {
                    this.form.sender_type = '*';
                    this.form.sender_payload = '*';
                } else {
                    this.form.sender_type = null;
                    this.form.sender_payload  = null;
                }
            }
        }
    }
</script>
