<template>
    <div class="ldap-index">
        <button type="button" class="btn btn-primary mb-2" @click="openStoreModal()"><i class="fas fa-plus"></i></button>

        <b-table hover :items="ldapDirectories" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage">
            <!-- A virtual composite column -->
            <template slot="app_actions" slot-scope="ldapDirectory">
                <button class="btn btn-secondary btn-sm" @click="openUpdateModal(ldapDirectory)"><i class="fas fa-edit"></i></button>
                <button class="btn btn-warning btn-sm" @click="deleteLdapDirectory(ldapDirectory)"><i class="fas fa-trash-alt"></i></button>
            </template>
        </b-table>

        <ldap-directory-store-update-modal
                v-bind:ldapDirectory="modalLdapDirectory"
                v-on:ldap-stored="getLdapDirectories"
                v-on:ldap-updated="getLdapDirectories"
        ></ldap-directory-store-update-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getLdapDirectories();
        },
        data() {
            return {
                modalLdapDirectory: null,
                currentPage: 1,
                perPage: 10,
                totalRows: null,
                ldapDirectories: [],
                filter: null,
                fields: [
                    {
                        key: 'connection',
                        label: 'Verbindung',
                    },
                    {
                        key: 'base_dn',
                        label: 'Base DN',
                    },
                    {
                        key: 'app_actions',
                        label: ''
                    }
                ]
            }
        },
        methods: {
            getLdapDirectories() {
                axios.get('/ldap').then((response) => {
                    this.ldapDirectories = response.data.data;

                    this.totalRows = this.ldapDirectories.length;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            deleteLdapDirectory(data) {
                axios.delete('/ldap/' + data.item.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        let index = this.ldapDirectories.findIndex(x => x.id === data.item.id);

                        this.$delete(this.ldapDirectories, index);
                    }).catch((error) => {
                    this.$notify({
                        title: error.response.data.message,
                        type: error.response.data.status
                    });
                });
            },
            openUpdateModal(data) {
                this.modalLdapDirectory = data.item;

                this.$bvModal.show('ldap-directory-update-store-modal');
            },
            openStoreModal() {
                this.modalLdapDirectory = null;

                this.$bvModal.show('ldap-directory-update-store-modal');
            }
        },
    }
</script>