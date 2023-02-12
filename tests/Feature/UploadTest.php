<?php

namespace W360\ImageStorage\Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use W360\ImageStorage\Facades\ImageST;
use W360\ImageStorage\Models\UserTest;
use W360\ImageStorage\Tests\TestCase;

class UploadTest extends TestCase
{

    use DatabaseMigrations, RefreshDatabase;

    /**
     * @test
     */
    public function upload_and_save_image_to_storage(){

        $storage = 'avatars';
        Storage::fake($storage);

        $upload = UploadedFile::fake()->image('avatar.jpg');
        $userTest = factory(UserTest::class)->create();
        $image = ImageST::save($upload, $storage, $userTest);
        $userTest->save();

        Storage::disk($image->storage)->assertExists($image->name);
        $this->assertEquals($userTest->id, $image->model_id,'Id Model Test Not Equal Model Id' );
        $this->assertEquals($userTest->image_storage_id, $image->id, 'Id Ralation Image Storage Id Not Equal To Image Store Id');
    }


    public function upload_and_save_array_of_images_to_storage(){
        $this->assertTrue(true);
    }

}