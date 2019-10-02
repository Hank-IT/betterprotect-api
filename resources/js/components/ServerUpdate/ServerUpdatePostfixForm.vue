<template>
    <b-form>
        <b-form-group label="Datenbankhost *">
            <b-form-input :class="{ 'is-invalid': errors.postfix_db_host }" type="text" v-model="form.postfix_db_host" placeholder="Datenbankhost"></b-form-input>

            <b-form-invalid-feedback>
                <ul class="form-group-validation-message-list">
                    <li v-for="error in errors.postfix_db_host" v-text="error"></li>
                </ul>
            </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Datenbankname *">
            <b-form-input :class="{ 'is-invalid': errors.postfix_db_name }" type="text" v-model="form.postfix_db_name" placeholder="Datenbankname"></b-form-input>

            <b-form-invalid-feedback>
                <ul class="form-group-validation-message-list">
                    <li v-for="error in errors.postfix_db_name" v-text="error"></li>
                </ul>
            </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Datenbankbenutzer">
            <b-form-input :class="{ 'is-invalid': errors.postfix_db_user }" type="text" v-model="form.postfix_db_user" placeholder="Datenbankbenutzer"></b-form-input>

            <b-form-invalid-feedback>
                <ul class="form-group-validation-message-list">
                    <li v-for="error in errors.postfix_db_user" v-text="error"></li>
                </ul>
            </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Datenbankpasswort">
            <b-input-group>
                <b-form-input :class="{ 'is-invalid': errors.postfix_db_password }" type="password" v-model="form.postfix_db_password" placeholder="Datenbankpasswort"></b-form-input>

                <b-input-group-append>
                    <b-button variant="outline-secondary" @click="setPasswordEmpty">Leeren</b-button>
                </b-input-group-append>
            </b-input-group>

            <b-form-invalid-feedback>
                <ul class="form-group-validation-message-list">
                    <li v-for="error in errors.postfix_db_password" v-text="error"></li>
                </ul>
            </b-form-invalid-feedback>

            <ul class="text-muted mb-0 mt-1 pl-3">
                <li>Das Passwort wird aus Sicherheitsgründen nicht angezeigt.</li>
                <li>Geben Sie ein neues Passwort ein, um das Passwort zu ändern.</li>
                <li>Lassen Sie das Feld Leer, um das Passwort beizubehalten.</li>
                <li>Klicken Sie Leeren, um das Passwort zu entfernen.</li>
            </ul>
        </b-form-group>

        <b-form-group label="Datenbankport *">
            <b-form-input :class="{ 'is-invalid': errors.postfix_db_port }" type="text" v-model="form.postfix_db_port" placeholder="Datenbankport"></b-form-input>

            <b-form-invalid-feedback>
                <ul class="form-group-validation-message-list">
                    <li v-for="error in errors.postfix_db_port" v-text="error"></li>
                </ul>
            </b-form-invalid-feedback>
        </b-form-group>

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
            axios.get('/server/' + this.server + '/postfix').then((response) => {
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
                axios.patch('/server/' + this.server + '/postfix', this.form).then((response) => {
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
                this.form.postfix_db_password = null;
            }
        }
    }
</script>
