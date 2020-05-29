<template>
    <div class="ldap.query">
        <!-- Modal Component -->
        <b-modal id="ldap-query-modal" ref="ldapQueryModal" size="lg" :title="translate('features.ldap.ldap')" @ok="handleOk" @shown="modalShown">
            <b-form>
                <b-form-select v-model="ldapDirectory" :options="ldapDirectories" :value-field="'id'" :html-field="'connection'"></b-form-select>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                ldapDirectories: [],
                ldapDirectory: null,
            }
        },
        methods: {
            modalShown() {
                this.getLdapDirectories();
            },
            getLdapDirectories() {
                axios.get('/ldap').then((response) => {
                    this.ldapDirectories = response.data.data;
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                });
            },
            handleOk(event) {
                // Prevent modal from closing
                event.preventDefault();

                this.queryLdapRecipients();
            },
            queryLdapRecipients() {
                axios.post('/recipient/ldap/' + this.ldapDirectory).then((response) => {
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });

                    this.$refs.ldapQueryModal.hide();
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                });
            }
        }
    }
</script>
