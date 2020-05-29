<template>
    <b-modal id="user-store-update-modal" ref="userStoreUpdateModal" size="lg" title="Benutzer hinzufügen" @ok="handleOk" @shown="modalShown">
        <b-form>
            <b-form-group :label="translate('validation.attributes.username') + ' *'">
                <b-form-input :class="{ 'is-invalid': errors.username }" type="text" ref="username" v-model="form.username" :placeholder="translate('validation.attributes.username')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.username" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.email')">
                <b-form-input :class="{ 'is-invalid': errors.email }" type="text" ref="email" v-model="form.email" :placeholder="translate('validation.attributes.email')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.email" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Rolle">
                <b-form-select v-model="form.role" :options="roles"></b-form-select>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.password') + ' *'" v-if="user == null || (user && user.objectguid === null)">
                <b-form-input :class="{ 'is-invalid': errors.password }" type="password" ref="email" v-model="form.password" :placeholder="translate('validation.attributes.password')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.password" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group :label="translate('validation.attributes.password_confirmation') + ' *'" v-if="user == null || (user && user.objectguid === null)">
                <b-form-input :class="{ 'is-invalid': errors.password_confirmation }" type="password" ref="password_confirmation" v-model="form.password_confirmation" :placeholder="translate('validation.attributes.password_confirmation')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.password_confirmation" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>
        </b-form>
    </b-modal>
</template>

<script>
    export default {
        props: [
            'user',
        ],
        data() {
            return {
                form: {},
                errors: [],
                selectedRole: null,
                roles: [
                    { value: 'readonly', 'text': this.translate('misc.roles.readonly') },
                    { value: 'authorizer', text: this.translate('misc.roles.authorizer') },
                    { value: 'editor', text: this.translate('misc.roles.editor') },
                    { value: 'administrator', text: this.translate('misc.roles.administrator') },
                ]
            }
        },
        methods: {
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                if (this.user == null) {
                    this.store();
                } else {
                    this.update();
                }
            },
            modalShown() {
                if (this.user == null) {
                    this.form = {
                        role: 'readonly',
                    };
                } else {
                    axios.get('/user/' + this.user.id).then((response) => {
                        this.form = response.data;
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
                }

                this.$refs.username.focus();

                this.errors = [];
            },
            store() {
                axios.post('/user', this.form).then((response) => {
                    this.$emit('user-stored', response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.userStoreUpdateModal.hide();
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

                this.errors = [];
            },
            update() {
                axios.patch('/user/' + this.user.id, this.form).then((response) => {
                    this.$emit('user-updated', response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.userStoreUpdateModal.hide();
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

                this.errors = [];
            }
        }
    }
</script>
