<?php

namespace App\Services\BetterprotectPolicy\Enums;

enum PolicyDecisions: string
{
    case POLICY_DENIED = 'Access denied by bp-policy';
    case POLICY_GRANTED = 'Access granted by bp-policy';
}
