<template>
    <div class="server-schema-check">
        <span :data-server-id="server.id" v-html="message" :class="{ 'text-danger': schemaError, 'text-warning': schemaUpdate, 'text-success': schemaSuccess }"></span>

        <form @submit.prevent="checkSchema" class="inline" v-if="!checkSchemaLoading">
            <button type="submit" class="btn btn-primary btn-sm mb-1"><i class="fas fa-sync"></i></button>
        </form>

        <form @submit.prevent="updateSchema" class="inline" v-if="schemaUpdate">
            <button type="submit" class="btn btn-primary btn-sm mb-1">Aktualisieren</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['server', 'database'],
        created() {
            this.checkSchema();

            window.Echo.private('task')
                .listen('Task', (e) => {
                    let task = e.task;

                    if (task.task === 'migrate-server-db' && task.status === 3) {
                        this.checkSchema();
                    }
                });
        },
        data() {
            return {
                schemaError: false,
                schemaUpdate: false,
                schemaSuccess: false,
                checkSchemaLoading: false,
            }
        },
        methods: {
            checkSchema() {
                this.checkSchemaLoading = true;
                this.schemaError = false;
                this.schemaUpdate = false;
                this.schemaSuccess = false;
                this.message = '<i class="fas fa-spinner fa-spin fa-fw"></i> <i>Schema wird überprüft...</i>';

                axios.get('/server/' + this.server.id + '/schema?database=' + this.database)
                    .then((response) => {
                        let data = response.data;

                        this.message = data.message;

                        if (data.status === 'warning') {
                            this.schemaUpdate = true;
                        } else if(data.status === 'error') {
                            this.schemaError = true;
                        } else {
                            this.schemaSuccess = true;
                        }

                        this.checkSchemaLoading = false;
                    }).catch((error) => {
                    this.schemaError = true;
                    this.checkSchemaLoading = false;
                });
            },
            updateSchema() {
                axios.post('/server/' + this.server.id + '/schema', {
                    database: this.database
                }).then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });
                    }).catch((error) => {
                    if (error.response) {
                        this.$notify({
                            title: error.response.data.message,
                            type: 'error'
                        });
                    }
                });
            }
        }
    }
</script>

<style>
    .server-schema-check {
        height: 35px;
    }
</style>
