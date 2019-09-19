<template>
    <div class="ldap-index">
        <b-row>
            <b-col md="3" >
                <b-button-group>
                    <button type="button" class="btn btn-primary" @click="openStoreModal"><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="getLdapDirectories"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>

            <b-col md="4" offset="5">
                <b-form-group >
                    <b-input-group>
                        <b-form-input v-model="filter" placeholder="Suche" />
                        <b-input-group-append>
                            <b-btn :disabled="!filter" @click="filter = ''">Leeren</b-btn>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>

        <template v-if="!loading">
            <b-table hover :items="ldapDirectories" :fields="fields" :current-page="currentPage" :per-page="perPage" v-if="ldapDirectories.length">
                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-secondary btn-sm" @click="openUpdateModal(data)"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-warning btn-sm" @click="deleteLdapDirectory(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>

            <b-row v-if="totalRows > 10">
                <b-col cols="1">
                    <b-form-select v-model="perPage" :options="displayedRowsOptions" @change="getLdapDirectories"></b-form-select>
                </b-col>
                <b-col cols="2">
                    <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" @change="changePage"></b-pagination>
                </b-col>
            </b-row>
        </template>

        <ldap-directory-store-update-modal
                v-bind:ldapDirectory="modalLdapDirectory"
                v-on:ldap-stored="getLdapDirectories"
                v-on:ldap-updated="getLdapDirectories"
        ></ldap-directory-store-update-modal>

        <div class="text-center" v-if="loading">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>
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
                filter: null,
                displayedRowsOptions: [
                    { value: 10, text: 10 },
                    { value: 25, text: 25 },
                    { value: 50, text: 50 },
                    { value: 100, text: 100 },
                ],

                /**
                 * Loader
                 */
                loading: false,

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
                this.loading = true;
                axios.get('/ldap', {
                    params: {
                        currentPage: this.currentPage,
                        perPage: this.perPage,
                    }
                }).then((response) => {
                    this.ldapDirectories = Object.values(response.data.data);
                    this.totalRows = response.data.total;
                    this.loading = false;
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                    this.loading = false;
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

                        this.getLdapDirectories();
                    }).catch((error) => {
                        if (error.response) {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }
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
