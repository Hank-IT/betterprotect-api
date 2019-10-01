<template>
    <div class="server-wizard.server">
        <b-form>
            <b-form-group label="Hostname *">
                <b-form-input :class="{ 'is-invalid': errors.hostname, 'is-valid': isValid('hostname') }" type="text" ref="hostname" v-model="form.hostname" placeholder="Hostname" :disabled="submitted"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.hostname" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Beschreibung">
                <b-form-textarea :class="{ 'is-invalid': errors.description, 'is-valid': isValid('description') }" type="text" v-model="form.description" rows="4" placeholder="Beschreibung" :disabled="submitted"></b-form-textarea>

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
                submitted: false,
            }
        },
        props: ['bus'],
        created() {
            this.bus.$on('server-wizard-submit-server', this.submit);
        },
        methods: {
            submit(props) {
                // Prevent second submit
                if (this.submitted) {
                    props.nextTab();

                    return;
                }

                axios.post('/server-wizard', this.form).then((response) => {
                    this.errors = {};
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$emit('server-wizard-submit-server-success', response.data.data);

                    this.submitted = true;
                    props.nextTab();
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
            isValid(index) {
                if (this.submitted) {
                    if (Object.keys(this.errors).length === 0) {
                        return true;
                    }

                    return this.errors[index];
                }

                return false;
            }
        }
    }
</script>
