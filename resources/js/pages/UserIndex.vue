<template>
    <div class="user.index">
        <button type="button" class="btn btn-primary mb-2" @click="openStoreModal()"><i class="fas fa-plus"></i></button>

        <b-table hover :items="users" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage">
            <!-- A virtual composite column -->
            <template slot="app_actions" slot-scope="user">
                <button class="btn btn-secondary btn-sm" @click="openUpdateModal(user)"><i class="fas fa-edit"></i></button>
                <button class="btn btn-warning btn-sm" @click="deleteUser(user)"><i class="fas fa-trash-alt"></i></button>
            </template>
        </b-table>

        <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage"></b-pagination>

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
                axios.get('/user').then((response) => {
                    this.users = response.data.data;

                    this.totalRows = this.users.length;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            deleteUser(data) {
                axios.delete('/user/' + data.item.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        let index = this.users.findIndex(x => x.id === data.item.id);

                        this.$delete(this.users, index);
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