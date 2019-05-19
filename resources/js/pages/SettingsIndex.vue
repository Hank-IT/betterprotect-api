<template>
    <div class="settings.index">
        <b-form>
            <b-row>
                <b-col md="2">
                    <label class="mt-1" for="ldap-auth">LDAP Authentifizierung</label>
                </b-col>

                <b-col md="3">
                    <b-form-select class="mb-2 mr-sm-2 mb-sm-0" v-model="ldapAuthForm.ldap_directory" :options="ldapDirectories" :value-field="'id'" :html-field="'connection'" id="ldap-auth">
                        <option slot="first" :value="null">Keine LDAP Authentifizierung</option>
                    </b-form-select>
                </b-col>

                <b-col md="3">
                    <b-button size="sm" class="mt-1" variant="primary" @click="handleLdapStoreSave">Speichern</b-button>
                </b-col>
            </b-row>
        </b-form>

        <b-form class="mt-2">
            <b-row>
                <b-col md="2">
                    <label class="mt-1" for="ldap-login-fallback">Anmeldefallback</label>
                </b-col>

                <b-col md="3">
                    <b-form-checkbox class="mt-2" id="ldap-login-fallback" v-model="login_fallback" switch></b-form-checkbox>
                </b-col>

                <b-col md="3">
                    <b-button size="sm" class="mt-1" variant="primary" @click="storeAuthFallback">Speichern</b-button>
                </b-col>
            </b-row>
        </b-form>

        <b-modal id="auth-ldap-modal" ref="authLdapModal" size="lg" title="LDAP" @ok="handleOk" @shown="modalShown">
            <b-form>
                <b-form-group label="Benutzer">
                    <b-form-input :class="{ 'is-invalid': errors.username }" type="text" ref="username" v-model="ldapAuthForm.username" placeholder="Benutzer"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.username" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Passwort">
                    <b-form-input :class="{ 'is-invalid': errors.password }" type="password" ref="password" v-model="ldapAuthForm.password" placeholder="Passwort"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.password" v-text="error"></li>
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
            this.getLdapDirectories();

            this.getActiveLdap();

            this.getActiveAuthFallback();
        },
        data() {
            return {
                ldapDirectories: [],
                ldapAuthForm: {
                    ldap_directory: null,
                },
                errors: [],
                login_fallback: false,
            }
        },
        methods: {
            modalShown() {
                this.$refs.username.focus();

                this.errors = [];
            },
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                this.storeLdapDirectory();
            },
            getActiveLdap() {
                axios.get('/settings/auth/ldap').then((response) => {
                    this.ldapAuthForm.ldap_directory = response.data.data;
                }).catch(function (error) {
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
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }
                });
            },
            getLdapDirectories() {
                axios.get('/ldap').then((response) => {
                    this.ldapDirectories = response.data.data;
                }).catch(function (error) {
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
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }
                });
            },
            storeLdapDirectory() {
                axios.post('/settings/auth/ldap', this.ldapAuthForm).then((response) => {
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.authLdapModal.hide();
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
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }

                    this.ldapAuthForm.username = null;
                    this.ldapAuthForm.password = null;
                });

                this.errors = [];
            },
            handleLdapStoreSave() {
                if (this.ldapAuthForm.ldap_directory === null) {
                    this.storeLdapDirectory();
                } else {
                    this.$refs.authLdapModal.show();
                }
            },
            storeAuthFallback() {
                axios.post('/settings/auth/fallback', { login_fallback: this.login_fallback }).then((response) => {
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.authLdapModal.hide();
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
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }
                });
            },
            getActiveAuthFallback() {
                axios.get('/settings/auth/fallback').then((response) => {
                    this.login_fallback = response.data.data;
                }).catch(function (error) {
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
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }
                });
            }
        }
    }
</script>