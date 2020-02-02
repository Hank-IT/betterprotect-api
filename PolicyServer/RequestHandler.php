<?php

class RequestHandler {
    const CONFIG = 'app.json';

    const POSTFIX_ACTION_DEFER = 'defer';
    const POSTFIX_ACTION_DUNNO = 'dunno';

    protected $data;

    protected $config;

    protected $logger;

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->logger = new Logger;
    }

    public function getResponse()
    {
        $queryData = $this->queryDatabase($this->readConfig()['database']);

        // Continue processing on no match
        if (empty($queryData)) {
            return self::POSTFIX_ACTION_DUNNO;
        }

        // Return configured action on match
        if ($queryData['client_payload'] == $this->data[$queryData['client_type']]) {
            $this->logger->log('Response ' . $queryData['action']);
            return $queryData['action'];
        }
    }

    protected function queryDatabase(array $config) {
        try {
            $connection = new PDO("mysql:host=" . $config['hostname'] . ";dbname=" . $config['database'], $config['username'], $config['password']);

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new BetterprotectErrorException('Database connection failed', self::POSTFIX_ACTION_DEFER);
        }

        $stmt = $connection->prepare("SELECT client_type, client_payload, action FROM client_sender_access WHERE client_payload = :client_name or client_payload = :reverse_client_name or client_payload = :client_address LIMIT 1");
        $stmt->execute([
            'client_name' => $this->data['client_name'],
            'reverse_client_name' => $this->data['reverse_client_name'],
            'client_address' => $this->data['client_address'],
        ]);

        $row = $stmt->fetch();

        $connection->close();

        return $row;
    }

    protected function readConfig()
    {
        if (! file_exists(self::CONFIG)) {
            throw new BetterprotectErrorException('Configuration file unavailable', self::POSTFIX_ACTION_DEFER);
        }

        $config = json_decode(file_get_contents(self::CONFIG), true);

        if ($config === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new BetterprotectErrorException('Configuration file invalid', self::POSTFIX_ACTION_DEFER);
        }

        return $config;
    }
}