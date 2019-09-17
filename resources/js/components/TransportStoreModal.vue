<template>
    <b-modal id="transport-store-modal" ref="transportStoreModal" size="lg" title="Transport" @ok="handleOk" @shown="modalShown">
        <b-form>
            <b-form-group label="Domain *">
                <b-form-input :class="{ 'is-invalid': errors.domain }" type="text" ref="domain" v-model="form.domain" placeholder="Domain"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.domain" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Transport">
                <b-form-input :class="{ 'is-invalid': errors.transport }" type="text" ref="transport" v-model="form.transport" placeholder="z.B. smtp"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.transport" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Nexthop Typ">
                <b-form-select v-model="form.nexthop_type" :options="nexthopTypeOptions"></b-form-select>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.nexthop_type" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Nexthop" v-if="form.nexthop_type !== null">
                <b-form-input :class="{ 'is-invalid': errors.nexthop }" type="text" ref="nexthop" v-model="form.nexthop" placeholder="Nexthop"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.nexthop" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Nexthop Port" v-if="form.nexthop_type !== null">
                <b-form-input :class="{ 'is-invalid': errors.nexthop_port }" type="text" ref="nexthop_port" v-model="form.nexthop_port" placeholder="Nexthop Port"></b-form-input>

                <b-form-invalid-feedback>
                    <ul class="form-group-validation-message-list">
                        <li v-for="error in errors.nexthop_port" v-text="error"></li>
                    </ul>
                </b-form-invalid-feedback>
            </b-form-group>

            <b-form-checkbox id="nexthop_mx" v-model="form.nexthop_mx" name="nexthop_mx" value="true" unchecked-value="false" v-if="form.nexthop_type === 'hostname'">Nexthop MX Lookup</b-form-checkbox>
        </b-form>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                form: {},
                errors: [],
                nexthopTypeOptions: [
                    { value: null, text: 'Leer' },
                    { value: 'ipv4', text: 'IPv4' },
                    { value: 'ipv6', text: 'IPv6' },
                    { value: 'hostname', text: 'Hostname' }
                ]
            }
        },
        methods: {
            handleOk(event) {
                event.preventDefault();

                this.store();
            },
            modalShown() {
                this.form = {
                    transport: 'smtp',
                    nexthop_type: 'ipv4',
                    nexthop_port: 25,
                };

                this.$refs.domain.focus();
            },
            store() {
                axios.post('/transport', this.form).then((response) => {
                    this.$emit('transport-stored', response.data.data);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.transportStoreModal.hide();
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

                this.errors = [];
            }
        }
    }
</script>
