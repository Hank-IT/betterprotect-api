<template>
    <div id="app">
        <notifications position="bottom left"/>

        <div id="root">
            <navbar v-if="$auth.check()"></navbar>

            <sidebar v-if="$auth.check()">
                <div class="content-wrapper col py-3">
                    <div class="content" >
                        <router-view></router-view>
                    </div>
                </div>
            </sidebar>

            <div v-else>
                <router-view></router-view>
            </div>

            <policy-store v-if="$auth.check()"></policy-store>

            <app-footer v-if="$auth.check()"></app-footer>
        </div>
    </div>
</template>

<script>
    import Echo from "laravel-echo";

    export default {
        mounted() {
            this.$auth.ready(function() {
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: 'betterprotect',
                    wsHost: window.location.hostname,
                    wsPort: 80,
                    wssPort: 443,
                    wsPath: '/ws',
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                    auth: {
                        headers: {
                            Authorization: 'Bearer ' + this.$auth.token(),
                        },
                    },
                });

                window.Echo.connector.pusher.config.authEndpoint = `${document.head.querySelector('meta[name="base-url"]').content}/broadcasting/auth`;
            });
        }
    }
</script>

<style>
    .content {
        position: absolute;
        width: 99%;
        height: 100%;
        overflow-y: auto;
        padding-bottom: 60px;
    }

    .content > div {
        width: 98%;
        margin-bottom: 80px;
    }

    .content-wrapper {
        margin-top: 2px;
        margin-left: 2px;
        margin-right: 15px;
    }

    body {
        background-color: white;
    }

    .spinner-3x3 {
        width: 3rem;
        height: 3rem;
    }

    .spinner-1x1 {
        width: 1rem;
        height: 1rem;
    }


</style>
