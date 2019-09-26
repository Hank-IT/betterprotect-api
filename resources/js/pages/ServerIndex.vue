<template>
    <div class="server.index">
        <b-row class="mb-2">
            <b-col md="1">
                <b-button-group>
                    <b-btn variant="primary" v-b-modal.server-wizard ><i class="fas fa-plus"></i></b-btn>
                    <b-btn variant="secondary" @click="getAllServers"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>
        </b-row>

        <div>
            <b-modal id="server-wizard" size="xl" title="Server Wizard" >
                <form-wizard color="#007bff">
                    <h2 slot="title"></h2>

                    <tab-content title="Server" icon="fas fa-server">
                        <server-wizard-server-form ref="serverWizardServerForm"></server-wizard-server-form>
                    </tab-content>
                    <tab-content title="Postfix" icon="fas fa-envelope">
                        <server-wizard-postfix-form></server-wizard-postfix-form>
                    </tab-content>
                    <tab-content title="Konsole" icon="fas fa-terminal">
                        <server-wizard-console-form></server-wizard-console-form>
                    </tab-content>
                    <tab-content title="Logging" icon="fas fa-tasks">
                        <server-wizard-logging-form></server-wizard-logging-form>
                    </tab-content>
                    <tab-content title="Amavis" icon="fas fa-trash">
                        <server-wizard-amavis-form></server-wizard-amavis-form>
                    </tab-content>
                </form-wizard>
            </b-modal>
        </div>

        <!-- Modal Component -->
        <b-modal id="server-store-modal" ref="serverStoreModal" size="lg" title="Server hinzufügen" @ok="handleOk" @shown="modalShown" @hidden="modalHidden">
            <b-form>
                <b-form-group label="Hostname *">
                    <b-form-input :class="{ 'is-invalid': errors.hostname }" type="text" ref="hostname" v-model="serverForm.hostname" placeholder="Hostname"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.hostname" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Beschreibung">
                    <b-form-textarea :class="{ 'is-invalid': errors.description }" type="text" v-model="serverForm.description" rows="4" placeholder="Beschreibung"></b-form-textarea>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.description" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Datenbankhost *">
                    <b-form-input :class="{ 'is-invalid': errors.db_host }" type="text" v-model="serverForm.db_host" placeholder="Datenbankhost"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.db_host" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Datenbankbenutzer *">
                    <b-form-input :class="{ 'is-invalid': errors.db_user }" type="text" v-model="serverForm.db_user" placeholder="Datenbankbenutzer"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.db_user" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Datenbankname *">
                    <b-form-input :class="{ 'is-invalid': errors.db_name }" type="text" v-model="serverForm.db_name" placeholder="Datenbankname"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.db_name" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Datenbankpasswort *">
                    <b-form-input :class="{ 'is-invalid': errors.db_password }" type="password" v-model="serverForm.db_password" placeholder="Datenbankpasswort"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.db_password" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>

                    <p class="text-muted mb-0">Das Passwort wird aus Sicherheitsgründen nicht angezeigt!</p>
                </b-form-group>

                <b-form-group label="Datenbankport *">
                    <b-form-input :class="{ 'is-invalid': errors.db_port }" type="text" v-model="serverForm.db_port" placeholder="Datenbankport"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.db_port" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-form>
        </b-modal>

        <server-terminal-modal v-bind:server="serverTerminalModalServer"></server-terminal-modal>
        <server-queue-modal v-bind:server="serverQueueModalServer"></server-queue-modal>

        <server
                v-if="!loading"
                v-for="server in servers"
                v-bind:key="server.id"
                v-bind:server="server"
                v-on:server-deleted="getAllServers"
                v-on:edit-server="editServer"
                v-on:open-server-terminal-modal="openServerTerminalModal"
                v-on:open-server-queue-modal="openServerQueueModal"
        ></server>

        <b-alert show variant="warning" v-if="!servers.length && !loading">
            <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
        </b-alert>

        <div class="text-center" v-if="loading">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                servers: [],
                serverForm: {},
                serverFormUpdated: false,
                errors: [],

                /**
                 * Server Terminal Modal
                 */
                serverTerminalModalServer: null,

                /**
                 * Server Queue Modal
                 */
                serverQueueModalServer: null,

                /**
                 * Loader
                 */
                loading: false,
            }
        },
        created() {
            this.getAllServers();
        },
        methods: {
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                if (this.serverFormUpdated) {
                    this.updateServer()
                } else {
                    this.storeServer();
                }
            },
            modalShown() {
                this.$refs.hostname.focus();
                this.errors = [];
            },
            modalHidden() {
                this.serverForm = {};
                this.errors = [];
            },
            storeServer() {
                axios.post('/server', this.serverForm).then((response) => {
                    this.errors = [];

                    this.servers.push(response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.serverStoreModal.hide();
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
            updateServer() {
                axios.patch('/server/' + this.serverForm.id, this.serverForm).then((response) => {
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    let serverIndex = this.servers.findIndex(x => x.id === this.serverForm.id);

                    this.$set(this.servers, serverIndex, this.serverForm);

                    this.$refs.serverStoreModal.hide();

                    this.serverFormUpdated = false;
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
            getAllServers() {
                this.loading = true;
                axios.get('/server').then((response) => {
                    this.servers = response.data;

                    this.loading = false;
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

                    this.loading = false;
                });
            },
            editServer(id) {
                this.serverFormUpdated = true;

                axios.get('/server/' + id).then((response) => {
                    this.serverForm = response.data;

                    this.$refs.serverStoreModal.show();
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
            openServerTerminalModal(server) {
                this.serverTerminalModalServer = server;
            },
            openServerQueueModal(server) {
                this.serverQueueModalServer = server;
            }
        }
    }
</script>

<style>
    .vue-form-wizard .wizard-icon {
        height: inherit;
    }
</style>
