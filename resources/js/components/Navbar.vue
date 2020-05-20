<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
            <span class="menu-collapsed"><i class="fas fa-envelope"></i> Betterprotect</span>
        </a>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <b-navbar-nav>
                <form class="form-inline my-2 my-lg-0">
                    <button :disabled="! $auth.check(['authorizer', 'editor', 'administrator'])" class="btn btn-success my-2 my-sm-0" v-b-modal.policy-store-modal><i class="fas fa-upload"></i> Policy installieren</button>
                </form>
            </b-navbar-nav>

            <b-navbar-nav class="ml-auto">
                <b-nav-item-dropdown right>
                    <!-- Using button-content slot -->
                    <template slot="button-content">
                        <em>{{ $auth.user().username }}</em>
                    </template>
                    <b-dropdown-item href="#" @click.prevent="logout">Logout</b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </div>
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
