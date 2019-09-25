<template>
    <b-modal title="Sind Sie sicher?" hide-backdrop cancel-disabled ok-disabled id="are-you-sure-modal" ref="areYouSureModal">
        <p class="text-danger"><i class="fas fa-exclamation"></i> Der Eintrag wird gelöscht.</p>

        <template v-slot:modal-footer>
            <div class="w-100">
                <b-button
                    variant="danger"
                    class="float-right"
                    @click="areYouSureModalYes">
                    Ja
                </b-button>
                <b-button
                    variant="secondary"
                    class="float-right  mr-1"
                    @click="areYouSureModalNo">
                    Nein
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
