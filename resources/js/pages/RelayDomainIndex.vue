<template>
    <div class="relay-domain index">
        <b-row>
            <b-col md="3" >
                <b-button-group>
                    <button :disabled="! $auth.check(['editor', 'administrator'])" type="button" class="btn btn-primary" @click="openModal()"><i class="fas fa-plus"></i></button>
                    <b-btn variant="secondary" @click="getRelayDomains"><i class="fas fa-sync"></i></b-btn>
                </b-button-group>
            </b-col>

            <b-col md="4" offset="5" >
                <b-form-group >
                    <b-input-group>
                        <b-form-input v-model="search" :placeholder="translate('misc.search')" @change="getRelayDomains"/>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>

        <template v-if="!loading">
            <b-table hover :items="relayDomains" :fields="fields" v-if="relayDomains.length" :tbody-tr-class="rowClass">
                <template v-slot:cell(app_actions)="data">
                    <button :disabled="! $auth.check(['editor', 'administrator'])" class="btn btn-warning btn-sm" @click="activation(data)"><i class="fas" :class="{ 'fa-lock': data.item.active === 1, 'fa-unlock': data.item.active === 0 }"></i></button>
                    <button :disabled="! $auth.check(['editor', 'administrator'])" class="btn btn-danger btn-sm" @click="deleteRow(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">{{ translate('misc.no-data-available') }}</h4>
            </b-alert>

            <b-row v-if="totalRows > 10">
                <b-col cols="2">
                    <b-form-select v-model="perPage" :options="displayedRowsOptions" @change="getRelayDomains"></b-form-select>
                </b-col>
                <b-col cols="2" offset="3">
                    <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" v-if="totalRows > 10" @change="changePage"></b-pagination>
                </b-col>
                <b-col cols="2" offset="3" v-if="relayDomains.length">
                    <p class="mt-1">{{ translate('misc.pagination', {'from': from, 'to': to, 'total': totalRows}) }}</p>
                </b-col>
            </b-row>
        </template>

        <div class="text-center" v-if="loading">
            <b-spinner type="grow" :label="translate('misc.loading') + '...'"></b-spinner>
        </div>

        <are-you-sure-modal v-on:answered-yes="deleteRelayDomain" v-on:answered-no="row = null"></are-you-sure-modal>

        <relay-domain-store-modal v-on:relay-domain-stored="getRelayDomains"></relay-domain-store-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getRelayDomains();
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

                relayDomains: [],

                /**
                 * Table
                 */
                fields: [
                    {
                        key: 'domain',
                        label: this.translate('validation.attributes.domain'),
                        sortable: true,
                    },
                    {
                        key: 'created_at',
                        label: this.translate('misc.created_at'),
                        sortable: false,
                    },
                    {
                        key: 'app_actions',
                        label: this.translate('misc.options'),
                    }
                ],

                /**
                 * Are you sure modal
                 */
                row: null,
            }
        },
        methods: {
            rowClass(item, type) {
                if (!item) {
                    return;
                }

                if (item.active === 0) {
                    return 'table-secondary'
                }
            },
            activation(data) {
                if (data.item.active === 0) {
                    axios.post('/activation/' + data.item.id, {
                        model: 'RelayDomain',
                    }).then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getRelayDomains();
                    }).catch((error) => {
                        let title = error.response
                            ? error.response.data.message
                            : this.translate('misc.errors.unknown');

                        this.$notify({
                            title: title,
                            type: 'error'
                        });

                        this.loading = false;
                    });
                } else {
                    axios.patch('/activation/' + data.item.id, {
                        model: 'RelayDomain',
                    }).then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getRelayDomains();
                    }).catch((error) => {
                        let title = error.response
                            ? error.response.data.message
                            : this.translate('misc.errors.unknown');

                        this.$notify({
                            title: title,
                            type: 'error'
                        });

                        this.loading = false;
                    });
                }
            },
            deleteRow(data) {
                this.row = data.item;
                this.$bvModal.show('are-you-sure-modal');
            },
            openModal() {
                this.$bvModal.show('relay-domain-store-modal');
            },
            changePage(data) {
                this.currentPage = data;
                this.getRelayDomains();
            },
            getRelayDomains() {
                this.loading = true;
                axios.get('/relay-domain', {
                    params: {
                        currentPage: this.currentPage,
                        perPage: this.perPage,
                        search: this.search,
                    }
                }).then((response) => {
                    this.relayDomains = response.data.data.data;
                    this.totalRows = response.data.data.total;
                    this.from = response.data.data.from;
                    this.to = response.data.data.to;
                    this.loading = false;
                }).catch((error) => {
                    let title = error.response
                        ? error.response.data.message
                        : this.translate('misc.errors.unknown');

                    this.$notify({
                        title: title,
                        type: 'error'
                    });

                    this.loading = false;
                });
            },
            deleteRelayDomain() {
                axios.delete('/relay-domain/' + this.row.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.getRelayDomains();
                    }).catch((error) => {
                        let title = error.response
                            ? error.response.data.message
                            : this.translate('misc.errors.unknown');

                        this.$notify({
                            title: title,
                            type: 'error'
                        });
                });
            }
        }
    }
</script>
