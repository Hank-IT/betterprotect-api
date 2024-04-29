<template>
    <div class="server.log">
        <div class="toolbar">
            <b-row>
                <b-col md="4">
                    <form class="form-inline">
                        <b-btn variant="secondary" @click="currentLogs(false)"><i class="fas fa-sync"></i></b-btn>
                        <b-select v-model="mailStatusSelected" :options="mailStatusOptions" @change="getLogs" class="ml-1"></b-select>
                        <b-form-checkbox
                                class="ml-5"
                                v-model="autoRefresh"
                                value="true"
                                unchecked-value="false"
                        >
                            Automatisch aktualisieren
                        </b-form-checkbox>
                    </form>
                </b-col>

                <b-col md="6" offset="2">
                    <b-form-group >
                        <b-input-group>
                            <b-input-group-prepend>
                                    <date-range-picker
                                            :maxDate="maxDate"
                                            :locale-data="locale"
                                            :opens="opens"
                                            :timePicker="timePicker"
                                            :timePicker24Hour="timePicker24Hour"
                                            v-model="dateRange"
                                            @update="updateSelectedDate"
                                    >
                                        <div slot="input" slot-scope="picker">{{ currentStart | date }} - {{ currentEnd | date }}</div>
                                    </date-range-picker>
                            </b-input-group-prepend>
                            <b-form-input v-model="search" placeholder="Suche" @change="getLogs"/>
                        </b-input-group>
                    </b-form-group>
                </b-col>

            </b-row>
        </div>

        <template v-if="!logsLoading">
            <b-table hover :items="logs" :fields="fields" @row-clicked="showModal" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" v-if="logs.length">
                <template v-slot:cell(from)="data">
                    <span v-b-popover.hover="data.item.from" v-if="data.item.from">
                        {{ data.item.from.trunc(40) }}
                    </span>
                </template>
                <template v-slot:cell(to)="data">
                    <span v-b-popover.hover="data.item.to" v-if="data.item.to">
                        {{ data.item.to.trunc(40) }}
                    </span>
                </template>
                <template v-slot:cell(client)="data">
                    <span v-if="data.item.client">
                        <span v-b-popover.hover="data.item.client_ip_country" v-if="data.item.client_ip_country_iso_code" class="flag-icon" :class="['flag-icon-' + data.item.client_ip_country_iso_code]"></span>
                        <span v-b-popover.hover="data.item.client">{{ data.item.client.trunc(40) }}</span>
                    </span>
                </template>
                <template v-slot:cell(status)="data">
                    <span v-if="data.item.status">
                        <span v-bind:class="getStatusClass(data)">{{ translate('postfix.mail.status.' + data.item.status.toLowerCase()) }}</span>
                    </span>
                </template>
            </b-table>

            <b-alert show variant="warning" v-else>
                <h4 class="alert-heading text-center">Keine Daten vorhanden</h4>
            </b-alert>

            <b-row v-if="totalRows > 10">
                <b-col cols="2">
                    <b-form-select v-model="perPage" :options="displayedRowsOptions" v-if="!logsLoading" @change="getLogs"></b-form-select>
                </b-col>
                <b-col cols="2" offset="3">
                    <b-pagination size="md" :total-rows="totalRows" v-model="currentPage" :per-page="perPage" @change="changePage" v-if="!logsLoading"></b-pagination>
                </b-col>
                <b-col cols="2" offset="3" v-if="logs.length">
                    <p class="mt-1">Zeige Zeile {{ from }} bis {{ to }} von {{ totalRows }} Zeilen.</p>
                </b-col>
            </b-row>
        </template>

        <div class="text-center" v-if="logsLoading">
            <b-spinner type="grow" :label="translate('misc.loading') + '...'"></b-spinner>
        </div>

        <b-modal id="mail-log-modal" ref="mailLogModal" size="xl" title="Mail Log" @shown="loadWhoisData">
            <div class="stepwizard col-md-offset-3">
                <div class="stepwizard-row">
                    <div class="stepwizard-step">
                        <p>{{ detailedRow.client }}&nbsp;</p>
                        <button type="button" class="btn btn-circle btn-secondary" :class="{ 'btn-success': detailedRow.status === 'sent', 'btn-warning': detailedRow.status === 'deferred', 'btn-danger': detailedRow.status === 'reject' }">Client</button>
                        <p class="stepwizard-step-subtitle">{{ detailedRow.from }}</p>
                    </div>
                    <div class="stepwizard-step">
                        <p>&nbsp;</p>
                        <button type="button" class="btn btn-circle btn-secondary" :class="{ 'btn-success': detailedRow.status === 'sent', 'btn-warning': detailedRow.status === 'deferred', 'btn-danger': detailedRow.status === 'reject' }">Server</button>
                        <p class="stepwizard-step-subtitle">{{ detailedRow.host }}</p>
                    </div>
                    <div class="stepwizard-step">
                        <p>{{ detailedRow.relay }}&nbsp;</p>
                        <button type="button" class="btn btn-circle btn-secondary" :class="{ 'btn-success': detailedRow.status === 'sent', 'btn-warning': detailedRow.status === 'deferred', 'btn-danger': detailedRow.status === 'reject' }">Relay</button>
                        <p class="stepwizard-step-subtitle">{{ detailedRow.to }}</p>
                    </div>
                </div>
            </div>

            <template v-if="detailedRow.bp_policy_decision">
                <b-alert v-if="detailedRow.bp_policy_decision === 'reject'" show variant="danger">Diese E-Mail wurde aufgrund der Betterprotect Policy abgelehnt!</b-alert>
                <b-alert v-if="detailedRow.bp_policy_decision === 'ok'" show variant="success">Diese E-Mail wurde aufgrund der Betterprotect Policy angenommen!</b-alert>
            </template>

            <table class="table">
                <tr v-if="detailedRow.client_ip && (detailedRow.status === 'reject' || detailedRow === 'ok') && ! detailedRow.bp_policy_decision">
                    <th>Aktionen</th>
                    <td>
                        <b-btn :disabled="! $auth.check(['authorizer', 'editor', 'administrator'])" size="sm" variant="primary" @click="whitelist(detailedRow)" v-if="detailedRow.status === 'reject' || detailedRow.status === 'bounced'">Whitelist</b-btn>
                        <b-btn :disabled="! $auth.check(['authorizer', 'editor', 'administrator'])" size="sm" variant="primary" @click="blacklist(detailedRow)" v-if="detailedRow.status === 'sent'">Blacklist</b-btn>
                    </td>
                </tr>
                <tr v-if="detailedRow.subject">
                    <th>Betreff</th>
                    <td>{{ detailedRow.subject }}</td>
                </tr>
                <tr v-if="detailedRow.client_ip_country_iso_code">
                    <th>Client IP Herkunft</th>
                    <td><span class="flag-icon" :class="['flag-icon-' + detailedRow.client_ip_country_iso_code]"></span> {{ detailedRow.client_ip_country }}</td>
                </tr>
                <tr>
                    <th>Organisation</th>
                    <td v-if="whoisLoading">
                        <b-spinner small type="grow" :label="translate('misc.loading') + '...'"></b-spinner>
                    </td>
                    <td v-else>
                        {{ whoisOrganisation }}
                    </td>
                </tr>
                <tr>
                    <th>Abuse Kontakt</th>
                    <td v-if="whoisLoading">
                        <b-spinner small type="grow" :label="translate('misc.loading') + '...'"></b-spinner>
                    </td>
                    <td v-else>
                        <template v-if="whoisAbuseContact === 'N/A'">{{ whoisAbuseContact }}</template>
                        <a v-else :href="['mailto:' + whoisAbuseContact]">{{ whoisAbuseContact }}</a>
                    </td>
                </tr>
                <tr>
                    <th>Erhalten am</th>
                    <td>{{ detailedRow.reported_at }}</td>
                </tr>
                <tr>
                    <th>Queue ID</th>
                    <td>{{ detailedRow.queue_id }}</td>
                </tr>
                <tr v-if="detailedRow.ndn_queue_id">
                    <th>Unzustellbarkeitsbenachrichtigung (Queue ID)</th>
                    <td>{{ detailedRow.ndn_queue_id }}</td>
                </tr>
                <tr>
                    <th>Code</th>
                    <td>{{ detailedRow.dsn }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ detailedRow.status }}</td>
                </tr>
                <tr>
                    <th>Helo</th>
                    <td>{{ detailedRow.helo }}</td>
                </tr>
                <tr>
                    <th>Größe (Bytes)</th>
                    <td>{{ detailedRow.size }}</td>
                </tr>
                <tr>
                    <th>Delays</th>
                    <td>{{ detailedRow.delays }}</td>
                </tr>
                <tr>
                    <th>Empfänger (Anzahl)</th>
                    <td>{{ detailedRow.nrcpt }}</td>
                </tr>
                <tr v-if="Object.keys(detailedRow).includes('enc_type')">
                    <th>Verschlüsselung</th>
                    <td v-if="detailedRow.enc_type === ''">Keine</td>
                    <td v-else>{{ detailedRow.enc_cipher }} ({{ detailedRow.enc_type }})</td>
                </tr>
                <tr v-else>
                    <th>Verschlüsselung</th>
                    <td>Unbekannt</td>
                </tr>
            </table>

            <hr>

            <h5>Antwort:</h5>

            <p>{{ detailedRow.response }}</p>

            <div slot="modal-footer"></div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        created() {
            this.getLogs();

            window.setInterval(() => {
                if (this.autoRefresh === 'true') {
                    this.currentLogs(true);
                }
            }, 10000);
        },
        data() {
            return {
                /**
                 * Pagination
                 */
                currentPage: 1,
                perPage: 12,
                to: 0,
                from: 0,
                totalRows: null,
                sortBy: 'reported_at',
                sortDesc: true,
                displayedRowsOptions: [
                    { value: 12, text: 12 },
                    { value: 25, text: 25 },
                    { value: 50, text: 50 },
                    { value: 100, text: 100 },
                ],

                /**
                 * Mail Status Filter
                 */
                mailStatusSelected: null,
                mailStatusOptions: [
                    { value: null, text: this.translate('misc.choose-status') },
                    { value: 'reject', text: this.translate('postfix.mail.status.reject') },
                    { value: 'sent', text: this.translate('postfix.mail.status.sent') },
                    { value: 'deferred', text: this.translate('postfix.mail.status.deferred') },
                    { value: 'bounced', text: this.translate('postfix.mail.status.bounced') },
                    { value: 'filter', text: this.translate('postfix.mail.status.filter') },
                ],

                /**
                 * Whois
                 */
                whoisOrganisation: 'N/A',
                whoisAbuseContact: 'N/A',
                whoisLoading: false,

                /**
                 * Search
                 */
                search: null,

                /**
                 * Datepickerpicker
                 */
                maxDate: this.moment().format('YYYY/MM/DD HH:mm'),
                currentStart: this.moment().subtract(1, 'hours'),
                currentEnd: this.moment().add(1, 'minutes'),
                dateRange: {
                    startDate: this.moment().subtract(1, 'hours').format('YYYY/MM/DD HH:mm'),
                    endDate: this.moment().add(1, 'minutes').format('YYYY/MM/DD HH:mm'),
                },
                opens: 'right',
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    direction: 'ltr', //direction of text
                    format: 'YYYY/MM/DD HH:mm', // format of the dates displayed
                    separator: ' - ', //separator between the two ranges
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    weekLabel: 'W',
                    daysOfWeek: this.moment.weekdaysMin(), //array of days - see moment documentation for details
                    monthNames: this.moment.monthsShort(), //array of month names - see moment documentation for details
                    firstDay: 1 //ISO first day of week - see moment documentation for details
                },
                logs: [],
                autoRefresh: 'false',
                logsLoading: false,
                fields: [
                    {
                        key: 'reported_at',
                        label: 'Erhalten am',
                        'class': 'col-reported_at',
                    },
                    {
                        key: 'from',
                        label: 'Absender',
                        'class': 'col-from',
                    },
                    {
                        key: 'to',
                        label: 'Empfänger',
                        'class': 'col-to',
                    },
                    {
                        key: 'client',
                        label: 'Client',
                        'class': 'col-client',
                    },
                    {
                        key: 'status',
                        label: 'Status',
                        sortable: true,
                    },
                ],
                detailedRow: [],
            }
        },
        filters: {
            date: function(value) {
                if (!value) return '';

                return value.format('YYYY/MM/DD HH:mm');
            }
        },
        methods: {
            loadWhoisData() {
                if (this.detailedRow.client_ip) {
                    this.whoisLoading = true;

                    axios.post('/whois', {
                        'client_ip': this.detailedRow.client_ip,
                    }).then((response) => {
                        this.whoisOrganisation = response.data.data.organization;
                        this.whoisAbuseContact = response.data.data.abuse;

                        this.whoisLoading = false;
                    }).catch((error) => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.$notify({
                                    title: error.response.data.errors.payload[0],
                                    type: 'error'
                                });
                            } else {
                                this.$notify({
                                    title: error.response.data.message,
                                    type: 'error'
                                });
                            }
                        }

                        this.whoisLoading = false;
                    });
                }
            },
            blacklist(row) {
                axios.post('/access', {
                    client_type: 'client_ipv4',
                    client_payload: row.client_ip,
                    sender_type: 'mail_from_address',
                    sender_payload: row.from,
                    action: 'reject',
                    description: 'Über Mail Log hinzugefügt.',
                }).then((response) => {
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.$notify({
                                title: error.response.data.errors.payload[0],
                                type: 'error'
                            });
                        } else {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }
                    }
                });
            },
            whitelist(row) {
                axios.post('/access', {
                    client_type: 'client_ipv4',
                    client_payload: row.client_ip,
                    sender_type: 'mail_from_address',
                    sender_payload: row.from,
                    action: 'ok',
                    description: 'Über Mail Log hinzugefügt.',
                }).then((response) => {
                    this.$notify({
                        title: response.data.message,
                        type: 'success'
                    });
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.$notify({
                                title: error.response.data.errors.payload[0],
                                type: 'error'
                            });
                        } else {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }
                    }
                });
            },
            currentLogs() {
                this.currentStart = this.moment().subtract(1, 'hours');
                this.currentEnd = this.moment().add(1, 'minutes');
                this.dateRange.startDate = this.currentStart.format('YYYY/MM/DD HH:mm');
                this.dateRange.endDate = this.currentEnd.format('YYYY/MM/DD HH:mm');
                this.currentPage = 1;
                this.getLogs();
            },
            changePage(data) {
                this.currentPage = data;
                this.getLogs();
            },
            getLogs() {
                this.logsLoading = true;

                axios.get('/server/log', {
                    params: {
                        startDate: this.currentStart.format('YYYY/MM/DD HH:mm'),
                        endDate: this.currentEnd.format('YYYY/MM/DD HH:mm'),
                        currentPage: this.search ? 1: this.currentPage, // Reset page to 1 on search requests
                        perPage: this.perPage,
                        search: this.search,
                        status: this.mailStatusSelected,
                    }
                }).then((response) => {
                    this.logs = Object.values(response.data.data);
                    this.totalRows = response.data.total;
                    this.from = response.data.from;
                    this.to = response.data.to;

                    this.logsLoading = false;
                }).catch((error) => {
                    console.log(error)

                    if (error.response) {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            this.$notify({
                                title: error.response.data.message,
                                type: 'error'
                            });
                        }
                    } else {
                        this.$notify({
                            title: 'Unbekannter Fehler',
                            type: 'error'
                        });
                    }

                    this.logsLoading = false;
                });
            },
            showModal(record, index) {
                this.$refs.mailLogModal.show();
                this.detailedRow = record;
            },
            updateSelectedDate(data) {
                this.currentStart = this.moment(data.startDate);
                this.currentEnd = this.moment(data.endDate);

                this.getLogs();
            },
            getStatusClass(data) {
                switch(data.item.status) {
                    case 'sent':
                        return 'text-success';
                    case 'reject':
                        return 'text-danger';
                    case 'deferred':
                        return 'text-warning';
                    default:
                        return 'text-secondary';
                }
            }
        }
    }
</script>

<style>
    .stepwizard-step-subtitle {
        margin-top: 10px;
    }
    .stepwizard-row {
        display: table-row;
    }
    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }
    .stepwizard-row:before {
        top: 55px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-order: 0;
    }
    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
        width: 375px;
    }
    .btn-circle {
        width: 120px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

    .col-reported_at {
        max-width: 90px;
    }

    .col-from {
        max-width: 190px;
    }

    .col-to {
        max-width: 190px;
    }

    .col-client {
        max-width: 190px;
    }
</style>
