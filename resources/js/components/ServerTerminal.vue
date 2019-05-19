<script>
    export default {
        props: ['server'],
        data() {
            return {
                serverTerminalForm: {},
                public_key: null,
                private_key: null,
                errors: {},
            }
        },
        methods: {
            storeTerminalSettings() {
                axios.post('/server/' + this.server.id + '/terminal', this.serverTerminalForm)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.$refs.serverTerminalModal.hide()
                    }).catch((error) => {
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

                        // handle error
                        console.log(error);
                    });
            },
            modalShown() {
                axios.get('/server/' + this.server.id + '/terminal')
                    .then((response) => {
                        this.serverTerminalForm = response.data.data;
                    }).catch(function (error) {
                        console.log(error);
                    });

                this.$refs.user.focus();

                this.errors = [];
            },
        }
    }
</script>