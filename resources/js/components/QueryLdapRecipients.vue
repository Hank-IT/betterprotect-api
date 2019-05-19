<template>
    <div class="ldap.query">
        <!-- Modal Component -->
        <b-modal id="ldap-query-modal" ref="ldapQueryModal" size="lg" title="LDAP" @ok="handleOk" @shown="modalShown">
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
                }).catch(function (error) {
                    console.log(error);
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
                }).catch(function (error) {
                    this.$notify({
                        title: error.response.data.message,
                        type: 'error'
                    });
                });
            }
        }
    }
</script>