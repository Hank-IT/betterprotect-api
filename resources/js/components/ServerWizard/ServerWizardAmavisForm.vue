<template>
    <div class="server-wizard.server">
        <b-form-checkbox v-model="amavis_feature_enabled" :value="true" :unchecked-value="false">{{ translate('features.amavis.name') }}</b-form-checkbox>

        <template v-if="amavis_feature_enabled">
            <b-form>
                <b-form-group :label="translate('misc.database.host') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.amavis_db_host, 'is-valid': isValid('amavis_db_host') }" type="text" v-model="form.amavis_db_host" :placeholder="translate('misc.database.host')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.amavis_db_host" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('misc.database.name') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.amavis_db_name, 'is-valid': isValid('amavis_db_name') }" type="text" v-model="form.amavis_db_name" :placeholder="translate('misc.database.name')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.amavis_db_name" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('misc.database.user') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.amavis_db_user, 'is-valid': isValid('amavis_db_user') }" type="text" v-model="form.amavis_db_user" :placeholder="translate('misc.database.user')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.amavis_db_user" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group :label="translate('misc.database.password') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.amavis_db_password, 'is-valid': isValid('amavis_db_password') }" type="password" v-model="form.amavis_db_password" :placeholder="translate('misc.database.password')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.amavis_db_password" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>

                    <p class="text-muted mb-0">{{ translate('misc.password-field-explanation.security') }}</p>
                </b-form-group>

                <b-form-group :label="translate('misc.database.port') + ' *'">
                    <b-form-input :class="{ 'is-invalid': errors.amavis_db_port, 'is-valid': isValid('amavis_db_port') }" type="text" v-model="form.amavis_db_port" :placeholder="translate('misc.database.port')" :disabled="submitted"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.amavis_db_port" v-text="error"></li>
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
                amavis_feature_enabled: true,
            }
        },
        props: ['bus', 'server'],
        created() {
            this.bus.$on('server-wizard-submit-amavis', this.submit);
        },
        methods: {
            submit(props) {
                // Prevent second submit
                if (this.submitted || ! this.amavis_feature_enabled) {
                    props.nextTab();

                    return;
                }

                axios.post('/server-wizard/' + this.server.id + '/amavis', this.form).then((response) => {
                    this.errors = [];
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
        }
    }
</script>
