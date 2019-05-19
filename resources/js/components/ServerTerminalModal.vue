<template>
    <!-- Modal Component -->
    <b-modal id="server-terminal-modal" ref="serverTerminalModal" size="lg" title="Terminal Zugriff" @shown="modalShown">
        <b-form>
            <b-form-group label="Benutzer *">
                <b-form-input :class="{ 'is-invalid': errors.user }" type="text" ref="user" v-model="serverTerminalForm.user" placeholder="Benutzer"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.user" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Public Key *">
                <b-form-textarea :class="{ 'is-invalid': errors.public_key }" type="text" v-model="serverTerminalForm.public_key" rows="4" placeholder="Public Key"></b-form-textarea>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.public_key" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Private Key *">
                <b-form-textarea :class="{ 'is-invalid': errors.private_key }" type="text" v-model="serverTerminalForm.private_key" rows="4" placeholder="Private Key"></b-form-textarea>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.private_key" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>

                <p class="text-muted mb-0">Der Schlüssel wird aus Sicherheitsgründen nicht angezeigt!</p>
            </b-form-group>

            <b-form-group label="Postqueue Pfad *">
                <b-form-input :class="{ 'is-invalid': errors.postqueue }" type="text" v-model="serverTerminalForm.postqueue" placeholder="Postqueue Pfad"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.postqueue" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Postsuper Pfad *">
                <b-form-input :class="{ 'is-invalid': errors.postsuper }" type="text" v-model="serverTerminalForm.postsuper" placeholder="Postsuper Pfad"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.postsuper" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Sudo Pfad *">
                <b-form-input :class="{ 'is-invalid': errors.sudo }" type="text" v-model="serverTerminalForm.sudo" placeholder="Sudo Pfad"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.sudo" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>
        </b-form>

        <div slot="modal-footer">
            <button class="btn btn-primary" @click="storeTerminalSettings">Testen & Speichern</button>
        </div>
    </b-modal>
</template>

<script>
    export default {
        props: ['server'],
        data() {
            return {
                serverTerminalForm: {},
                errors: {},
            }
        },
        methods: {
            storeTerminalSettings() {
                axios.post('/server/' + this.server.id + '/terminal', this.serverTerminalForm)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.$refs.serverTerminalModal.hide()
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

                    // handle error
                    console.log(error);
                });
            },
            modalShown() {
                this.errors = [];

                axios.get('/server/' + this.server.id + '/terminal')
                    .then((response) => {
                        this.serverTerminalForm = response.data.data;
                    }).catch(function (error) {
                    console.log(error);
                });

                this.$refs.user.focus();
            },
        }
    }
</script>