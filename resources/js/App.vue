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
</style>
