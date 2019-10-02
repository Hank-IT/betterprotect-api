<template>
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
            axios.get('/server/' + this.server).then((response) => {
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
                axios.patch('/server/' + this.server, this.form).then((response) => {
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
            }
        }
    }
</script>
