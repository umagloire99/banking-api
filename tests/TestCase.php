<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh --seed');
        Artisan::call('passport:install');
        $this->withHeaders([
            'Api-Key' => config('app.api_key'),
            'Accept' => 'application/json'
        ]);
    }

    /**
     * generate customer token
     * @param $user
     * @return string
     */
    protected function getBearerToken($user): string
    {
        return $user->createToken('USER_SIDE', ['user-side'])->accessToken;
    }
}
