<?php

namespace App\Services\Server\dtos;

use App\Services\Server\Models\Server;
use Database\Factories\SSHDetailsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SSHDetails
{
    use HasFactory;

    protected string $hostname;
    protected string $user;
    protected string $publicKey;
    protected string $privateKey;
    protected int $port;
    protected string $sudoCommand;
    protected string $postqueueCommand;
    protected string $postsuperCommand;

    public function __construct(array $data)
    {
        $this->hostname = $data['hostname'];
        $this->user = $data['user'];
        $this->port = 22;
        $this->publicKey = $data['public_key'];
        $this->privateKey = $data['private_Key'];
        $this->sudoCommand = $data['sudo_command'];
        $this->postqueueCommand = $data['postqueue_command'];
        $this->postsuperCommand = $data['postsuper_command'];
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function getSudoCommand(): string
    {
        return $this->sudoCommand;
    }

    public function getPostqueueCommand(): string
    {
        return $this->postqueueCommand;
    }

    public function getPostsuperCommand(): string
    {
        return $this->postsuperCommand;
    }

    protected static function newFactory()
    {
        return SSHDetailsFactory::new();
    }
}
