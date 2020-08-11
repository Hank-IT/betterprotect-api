<template>
    <div class="recipient.store">
        <!-- Modal Component -->
        <b-modal id="recipient-store-modal" ref="recipientStoreModal" size="lg" :title="translate('features.policy.recipient.store')" @ok="handleOk" @shown="modalShown">
            <b-form>
                <b-form-group label="Eintrag *">
                    <b-form-input :class="{ 'is-invalid': errors.payload }" ref="payload" type="text" v-model="form.payload" :placeholder="translate('misc.entry')"></b-form-input>

                    <b-form-invalid-feedback>
                        <ul class="form-group-validation-message-list">
                            <li v-for="error in errors.payload" v-text="error"></li>
                        </ul>
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    action: 'OK',
                    payload: null,
                    data_source: 'local',
                },
                errors: [],
            }
        },
        methods: {
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                this.storeRecipient();
            },
            modalShown() {
                this.form.payload = null;

                this.$refs.payload.focus();

                this.errors = [];
            },
            storeRecipient() {
                axios.post('/recipient', this.form).then((response) => {
                    this.$emit('recipient-stored', response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.recipientStoreModal.hide();
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
                    }
                });
            }
        }
    }
</script>
