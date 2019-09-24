<template>
    <div class="server.queue">
        <b-modal id="server-queue-modal" size="xl" title="Server Mail Queue" @shown="modalShown">
            <b-btn variant="secondary" @click="modalShown" class="mb-2"><i class="fas fa-sync"></i></b-btn>

            <div class="text-center" v-if="queuedMailsLoading">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <b-table hover
                     :items="queuedMails"
                     :fields="fields"
                     :sort-by.sync="sortBy"
                     :sort-desc.sync="sortDesc"
                     v-else
            >
                <template v-slot:cell(arrival_time)="data">
                    {{ moment.unix(data.item.arrival_time).format("YYYY-MM-DD HH:mm:ss") }}
                </template>

                <template v-slot:cell(sender)="data">
                    <span v-b-popover.hover="data.item.sender">
                        {{ data.item.sender.trunc(40) }}
                    </span>
                </template>

                <template v-slot:cell(recipients)="data">
                    <ul class="list-group">
                        <li class="list-group-item" v-for="recipient in data.item.recipients">{{ recipient.address }} ({{ recipient.delay_reason }})</li>
                    </ul>
                </template>

                <template v-slot:cell(app_actions)="data">
                    <button class="btn btn-warning btn-sm" @click="deleteQueuedMail(data)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </b-table>

            <div class="text-center text-danger" v-if="this.error">{{ this.error }}</div>

            <div slot="modal-footer"></div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: ['server'],
        data() {
            return {
                queuedMails: [],
                sortBy: 'arrival_time',
                sortDesc: true,
                fields: [
                    {
                        key: 'queue_name',
                        label: 'Queue',
                        sortable: true,
                    },
                    {
                        key: 'queue_id',
                        label: 'Queue ID',
                    },
                    {
                        key: 'arrival_time',
                        label: 'Empfangen',
                        sortable: true,
                    },
                    {
                        key: 'message_size',
                        label: 'Größe',
                    },
                    {
                        key: 'sender',
                        label: 'Absender',
                    },
                    {
                        key: 'recipients',
                        label: 'Empfänger',
                    },
                    {
                        key: 'app_actions',
                        label: ''
                    }

                ],
                queuedMailsLoading: true,
                error: false,
            }
        },
        methods: {
            modalShown() {
                this.error = false;
                this.queuedMails = [];

                axios.get('/server/' + this.server.id + '/queue')
                    .then((response) => {
                        this.queuedMails = response.data.data;
                        this.queuedMailsLoading = false;
                })
                    .catch((error) => {
                        console.log(error);

                        if (error.response.status === 500) {
                            this.error = error.response.data.message;
                        } else {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }

                        this.queuedMailsLoading = false;
                });
            },
            deleteQueuedMail(data) {
                axios.delete('/server/' + this.server.id + '/queue/' + data.item.queue_id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        let queuedMailsIndex = this.queuedMails.findIndex(x => x.queue_id === data.item.queue_id);

                        this.$delete(this.queuedMails, queuedMailsIndex);
                    })
                    .catch((error) => {
                        if (error.response.status === 500) {
                            this.error = error.response.data.message;
                        } else {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }
                    });
            }
        }
    };
</script>
