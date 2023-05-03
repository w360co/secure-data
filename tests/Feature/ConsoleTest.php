<?php

namespace W360\SecureData\Tests\Feature;

use W360\SecureData\Tests\TestCase;

class ConsoleTest extends TestCase
{

    /**
     * @test
     */
    public function get_generate_key_in_console()
    {
        $this->artisan('secure:key --bytes=16')
            ->expectsOutputToContain('SECURE_SECRET_KEY=')
            ->assertExitCode(0);
    }


    /**
     * @test
     */
    public function get_generate_encrypt_database()
    {
        $this->artisan('secure:encrypt "W360\\\SecureData\\\Models\\\User"')
            ->assertExitCode(0);
    }





}