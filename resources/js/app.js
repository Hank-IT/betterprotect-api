import Vue from 'vue';
import Router from 'vue-router';
import auth from '@websanova/vue-auth';
import httpAxios from '@websanova/vue-auth/drivers/http/axios.1.x.js';
import authRouter from '@websanova/vue-auth/drivers/router/vue-router.2.x.js';
import authBearer from './DummyAuthDriver';
import App from './App.vue';
window.Pusher = require('pusher-js');
import ServerIndex from './pages/ServerIndex';
import LdapDirectoryIndex from './pages/LdapDirectoryIndex';
import Server from './components/Server';
import AppFooter from './app/Footer';
import Task from './components/Task';
import AccessIndex from './pages/AccessIndex';
import AccessStore from './components/AccessStore';
import TransportIndex from './pages/TransportIndex';
import MilterIndex from './pages/MilterIndex';
import MilterExceptionIndex from './pages/MilterExceptionIndex';
import TransportStoreModal from './components/TransportStoreModal';
import UserIndex from './pages/UserIndex';
import UserStoreUpdateModal from './components/UserStoreUpdateModal';
import LdapDirectoryStoreUpdateModal from './components/LdapDirectoryStoreUpdateModal';
import PolicyStore from './components/PolicyStore';
import QueryLdapRecipients from './components/QueryLdapRecipients';
import Sidebar from './components/Sidebar';
import Navbar from './components/Navbar';
import RecipientIndex from './pages/RecipientIndex';
import SettingsIndex from './pages/SettingsIndex';
import RecipientStore from './components/RecipientStore';
import Login from './components/auth/Login';
import RelayDomainStoreModal from './components/RelayDomainStoreModal';
import ServerWizardServerForm from "./components/ServerWizard/ServerWizardServerForm";
import ServerWizardPostfixForm from "./components/ServerWizard/ServerWizardPostfixForm";
import ServerWizardConsoleForm from "./components/ServerWizard/ServerWizardConsoleForm";
import ServerWizardLoggingForm from "./components/ServerWizard/ServerWizardLoggingForm";
import AreYouSureModal from './components/AreYouSureModal';
import RelayDomainIndex from "./pages/RelayDomainIndex";
import ServerQueue from "./pages/ServerQueue";
import ServerLog from './pages/ServerLog';
import moment from 'moment';
import DateRangePicker from 'vue2-daterange-picker';
import VueFormWizard from 'vue-form-wizard'
import ServerSchemaCheck from "./components/ServerSchemaCheck";
import ServerUpdateServerForm from "./components/ServerUpdate/ServerUpdateServerForm";
import ServerUpdatePostfixForm from "./components/ServerUpdate/ServerUpdatePostfixForm";
import ServerUpdateConsoleForm from "./components/ServerUpdate/ServerUpdateConsoleForm";
import ServerUpdateLoggingForm from "./components/ServerUpdate/ServerUpdateLoggingForm";
import Echo from "laravel-echo";
import LineChart from './components/LineChart';
import MailFlowChartPage from './pages/charts/MailFlow';

Vue.prototype.moment = moment;
Vue.prototype.translate = require('./VueTranslation/Translation').default.translate;

String.prototype.trunc = String.prototype.trunc ||
    function(n){
        return (this.length > n) ? this.substr(0, n-1) + '...' : this;
    };

Vue.use(Router);

/*
 * Fontawesome
 */
import fontawesome from '@fortawesome/fontawesome';
import faUser from '@fortawesome/fontawesome-free-solid/faUser';
import faSignOutAlt from '@fortawesome/fontawesome-free-solid/faSignOutAlt';
import faPlus from '@fortawesome/fontawesome-free-solid/faPlus';
import faTasks from '@fortawesome/fontawesome-free-solid/faTasks';
import faSpinner from '@fortawesome/fontawesome-free-solid/faSpinner';
import faEdit from '@fortawesome/fontawesome-free-solid/faEdit';
import faTrashAlt from '@fortawesome/fontawesome-free-solid/faTrashAlt';
import faServer from '@fortawesome/fontawesome-free-solid/faServer';
import faSync from '@fortawesome/fontawesome-free-solid/faSync';
import faTerminal from '@fortawesome/fontawesome-free-solid/faTerminal';
import faEnvelope from '@fortawesome/fontawesome-free-solid/faEnvelope';
import faKey from '@fortawesome/fontawesome-free-solid/faKey';
import faUpload from '@fortawesome/fontawesome-free-solid/faUpload';
import faArrowsAltV from '@fortawesome/fontawesome-free-solid/faArrowsAltV';
import faPaperPlane from '@fortawesome/fontawesome-free-solid/faPaperPlane';
import faExclamation from '@fortawesome/fontawesome-free-solid/faExclamation';
import faLock from '@fortawesome/fontawesome-free-solid/faLock';
import faUnlock from '@fortawesome/fontawesome-free-solid/faUnlock';
import faChevronUp from '@fortawesome/fontawesome-free-solid/faChevronUp';
import faChevronDown from '@fortawesome/fontawesome-free-solid/faChevronDown';
import faChevronRight from '@fortawesome/fontawesome-free-solid/faChevronRight';

fontawesome.library.add(faUser);
fontawesome.library.add(faSignOutAlt);
fontawesome.library.add(faPlus);
fontawesome.library.add(faTasks);
fontawesome.library.add(faSpinner);
fontawesome.library.add(faEdit);
fontawesome.library.add(faTrashAlt);
fontawesome.library.add(faServer);
fontawesome.library.add(faSync);
fontawesome.library.add(faTerminal);
fontawesome.library.add(faEnvelope);
fontawesome.library.add(faKey);
fontawesome.library.add(faUpload);
fontawesome.library.add(faArrowsAltV);
fontawesome.library.add(faPaperPlane);
fontawesome.library.add(faExclamation);
fontawesome.library.add(faLock);
fontawesome.library.add(faUnlock);
fontawesome.library.add(faChevronUp);
fontawesome.library.add(faChevronRight);
fontawesome.library.add(faChevronDown);

/*
 * Axios
 */
window.axios = require('axios');
import VueAxios from 'vue-axios'
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//let token = document.head.querySelector('meta[name="csrf-token"]');
//window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
window.axios.defaults.baseURL = document.head.querySelector('meta[name="base-url"]').content;
Vue.use(VueAxios, window.axios);

/*
 * Bootstrap
 */
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

/*
 * Notification
 */
import Notifications from 'vue-notification'
import ServerLogOpensearch from "./pages/ServerLogOpensearch.vue";
Vue.use(Notifications);

/*
 * Vue Form Wizard
 */
Vue.use(VueFormWizard);

Vue.mixin({
    methods: {
        connectWebsocket: function() {
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
        }
    }
});

/*
 * Vue
 */
Vue.router = new Router({
    routes: [
        {
            path: '/login',
            name: 'auth.login',
            component: Login,
            meta: {
                auth: false
            }
        },
        {
            path: '/server',
            alias: '/',
            name:'server.index',
            component: ServerIndex,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/server-log',
            name:'server.log',
            component: ServerLog,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: 'server.log-opensearch',
            name: 'server.log-opensearch',
            component: ServerLogOpensearch,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/charts/mail-flow',
            name: 'charts.mail-flow',
            component: MailFlowChartPage,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/server-queue',
            name:'server.queue',
            component: ServerQueue,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/access',
            name: 'access.index',
            component: AccessIndex,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/recipient',
            name: 'recipient.index',
            component: RecipientIndex,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/transport',
            name: 'transport.index',
            component: TransportIndex,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/relay-domain',
            name: 'relay-domain.index',
            component: RelayDomainIndex,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/milter',
            name: 'milter.index',
            component: MilterIndex,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/milter/exception',
            name: 'milter.exception.index',
            component: MilterExceptionIndex,
            meta: {
                auth: ['readonly', 'authorizer', 'editor', 'administrator'],
            }
        },
        {
            path: '/user',
            name: 'user.index',
            component: UserIndex,
            meta: {
                auth: ['administrator'],
            }
        },
        {
            path: '/ldap',
            name: 'ldap.index',
            component: LdapDirectoryIndex,
            meta: {
                auth: ['administrator'],
            }
        },
        {
            path: '/settings',
            name: 'settings.index',
            component: SettingsIndex,
            meta: {
                auth: ['administrator'],
            }
        }

    ],
});

Vue.use(auth, {
    auth: authBearer,
    http: httpAxios,
    router: authRouter,
    rolesKey: 'role',
    refreshData: {
        enabled: false,
    }
});

Vue.component('Server', Server);
Vue.component('AppFooter', AppFooter);
Vue.component('Task', Task);
Vue.component('AccessStore', AccessStore);
Vue.component('PolicyStore', PolicyStore);
Vue.component('QueryLdapRecipients', QueryLdapRecipients);
Vue.component('RecipientStore', RecipientStore);
Vue.component('Sidebar', Sidebar);
Vue.component('Navbar', Navbar);
Vue.component('ServerLog', ServerLog);
Vue.component('DateRangePicker', DateRangePicker);
Vue.component('UserStoreUpdateModal', UserStoreUpdateModal);
Vue.component('LdapDirectoryStoreUpdateModal', LdapDirectoryStoreUpdateModal);
Vue.component('TransportStoreModal', TransportStoreModal);
Vue.component('RelayDomainStoreModal', RelayDomainStoreModal);
Vue.component('AreYouSureModal', AreYouSureModal);
Vue.component('ServerWizardServerForm', ServerWizardServerForm);
Vue.component('ServerWizardPostfixForm', ServerWizardPostfixForm);
Vue.component('ServerWizardConsoleForm', ServerWizardConsoleForm);
Vue.component('ServerWizardLoggingForm', ServerWizardLoggingForm);
Vue.component('ServerSchemaCheck', ServerSchemaCheck);
Vue.component('ServerUpdateServerForm', ServerUpdateServerForm);
Vue.component('ServerUpdatePostfixForm', ServerUpdatePostfixForm);
Vue.component('ServerUpdateConsoleForm', ServerUpdateConsoleForm);
Vue.component('ServerUpdateLoggingForm', ServerUpdateLoggingForm);
Vue.component('LineChart', LineChart);
Vue.component('MailFlowChartPage', MailFlowChartPage);

App.router = Vue.router;

new Vue(App).$mount('#app');
