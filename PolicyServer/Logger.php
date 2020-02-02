<?php

class Logger
{
    public function log(string $line, $priority = LOG_INFO)
    {
        openlog('syslog', LOG_PID, LOG_MAIL);
        syslog($priority, 'Betterprotect Policy Server: ' . $line);
        closelog();
    }
}