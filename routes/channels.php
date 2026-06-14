<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{projectId}', function ($user, $projectId) {
    return true;
});