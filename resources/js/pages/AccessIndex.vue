<template>
    <div class="access.index">
        <b-row>
            <b-col md="6" >
                <access-store
                        v-on:access-stored="addAccess"
                ></access-store>
            </b-col>

            <b-col md="6" >
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

        <b-table hover :items="rules" :fields="fields" :filter="filter" @row-clicked="showModal">
            <template slot="action" slot-scope="data">
                <p :class="{ 'text-success': data.value === 'ok', 'text-danger': data.value === 'reject' }">{{ data.value }}</p>
            </template>

            <!-- A virtual composite column -->
            <template slot="app_actions" slot-scope="data">
                <!-- <button class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button> -->
                <button class="btn btn-warning btn-sm" @click="deleteAccess(data)"><i class="fas fa-trash-alt"></i></button>
            </template>
        </b-table>

        <b-modal title="Beschreibung" ok-only id="access-description-modal">
            <p>{{ this.modalDescription }}</p>
        </b-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getAccessRules();
        },
        data() {
            return {
                modalDescription: null,
                rules: [],
                filter: null,
                fields: [
                    {
                        key: 'payload',
                        label: 'Eintrag',
                        sortable: true,
                    },
                    {
                        key: 'type',
                        label: 'Type',
                        sortable: true
                    },
                    {
                        key: 'action',
                        label: 'Aktion',
                        sortable: false,
                    },
                    {
                        key: 'payload',
                        label: 'Eintrag',
                        sortable: true,
                    },
                    {
                        key: 'created_at',
                        label: 'Erstellt am',
                    },
                    {
                        key: 'updated_at',
                        label: 'Aktualisiert am',
                    },
                    {
                        key: 'app_actions',
                        label: ''
                    }
                ],
            }
        },
        methods: {
            showModal(row) {
                this.modalDescription = row.description;

                this.$bvModal.show('access-description-modal');
            },
            getAccessRules() {
                axios.get('/access').then((response) => {
                    this.rules = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            addAccess(data) {
                this.rules.push(data);
            },
            deleteAccess(row) {
                axios.delete('/access/' + row.item.id).then((response) => {
                    let ruleIndex = this.rules.findIndex(x => x.id === row.item.id);

                    this.$delete(this.rules, ruleIndex);

                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });
                }).catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>