<template>
    <div class="policy.store">
        <!-- Modal Component -->
        <b-modal id="policy-store-modal" ref="policy-store-modal" size="lg" title="Policy Installation" @shown="modalShown" @ok="handleOk">
            <b-form @submit.stop.prevent="installPolicy">
                <b-form-select v-model="selectedServer" :options="servers" class="mb-3"></b-form-select>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                servers: [],
                selectedServer: null,
            }
        },
        methods: {
            modalShown() {
                this.getAllServers();
            },
            installPolicy() {
                axios.post('/policy', { server_id: this.selectedServer }).then((response) => {
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });
                }).catch((error) => {
                    console.log(error);
                });
            },
            handleOk() {
                this.installPolicy();
            },
            getAllServers() {
                axios.get('/server').then((response) => {
                    let data = [];
                    for (const key of Object.keys(response.data)) {
                        data[key] = {
                            value: response.data[key].id,
                            text: response.data[key].hostname,
                            disabled: ! response.data[key].active,
                        }
                    }

                    this.servers = data;
                }).catch((error) => {
                    this.$notify({
                        title: error.response.data.message,
                        type: 'error'
                    });
                });
            },
        }
    }
</script>
