<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#">
            <span class="menu-collapsed"><i class="fas fa-envelope"></i> {{ translate('misc.app') }}</span>
        </a>
            <b-navbar-nav>
                <form class="form-inline">
                    <button type="button" :disabled="! $auth.check(['authorizer', 'editor', 'administrator'])" class="btn btn-success my-2 my-sm-0" v-b-modal.policy-store-modal><i class="fas fa-upload"></i> {{ translate('features.policy.install') }}</button>
                </form>
            </b-navbar-nav>

            <b-navbar-nav class="ml-auto">
                <b-nav-item-dropdown right>
                    <!-- Using button-content slot -->
                    <template slot="button-content">
                        <em>{{ $auth.user().username }}</em>
                    </template>
                    <b-dropdown-item href="#" @click.prevent="logout">{{ translate('misc.logout') }}</b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
    </nav>
</template>

<script>
    export default {
        methods: {
            logout() {
                if (window.Echo) {
                    window.Echo.disconnect();

                    window.Echo = null;
                }

                this.$auth.logout({
                    makeRequest: true,
                    redirect: {name: 'auth.login'},
                })
            }
        }
    }
</script>
