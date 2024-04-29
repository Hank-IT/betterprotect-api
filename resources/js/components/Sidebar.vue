<template>
    <!-- Sidebar -->
    <div class="row" id="body-row">
        <div id="sidebar-container" class="sidebar-expanded d-md-block">
            <ul class="list-group sticky-top ">
                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>{{ translate('misc.menu.main') }}</small>
                </li>
                <router-link :to="{ name: 'server.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.server') }}</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'server.log-opensearch' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.log_viewer') }}</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'server.log' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.log_viewer') }} (veraltet)</span>
                    </div>
                </router-link>

                <router-link :to="'#'" :class="{'router-link-active': subIsActive('/charts')}" v-b-toggle.charts v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.charts') }}</span>
                        <span class="ml-auto when-closed"><i class="fas fa-chevron-down"></i></span>
                        <span class="ml-auto when-opened"><i class="fas fa-chevron-right"></i></span>
                    </div>
                </router-link>

                <b-collapse id="charts" v-bind="chartsVisible" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])">
                    <div class="sidebar-submenu">
                        <router-link :to="{ name: 'charts.mail-flow' }" exact class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed">{{ translate('misc.menu.mail_flow') }}</span>
                        </router-link>
                    </div>
                </b-collapse>

                <router-link :to="{ name: 'server.queue' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.queue') }}</span>
                    </div>
                </router-link>

                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>Policy</small>
                </li>

                <router-link :to="{ name: 'access.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.rules') }}</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'recipient.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.recipients') }}</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'transport.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.transport') }}</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'relay-domain.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.relay_domains') }}</span>
                    </div>
                </router-link>

                <router-link :to="'#'" :class="{'router-link-active': subIsActive('/milter')}" v-b-toggle.milter v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.milter') }}</span>
                        <span class="ml-auto when-closed"><i class="fas fa-chevron-down"></i></span>
                        <span class="ml-auto when-opened"><i class="fas fa-chevron-right"></i></span>
                    </div>
                </router-link>

                <b-collapse id="milter" v-bind="milterVisible" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])">
                    <div class="sidebar-submenu">
                        <router-link :to="{ name: 'milter.index' }" exact class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed">{{ translate('misc.menu.definitions') }}</span>
                        </router-link>
                        <router-link :to="{ name: 'milter.exception.index' }" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed">{{ translate('misc.menu.exceptions') }}</span>
                        </router-link>
                    </div>
                </b-collapse>

                <li v-if="$auth.check(['administrator'])" class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>System</small>
                </li>

                <router-link :to="{ name: 'user.index' }" v-if="$auth.check(['administrator'])" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.user') }}</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'ldap.index' }" v-if="$auth.check(['administrator'])" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.ldap') }}</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'settings.index' }" v-if="$auth.check(['administrator'])" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">{{ translate('misc.menu.settings') }}</span>
                    </div>
                </router-link>
            </ul>
        </div>

        <slot></slot>
    </div>
</template>

<script>
    export default {
        methods: {
            subIsActive(input) {
                const paths = Array.isArray(input) ? input : [input];
                return paths.some(path => {
                    return this.$route.path.indexOf(path) === 0 // current path starts with this path string
                })
            }
        },
        computed: {
            milterVisible() {
                if (this.subIsActive('/milter')) {
                    return { visible: true }
                }
            },
            chartsVisible() {
                if (this.subIsActive('/charts')) {
                    return { visible: true }
                }
            }
        }
    }
</script>

<style>
    .collapsed > div > .when-opened,
    :not(.collapsed) > div > .when-closed {
        display: none;
    }

    /* Submenu item*/
    #sidebar-container .list-group .sidebar-submenu a {
        height: 45px;
        padding-left: 30px;
    }
    .sidebar-submenu {
        font-size: 0.9rem;
    }
</style>
