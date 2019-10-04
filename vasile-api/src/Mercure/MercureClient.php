<?php

namespace App\Mercure;

use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;

/**
 * Class MercureProvider
 * @package App\Mercure
 */
class MercureClient
{
    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * Provider constructor.
     */
    public function __construct()
    {
        $this->publisher = new Publisher(
            'http://internal-mercure/hub',
            new StaticJwtProvider('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyJmb28iLCJiYXIiXSwicHVibGlzaCI6WyJmb28iXX19.afLx2f2ut3YgNVFStCx95Zm_UND1mZJ69OenXaDuZL8')
        );
    }

    public function update($topic, $data)
    {
        ($this->publisher)(new Update($topic, $data));
    }
}
