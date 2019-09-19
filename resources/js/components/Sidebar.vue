<template>
    <!-- Sidebar -->
    <div class="row" id="body-row">
        <div id="sidebar-container" class="sidebar-expanded d-md-block">
            <ul class="list-group sticky-top ">
                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>Hauptmenü</small>
                </li>
                <router-link :to="{ name: 'server.index' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Server</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'server.log' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Log Viewer</span>
                    </div>
                </router-link>

                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>Policy</small>
                </li>

                <router-link :to="{ name: 'access.index' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Blacklist/Whitelist</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'recipient.index' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Empfänger</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'transport.index' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Transport</span>
                    </div>
                </router-link>

                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>System</small>
                </li>

                <router-link :to="{ name: 'user.index' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Benutzer</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'ldap.index' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">LDAP</span>
                    </div>
                </router-link>

                <router-link :to="{ name: 'settings.index' }" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="menu-collapsed">Einstellungen</span>
                    </div>
                </router-link>

            </ul>
        </div>

        <slot></slot>
    </div>
</template>
