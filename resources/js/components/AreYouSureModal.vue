<template>
    <b-modal :title="translate('misc.are-you-sure')" hide-backdrop cancel-disabled ok-disabled id="are-you-sure-modal" ref="areYouSureModal">
        <p class="text-danger"><i class="fas fa-exclamation"></i> {{ translate('misc.entry-will-be-removed') }}</p>

        <template v-slot:modal-footer>
            <div class="w-100">
                <b-button
                    variant="danger"
                    class="float-right"
                    @click="areYouSureModalYes">
                    {{ translate('misc.yes') }}
                </b-button>
                <b-button
                    variant="secondary"
                    class="float-right  mr-1"
                    @click="areYouSureModalNo">
                    {{ translate('misc.no') }}
                </b-button>
            </div>
        </template>
    </b-modal>
</template>

<script>
    export default {
        methods: {
            areYouSureModalNo() {
                this.$bvModal.hide('are-you-sure-modal');

                this.$emit('answered-no');
            },
            areYouSureModalYes() {
                this.$bvModal.hide('are-you-sure-modal');

                this.$emit('answered-yes');
            },
        }
    }
</script>
