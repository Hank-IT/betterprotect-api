<template>
    <div class="server-wizard.server">
        <b-form>
            <b-form-group label="Datenbankhost *">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_host }" type="text" v-model="form.amavis_db_host" placeholder="Datenbankhost"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_host" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Datenbankname *">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_name }" type="text" v-model="form.amavis_db_name" placeholder="Datenbankname"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_name" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Datenbankbenutzer">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_user }" type="text" v-model="form.amavis_db_user" placeholder="Datenbankbenutzer"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_user" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Datenbankpasswort">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_password }" type="password" v-model="form.amavis_db_password" placeholder="Datenbankpasswort"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_password" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>

                <p class="text-muted mb-0">Das Passwort wird aus Sicherheitsgründen nicht angezeigt!</p>
            </b-form-group>

            <b-form-group label="Datenbankport *">
                <b-form-input :class="{ 'is-invalid': errors.amavis_db_port }" type="text" v-model="form.amavis_db_port" placeholder="Datenbankport"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.amavis_db_port" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>
        </b-form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: {},
                form: {},
            }
        }
    }
</script>
