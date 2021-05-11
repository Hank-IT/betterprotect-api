<template>
    <b-modal id="ldap-directory-update-store-modal" ref="ldapDirectoryUpdateStoreModal" size="lg" title="LDAP Directory hinzufügen" @ok="handleOk" @shown="modalShown">
        <b-form>
            <b-form-group :label="translate('validation.attributes.connection') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.connection }" type="text" ref="connection" v-model="form.connection" :placeholder="translate('validation.attributes.connection')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.connection" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.server') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.servers }" type="text" ref="servers" v-model="form.servers" :placeholder="translate('validation.attributes.server')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.servers" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.port') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.port }" type="text" ref="port" v-model="form.port" :placeholder="translate('validation.attributes.port')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.port" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.timeout') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.timeout }" type="text" ref="timeout" v-model="form.timeout" :placeholder="translate('validation.attributes.timeout')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.timeout" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.base_dn') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.base_dn }" type="text" ref="base_dn" v-model="form.base_dn" :placeholder="translate('validation.attributes.base_dn')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.base_dn" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.bind_user') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.bind_user }" type="text" ref="bind_user" v-model="form.bind_user" :placeholder="translate('validation.attributes.bind_user')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.bind_user" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.bind_password') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.bind_password }" type="password" ref="bind_password" v-model="form.bind_password" :placeholder="translate('validation.attributes.bind_password')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.bind_password" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.use_ssl')">
                <b-form-checkbox :class="{ 'is-invalid': errors.use_ssl }" type="checkbox" ref="use_ssl" v-model="form.use_ssl" :placeholder="translate('validation.attributes.use_ssl')" value="1" unchecked-value="0"></b-form-checkbox>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.use_ssl" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.use_tls')">
                <b-form-checkbox :class="{ 'is-invalid': errors.use_tls }" type="checkbox" ref="use_tls" v-model="form.use_tls" :placeholder="translate('validation.attributes.use_tls')" value="1" unchecked-value="0"></b-form-checkbox>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.use_tls" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.ignored_domains')">
                <b-form-input :class="{ 'is-invalid': errors.ignored_domains }" type="text" ref="ignored_domains" v-model="form.ignored_domains" :placeholder="translate('validation.attributes.ignored_domains')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ignored_domains" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-button v-b-toggle.authentication-settings variant="primary"><i class="fas fa-arrows-alt-v"></i> {{ translate('misc.more-settings') }}</b-button>

            <b-collapse id="authentication-settings" class="mt-2">
                <b-alert show>{{ translate('features.ldap.auth') }}</b-alert>

                <b-form-group :label="translate('validation.attributes.group_dn')">
                    <b-form-input :class="{ 'is-invalid': errors.group_dn }" type="text" ref="group_dn" v-model="form.group_dn" :placeholder="translate('validation.attributes.group_dn')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.group_dn" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.account_prefix')">
                    <b-form-input :class="{ 'is-invalid': errors.account_prefix }" type="text" ref="account_prefix" v-model="form.account_prefix" :placeholder="translate('validation.attributes.account_prefix')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.account_prefix" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.account_suffix')">
                    <b-form-input :class="{ 'is-invalid': errors.account_suffix }" type="text" ref="account_suffix" v-model="form.account_suffix" :placeholder="translate('validation.attributes.account_suffix')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.account_suffix" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.discover_attr')">
                    <b-form-input :class="{ 'is-invalid': errors.discover_attr }" type="text" ref="discover_attr" v-model="form.discover_attr" :placeholder="translate('validation.attributes.discover_attr')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.discover_attr" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.authenticate_attr')">
                    <b-form-input :class="{ 'is-invalid': errors.authenticate_attr }" type="text" ref="authenticate_attr" v-model="form.authenticate_attr" :placeholder="translate('validation.attributes.authenticate_attr')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.authenticate_attr" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.sso_auth_attr')">
                    <b-form-input :class="{ 'is-invalid': errors.sso_auth_attr }" type="text" ref="sso_auth_attr" v-model="form.sso_auth_attr" :placeholder="translate('validation.attributes.sso_auth_attr')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.sso_auth_attr" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.password_sync')">
                    <b-form-checkbox :class="{ 'is-invalid': errors.password_sync }" type="checkbox" ref="password_sync" v-model="form.password_sync" :placeholder="translate('validation.attributes.password_sync')" value="1" unchecked-value="0"></b-form-checkbox>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.password_sync" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-collapse>
        </b-form>
    </b-modal>
</template>

<script>
    export default {
        props: [
            'ldapDirectory',
        ],
        data() {
            return {
                form: {},
                errors: [],
            }
        },
        methods: {
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                if (this.ldapDirectory == null) {
                    this.store();
                } else {
                    this.update();
                }
            },
            modalShown() {
                if (this.ldapDirectory == null) {
                    this.form = {};
                } else {
                    axios.get('/ldap/' + this.ldapDirectory.id).then((response) => {
                        this.form = response.data;
                    }).catch((error) => {
                        // handle error
                        console.log(error);
                    });
                }

                this.$refs.connection.focus();

                this.errors = [];
            },
            store() {
                axios.post('/ldap', this.form).then((response) => {
                    this.$emit('ldap-stored', response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.ldapDirectoryUpdateStoreModal.hide();
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

                this.errors = [];
            },
            update() {
                axios.patch('/ldap/' + this.ldapDirectory.id, this.form).then((response) => {
                    this.$emit('ldap-updated', response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.ldapDirectoryUpdateStoreModal.hide();
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    }
                });

                this.errors = [];
            }
        }
    }
</script>
