<template>
    <div class="ldap-index">
        <button type="button" class="btn btn-primary mb-2" @click="openStoreModal()"><i class="fas fa-plus"></i></button>

        <b-table hover :items="ldapDirectories" :fields="fields" :current-page="currentPage" :per-page="perPage">
            <!-- A virtual composite column -->
            <template v-slot:cell(app_actions)="data">
                <button class="btn btn-secondary btn-sm" @click="openUpdateModal(data)"><i class="fas fa-edit"></i></button>
                <button class="btn btn-warning btn-sm" @click="deleteLdapDirectory(data)"><i class="fas fa-trash-alt"></i></button>
            </template>
        </b-table>

        <b-row v-if="totalRows > 10">
            <b-col cols="1">
                <b-form-select v-model="perPage" :options="displayedRowsOptions" @change="getLdapDirectories"></b-form-select>
            </b-col>
            <b-col cols="2">
                <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" @change="changePage"></b-pagination>
            </b-col>
        </b-row>

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
                /**
                 * Pagination
                 */
                currentPage: 1,
                perPage: 10,
                totalRows: null,
                displayedRowsOptions: [
                    { value: 10, text: 10 },
                    { value: 25, text: 25 },
                    { value: 50, text: 50 },
                    { value: 100, text: 100 },
                ],

                modalLdapDirectory: null,
                ldapDirectories: [],
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
                axios.get('/ldap', {
                    params: {
                        currentPage: this.currentPage,
                        perPage: this.perPage,
                    }
                }).then((response) => {
                    console.log(response);
                    this.ldapDirectories = Object.values(response.data.data);
                    this.totalRows = response.data.total;
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                });
            },
            changePage(data) {
                this.currentPage = data;
                this.getLdapDirectories();
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
