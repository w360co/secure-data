<?php

namespace W360\ImageStorage\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use W360\ImageStorage\Models\ImageStorage;
use W360\ImageStorage\Models\UserTest;
use W360\ImageStorage\Tests\TestCase;

class SaveImageTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function save_image_in_database_with_factory(){
        $user = factory(UserTest::class)->create();
        $image = factory(ImageStorage::class)->create();
        $this->assertCount(1, ImageStorage::all(), 'Database Images Is Empty');
        $this->assertCount(1, UserTest::all(), 'Database Users Is Empty');
    }

}