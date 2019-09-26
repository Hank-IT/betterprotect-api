<template>
    <div class="server-wizard.server">
        <b-form>
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
                <b-form-textarea :class="{ 'is-invalid': errors.ssh_private_key }" type="text" v-model="form.ssh_private_key" rows="4" placeholder="Private Key"></b-form-textarea>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.ssh_private_key" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>

                <p class="text-muted mb-0">Der Schlüssel wird aus Sicherheitsgründen nicht angezeigt!</p>
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
