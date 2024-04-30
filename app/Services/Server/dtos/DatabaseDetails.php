<?php

namespace App\Services\Server\dtos;

use Database\Factories\DatabaseDetailsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DatabaseDetails
{
    use HasFactory;

    protected string $hostname;
    protected string $database;
    protected ?string $username;
    protected ?string $password;
    protected int $port;

    public function __construct(array $data)
    {
        $this->hostname = $data['hostname'];
        $this->database = $data['database'];
        $this->username = $data['username'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->port = $data['port'] ?? 3306;
    }

    public static function make(
        string $hostname,
        string $database,
        ?string $username,
        ?string $password,
        int $port = 3306,
    ): static {
        return new static([
            'hostname' => $hostname,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'port' => $port,
        ]);
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    protected static function newFactory()
    {
        return DatabaseDetailsFactory::new();
    }
}
