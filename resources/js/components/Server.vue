<template>
    <div class="card mb-2">
        <div class="card-header"><i class="fas fa-server fa-fw"></i> {{ server.hostname }} ({{ translate('features.policy.last-installation') }}: {{ server.last_policy_install }})</div>
        <div class="card-body">
            <p class="card-text mb-0">{{ server.description }}</p>

            <template v-if="server.active">
                <server-schema-check :server="server" :database="'postfix_db'" v-if="server.postfix_feature_enabled"></server-schema-check>
                <server-schema-check :server="server" :database="'log_db'" v-if="server.log_feature_enabled"></server-schema-check>
            </template>
            <p v-else>{{ translate('features.server.disabled') }}</p>

            <div class="mt-1">
                <button class="btn btn-secondary" :disabled="! $auth.check(['editor', 'administrator'])" @click="openModal"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger" :disabled="! $auth.check(['editor', 'administrator'])" @click="deleteRow"><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>

        <are-you-sure-modal v-on:answered-yes="deleteServer" v-on:answered-no="row = null"></are-you-sure-modal>
    </div>
</template>

<script>
    export default {
        props: ['server'],
        data() {
            return {
                message: null,
            }
        },
        methods: {
            deleteRow() {
                this.$bvModal.show('are-you-sure-modal');
            },
            deleteServer() {
                axios.delete('/server/' + this.server.id)
                    .then((response) => {
                        this.$notify({
                            title: response.data.message,
                            type: 'success'
                        });

                        this.$emit('server-deleted', this.server.id);
                    }).catch((error) => {
                        console.log(error);
                    });
            },
            openModal() {
                this.$emit('edit-server', this.server.id);
            }
        }
    }
</script>
