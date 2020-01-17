<?php

return [
    /*
     * The postfix restriction class which combines client and sender checks.
     *
     * Postfix checks every mail against the client_access, if a combination rule is defined,
     * the client access rule will have the configured option as action. Postfix will then
     * use the action to check the sender access.
     *
     */
    'client_sender_combination_restriction_class' => 'check_sender_client_combination'
];