<template>
    <!-- Sidebar -->
    <div class="row" id="body-row">
        <div id="sidebar-container" class="sidebar-expanded d-md-block">
            <ul class="list-group sticky-top ">
                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>Hauptmenü</small>
                </li>
                <router-link :to="{ name: 'server.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Server</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'server.log' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Log Viewer</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'server.queue' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Queue</span>
                    </div>
                </router-link>

                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>Policy</small>
                </li>

                <router-link :to="{ name: 'access.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Regeln</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'recipient.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Empfänger</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'transport.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Transport</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'relay-domain.index' }" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Relay Domänen</span>
                    </div>
                </router-link>

                <router-link :to="'#'" v-b-toggle.milter v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Milter</span>
                        <span class="ml-auto when-closed"><i class="fas fa-chevron-down"></i></span>
                        <span class="ml-auto when-opened"><i class="fas fa-chevron-right"></i></span>
                    </div>
                </router-link>

                <b-collapse id="milter" v-if="$auth.check(['readonly', 'authorizer', 'editor', 'administrator'])">
                    <div class="sidebar-submenu">
                        <router-link :to="{ name: 'milter.index' }" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed">Definitionen</span>
                        </router-link>
                        <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed">Ausnahmen</span>
                        </a>
                    </div>
                </b-collapse>

                <li v-if="$auth.check(['administrator'])" class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>System</small>
                </li>

                <router-link :to="{ name: 'user.index' }" v-if="$auth.check(['administrator'])" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Benutzer</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'ldap.index' }" v-if="$auth.check(['administrator'])" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">LDAP</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'settings.index' }" v-if="$auth.check(['administrator'])" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Einstellungen</span>
                    </div>
                </router-link>
            </ul>
        </div>

        <slot></slot>
    </div>
</template>

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