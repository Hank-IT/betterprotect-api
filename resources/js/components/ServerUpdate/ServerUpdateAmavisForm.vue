<template>
    <b-form>
        <b-form-checkbox v-model="form.amavis_feature_enabled" :value="1" :unchecked-value="0">{{ translate('features.amavis.name') }}</b-form-checkbox>

        <template v-if="form.amavis_feature_enabled">
            <b-form-group :label="translate('misc.database.host') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_host }" type="text" v-model="form.amavis_db_host" :placeholder="translate('misc.database.host')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_host" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('misc.database.name') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_name }" type="text" v-model="form.amavis_db_name" :placeholder="translate('misc.database.name')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_name" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('misc.database.user') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_user }" type="text" v-model="form.amavis_db_user" :placeholder="translate('misc.database.user')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_user" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('misc.database.password') + ' *'">
                <b-input-group>
                    <b-form-input :class="{ 'is-invalid': errors.amavis_db_password }" type="password" v-model="form.amavis_db_password" :placeholder="translate('misc.database.password')"></b-form-input>

                    <b-input-group-append>
                        <b-button variant="outline-secondary" @click="setPasswordEmpty">{{ translate('misc.clear') }}</b-button>
                    </b-input-group-append>
                </b-input-group>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_password" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>

                <ul class="text-muted mb-0 mt-1 pl-3">
                    <li>{{ translate('misc.password-field-explanation.security') }}</li>
                    <li>{{ translate('misc.password-field-explanation.new') }}</li>
                    <li>{{ translate('misc.password-field-explanation.empty') }}</li>
                    <li>{{ translate('misc.password-field-explanation.clear') }}</li>
                </ul>
            </b-form-group>

            <b-form-group :label="translate('misc.database.port') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_port }" type="text" v-model="form.amavis_db_port" :placeholder="translate('misc.database.port')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_port" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>
        </template>

        <b-button variant="primary" type="submit" @click="submit">{{ translate('misc.save_close') }}</b-button>
    </b-form>
</template>

<script>
    export default {
        props: ['server'],
        data() {
            return {
                form: {},
                errors: {},
            }
        },
        created() {
            axios.get('/server/' + this.server + '/amavis').then((response) => {
                this.form = response.data.data;
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
        methods: {
            submit() {
                axios.patch('/server/' + this.server + '/amavis', this.form).then((response) => {
                    this.errors = {};
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$emit('edit-server-finished');
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
            setPasswordEmpty() {
                this.form.amavis_db_password = null;
            }
        }
    }
</script>
