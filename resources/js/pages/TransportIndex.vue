<template>
    <div class="transport.index">
        <button type="button" class="btn btn-primary mb-2" @click="openModal()"><i class="fas fa-plus"></i></button>

        <b-table hover :items="transports" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage">
            <!-- A virtual composite column -->
            <template slot="app_actions" slot-scope="transport">
                <button class="btn btn-warning btn-sm" @click="deleteTransport(transport)"><i class="fas fa-trash-alt"></i></button>
            </template>
        </b-table>

        <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage"></b-pagination>

        <transport-store-modal v-on:transport-stored="getTransports"></transport-store-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getTransports();
        },
        data() {
            return {
                transports: [],

                /**
                 * Pagination
                 */
                currentPage: 1,
                perPage: 10,
                totalRows: null,

                /**
                 * Table
                 */
                fields: [
                    {
                        key: 'domain',
                        label: 'Domain',
                        sortable: true,
                    },
                    {
                        key: 'transport',
                        label: 'Transport',
                        sortable: true,
                    },
                    {
                        key: 'nexthop',
                        label: 'Nexthop',
                        sortable: true,
                    },
                    {
                        key: 'nexthop_port',
                        label: 'Nexthop Port',
                    },
                    {
                        key: 'nexthop_mx',
                        label: 'Nexthop MX',
                        sortable: true,
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
                 * Table search
                 */
                filter: null,
            }
        },
        methods: {
            openModal() {
                this.$bvModal.show('transport-store-modal');
            },
            getTransports() {
                axios.get('/transport').then((response) => {
                    this.transports = response.data.data;

                    this.totalRows = this.transports.length;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            deleteTransport(data) {
                axios.delete('/transport/' + data.item.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        let index = this.transports.findIndex(x => x.id === data.item.id);

                        this.$delete(this.transports, index);
                    }).catch((error) => {
                    this.$notify({
                        title: error.response.data.message,
                        type: error.response.data.status
                    });
                });
            }
        }
    }
</script>
