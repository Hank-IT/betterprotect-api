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
                        <b-form-input v-model="filter" placeholder="Suche" />
                        <b-input-group-append>
                            <b-btn :disabled="!filter" @click="filter = ''">Leeren</b-btn>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>

        <template v-if="!loading">
            <b-table hover :items="users" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage" v-if="users.length">
                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-secondary btn-sm" @click="openUpdateModal(data)"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-warning btn-sm" @click="deleteUser(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>

            <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" v-if="totalRows > 10"></b-pagination>
        </template>

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

                modalUser: null,
                currentPage: 1,
                perPage: 10,
                totalRows: null,
                users: [],
                filter: null,
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
                        key: 'created_at',
                        label: 'Erstellt am',
                        sortable: false,
                    },
                    {
                        key: 'app_actions',
                        label: ''
                    },
                ]
            }
        },
        methods: {
            getUsers() {
                this.loading = true;
                axios.get('/user').then((response) => {
                    this.users = response.data.data;
                    this.totalRows = this.users.length;
                    this.loading = false;
                }).catch(function (error) {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                    this.loading = false;
                });
            },
            deleteUser(data) {
                axios.delete('/user/' + data.item.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getUsers();
                    }).catch((error) => {
                        this.$notify({
                            title: error.response.data.message,
                            type: error.response.data.status
                        });
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
