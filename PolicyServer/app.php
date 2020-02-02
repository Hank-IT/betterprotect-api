<?php

require 'BetterprotectErrorException.php';
require 'Logger.php';
require 'RequestHandler.php';
require 'Responder.php';

$f = fopen( 'php://stdin', 'r' );

while($line = fgets($f)) {
    // Match and store options
    if(preg_match("/([^=]+)=(.*)\n/", $line)) {
        $line = explode('=', $line);

        $options[$line[0]] = str_replace("\n", '', $line[1]);
    } else {
        // Break loop
        fclose($f);

        try {
            if (empty($options)) {
                throw new BetterprotectErrorException('No options received from postfix.', RequestHandler::POSTFIX_ACTION_DEFER);
            }

            $action = (new RequestHandler($options))->getResponse();
        } catch (Throwable $exception) {
            if (method_exists($exception, 'getPostfixAction')) {
                $action = $exception->getPostfixAction();
            } else {
                $action = RequestHandler::POSTFIX_ACTION_DEFER;
            }
        }

        (new Logger)->log('Response ' . $action, LOG_INFO);

        print (new Responder($action))->respond();

        exit(0);
    }
}
