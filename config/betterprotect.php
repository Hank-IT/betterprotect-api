<?php

return [

    /*
     * GUID of a group in which membership is required for authentication
     */
    'ldap_login_group' => env('LDAP_LOGIN_GROUP_GUID'),

    /*
     *
     */
    'ldap_query_ignored_domains' => env('LDAP_QUERY_IGNORED_DOMAINS')
];
