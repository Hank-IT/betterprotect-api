import Vue from 'vue';
import Router from 'vue-router';
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
import ServerWizardAmavisForm from "./components/ServerWizard/ServerWizardAmavisForm";
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
import ServerUpdateAmavisForm from "./components/ServerUpdate/ServerUpdateAmavisForm";

Vue.prototype.moment = moment;

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
import faTrash from '@fortawesome/fontawesome-free-solid/faTrash';
import faLock from '@fortawesome/fontawesome-free-solid/faLock';
import faUnlock from '@fortawesome/fontawesome-free-solid/faUnlock';

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

/*
 * Axios
 */
window.axios = require('axios');
import VueAxios from 'vue-axios'
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
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
Vue.use(Notifications);

/*
 * Vue Form Wizard
 */
Vue.use(VueFormWizard);

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
                auth: true
            }
        },
        {
            path: '/server-log',
            name:'server.log',
            component: ServerLog,
            meta: {
                auth: true
            }
        },
        {
            path: '/server-queue',
            name:'server.queue',
            component: ServerQueue,
            meta: {
                auth: true
            }
        },
        {
            path: '/access',
            name: 'access.index',
            component: AccessIndex,
            meta: {
                auth: true
            }
        },
        {
            path: '/recipient',
            name: 'recipient.index',
            component: RecipientIndex,
            meta: {
                auth: true
            }
        },
        {
            path: '/transport',
            name: 'transport.index',
            component: TransportIndex,
            meta: {
                auth: true
            }
        },
        {
            path: '/relay-domain',
            name: 'relay-domain.index',
            component: RelayDomainIndex,
            meta: {
                auth: true
            }
        },
        {
            path: '/user',
            name: 'user.index',
            component: UserIndex,
            meta: {
                auth: true
            }
        },
        {
            path: '/ldap',
            name: 'ldap.index',
            component: LdapDirectoryIndex,
            meta: {
                auth: true
            }
        },
        {
            path: '/settings',
            name: 'settings.index',
            component: SettingsIndex,
            meta: {
                auth: true
            }
        }

    ],
});

Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
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
Vue.component('ServerWizardAmavisForm', ServerWizardAmavisForm);
Vue.component('ServerSchemaCheck', ServerSchemaCheck);
Vue.component('ServerUpdateServerForm', ServerUpdateServerForm);
Vue.component('ServerUpdatePostfixForm', ServerUpdatePostfixForm);
Vue.component('ServerUpdateConsoleForm', ServerUpdateConsoleForm);
Vue.component('ServerUpdateLoggingForm', ServerUpdateLoggingForm);
Vue.component('ServerUpdateAmavisForm', ServerUpdateAmavisForm);

App.router = Vue.router;

new Vue(App).$mount('#app');
