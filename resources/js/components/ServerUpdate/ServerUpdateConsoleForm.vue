<template>
    <b-form>
        <b-form-checkbox v-model="form.ssh_feature_enabled" :value="1" :unchecked-value="0">SSH Funktionen aktivieren</b-form-checkbox>

        <template v-if="form.ssh_feature_enabled">
            <b-form-group label="Benutzer *">
                <b-form-input :class="{ 'is-invalid': errors.ssh_user }" type="text" ref="user" v-model="form.ssh_user" placeholder="Benutzer"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ssh_user" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Public Key *">
                <b-form-textarea :class="{ 'is-invalid': errors.ssh_public_key }" type="text" v-model="form.ssh_public_key" rows="4" placeholder="Public Key"></b-form-textarea>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ssh_public_key" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Private Key *">
                <b-input-group>
                    <b-form-textarea :class="{ 'is-invalid': errors.ssh_private_key }" type="text" v-model="form.ssh_private_key" rows="4" placeholder="Private Key"></b-form-textarea>

                    <b-input-group-append>
                        <b-button variant="outline-secondary" @click="setPasswordEmpty">Leeren</b-button>
                    </b-input-group-append>
                </b-input-group>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ssh_private_key" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>

                <ul class="text-muted mb-0 mt-1 pl-3">
                    <li>Das Passwort wird aus Sicherheitsgründen nicht angezeigt.</li>
                    <li>Geben Sie ein neues Passwort ein, um das Passwort zu ändern.</li>
                    <li>Lassen Sie das Feld Leer, um das Passwort beizubehalten.</li>
                    <li>Klicken Sie Leeren, um das Passwort zu entfernen.</li>
                </ul>
            </b-form-group>

            <b-form-group label="Sudo Pfad *">
                <b-form-input :class="{ 'is-invalid': errors.ssh_command_sudo }" type="text" v-model="form.ssh_command_sudo" placeholder="Sudo Pfad"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ssh_command_sudo" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Postqueue Pfad *">
                <b-form-input :class="{ 'is-invalid': errors.ssh_command_postqueue }" type="text" v-model="form.ssh_command_postqueue" placeholder="Postqueue Pfad"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ssh_command_postqueue" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Postsuper Pfad *">
                <b-form-input :class="{ 'is-invalid': errors.ssh_command_postsuper }" type="text" v-model="form.ssh_command_postsuper" placeholder="Postsuper Pfad"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ssh_command_postsuper" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>
        </template>

        <b-button variant="primary" type="submit" @click="submit">Speichern & Schließen</b-button>
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
            axios.get('/server/' + this.server + '/console').then((response) => {
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
                        title: 'Unbekannter Fehler',
                        type: 'error'
                    });
                }
            });
        },
        methods: {
            submit() {
                axios.patch('/server/' + this.server + '/console', this.form).then((response) => {
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
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }
                });
            },
            setPasswordEmpty() {
                this.form.ssh_private_key = null;
            }
        }
    }
</script>
