<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('task', function ($user) {
    return true;
});

Broadcast::channel('monitoring', function ($user) {
    return true;
});
