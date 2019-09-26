<template>
    <div class="server-wizard.server">
        <b-form>
            <b-form-group label="Hostname *">
                <b-form-input :class="{ 'is-invalid': errors.hostname }" type="text" ref="hostname" v-model="form.hostname" placeholder="Hostname"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.hostname" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Beschreibung">
                <b-form-textarea :class="{ 'is-invalid': errors.description }" type="text" v-model="form.description" rows="4" placeholder="Beschreibung"></b-form-textarea>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.description" v-text="error"></li>
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
