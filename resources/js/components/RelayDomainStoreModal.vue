<template>
    <b-modal id="relay-domain-store-modal" ref="relayDomainStoreModal" size="lg" :title="translate('features.policy.relay_domain.relay_domain')" @ok="handleOk" @shown="modalShown">
        <b-form>
            <b-form-group label="Domain *">
                <b-form-input :class="{ 'is-invalid': errors.domain }" type="text" ref="domain" v-model="form.domain" :placeholder="translate('validation.attributes.domain')"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.domain" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>
        </b-form>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                form: {},
                errors: [],
            }
        },
        methods: {
            handleOk(event) {
                event.preventDefault();

                this.store();
            },
            modalShown() {
                this.form = {};
                this.$refs.domain.focus();
            },
            store() {
                axios.post('/relay-domain', this.form).then((response) => {
                    this.$emit('relay-domain-stored', response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.relayDomainStoreModal.hide();
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
                            title: this.translate('misc.errors.unknown'),
                            type: 'error'
                        });
                    }
                });

                this.errors = [];
            }
        }
    }
</script>
