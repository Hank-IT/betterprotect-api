<template>
    <div class="server-wizard.server">
        <b-form-checkbox v-model="ssh_feature_enabled" :value="true" :unchecked-value="false">{ translate('features.console.name') }}</b-form-checkbox>

        <template v-if="ssh_feature_enabled">
            <b-form>
                <b-form-group :label="translate('validation.attributes.user') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.ssh_user, 'is-valid': isValid('ssh_user') }" type="text" ref="user" v-model="form.ssh_user" :placeholder="translate('validation.attributes.user')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.ssh_user" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.ssh_public_key') + ' *'">
                    <b-form-textarea :class="{ 'is-invalid': errors.ssh_public_key, 'is-valid': isValid('ssh_public_key') }" type="text" v-model="form.ssh_public_key" rows="4" :placeholder="translate('validation.attributes.ssh_public_key')" :disabled="submitted"></b-form-textarea>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.ssh_public_key" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.ssh_private_key') + ' *'">
                    <b-form-textarea :class="{ 'is-invalid': errors.ssh_private_key, 'is-valid': isValid('ssh_private_key') }" type="text" v-model="form.ssh_private_key" rows="4" :placeholder="translate('validation.attributes.ssh_private_key')" :disabled="submitted"></b-form-textarea>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.ssh_private_key" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>

                    <p class="text-muted mb-0">{{ translate('misc.password-field-explanation.security') }}</p>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.ssh_command_sudo') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.ssh_command_sudo, 'is-valid': isValid('ssh_command_sudo') }" type="text" v-model="form.ssh_command_sudo" :placeholder="translate('validation.attributes.ssh_command_sudo')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.ssh_command_sudo" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.ssh_command_postqueue') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.ssh_command_postqueue, 'is-valid': isValid('ssh_command_postqueue') }" type="text" v-model="form.ssh_command_postqueue" :placeholder="translate('validation.attributes.ssh_command_postqueue')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.ssh_command_postqueue" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('validation.attributes.ssh_command_postsuper') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.ssh_command_postsuper, 'is-valid': isValid('ssh_command_postsuper') }" type="text" v-model="form.ssh_command_postsuper" :placeholder="translate('validation.attributes.ssh_command_postsuper')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.ssh_command_postsuper" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-form>
        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: {},
                form: {},
                submitted: false,
                ssh_feature_enabled: true,
            }
        },
        props: ['bus', 'server'],
        created() {
            this.bus.$on('server-wizard-submit-console', this.submit);
        },
        methods: {
            submit(props) {
                // Prevent second submit
                if (this.submitted || ! this.ssh_feature_enabled) {
                    props.nextTab();

                    return;
                }

                axios.post('/server-wizard/' + this.server.id + '/console', this.form).then((response) => {
                    this.errors = {};
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.submitted = true;
                    props.nextTab();
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
                    } else {
                        this.$notify({
                            title: this.translate('misc.errors.unknown'),
                            type: 'error'
                        });
                    }
                });
            },
            isValid(index) {
                if (this.submitted) {
                    if (Object.keys(this.errors).length === 0) {
                        return true;
                    }

                    return this.errors[index];
                }

                return false;
            }
        },
    }
</script>
