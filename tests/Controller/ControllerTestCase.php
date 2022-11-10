<?php

namespace App\Tests\Controller;

use App\Tests\DatabaseDependantTestCase;

class ControllerTestCase extends DatabaseDependantTestCase
{
    protected function requestGet(string $url): void
    {
        $this->client->request('GET', $url);
    }

    protected function requestPost(string $url, array $requestContent): void
    {
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestContent),
        );
    }
}