<template>
    <div class="user.index">
        <b-row>
            <b-col md="3" >
                <b-button-group>
                    <button type="button" class="btn btn-primary" @click="openStoreModal"><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="getUsers"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>

            <b-col md="4" offset="5" >
                <b-form-group >
                    <b-input-group>
                        <b-form-input v-model="search" placeholder="Suche Benutzername" @change="getUsers"/>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>

        <template v-if="!loading">
            <b-table hover :items="users" :fields="fields" v-if="users.length">
                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-secondary btn-sm" @click="openUpdateModal(data)"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" @click="deleteRow(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>

            <b-row v-if="totalRows > 10">
                <b-col cols="2">
                    <b-form-select v-model="perPage" :options="displayedRowsOptions" @change="getUsers"></b-form-select>
                </b-col>
                <b-col cols="2" offset="3">
                    <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" v-if="totalRows > 10"  @change="changePage"></b-pagination>
                </b-col>
                <b-col cols="2" offset="3" v-if="users.length">
                    <p class="mt-1">Zeige Zeile {{ from }} bis {{ to }} von {{ totalRows }} Zeilen.</p>
                </b-col>
            </b-row>
        </template>

        <are-you-sure-modal v-on:answered-yes="deleteUser" v-on:answered-no="row = null"></are-you-sure-modal>

        <div class="text-center" v-if="loading">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Lade...</span>
            </div>
        </div>

        <user-store-update-modal
                v-bind:user="modalUser"
                v-on:user-stored="getUsers"
                v-on:user-updated="getUsers"
        ></user-store-update-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getUsers();
        },
        data() {
            return {
                /**
                 * Loader
                 */
                loading: false,

                /**
                 * Pagination
                 */
                currentPage: 1,
                perPage: 10,
                totalRows: 0,
                from: 0,
                to: 0,
                displayedRowsOptions: [
                    { value: 10, text: 10 },
                    { value: 25, text: 25 },
                    { value: 50, text: 50 },
                    { value: 100, text: 100 },
                ],

                /**
                 * Search
                 */
                search: null,

                modalUser: null,
                users: [],

                /**
                 * Table
                 */
                fields: [
                    {
                        key: 'username',
                        label: 'Benutzername',
                        sortable: true,
                    },
                    {
                        key: 'objectguid',
                        label: 'LDAP GUID',
                    },
                    {
                        key: 'email',
                        label: 'E-Mail',
                        sortable: true
                    },
                    {
                        key: 'role',
                        label: 'Rolle',
                    },
                    {
                        key: 'created_at',
                        label: 'Erstellt am',
                        sortable: false,
                    },
                    {
                        key: 'app_actions',
                        label: ''
                    },
                ],

                /**
                 * Are you sure modal
                 */
                row: null,
            }
        },
        methods: {
            deleteRow(data) {
                this.row = data.item;
                this.$bvModal.show('are-you-sure-modal');
            },
            changePage(data) {
                this.currentPage = data;
                this.getUsers();
            },
            getUsers() {
                this.loading = true;
                axios.get('/user', {
                    params: {
                        currentPage: this.currentPage,
                        perPage: this.perPage,
                        search: this.search,
                    }
                }).then((response) => {
                    this.users = response.data.data.data;
                    this.totalRows = response.data.data.total;
                    this.from = response.data.data.from;
                    this.to = response.data.data.to;
                    this.loading = false;
                }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    } else {
                        this.$notify({
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }
                    this.loading = false;
                });
            },
            deleteUser() {
                axios.delete('/user/' + this.row.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getUsers();
                    }).catch((error) => {
                        if (error.response) {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        } else {
                            this.$notify({
                                title: 'Unbekannter Fehler',
                                type: 'error'
                            });
                        }
                });
            },
            openUpdateModal(data) {
                this.modalUser = data.item;

                this.$bvModal.show('user-store-update-modal');
            },
            openStoreModal() {
                this.modalUser = null;

                this.$bvModal.show('user-store-update-modal');
            }
        }
    }
</script>
