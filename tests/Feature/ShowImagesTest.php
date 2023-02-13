<?php

namespace W360\ImageStorage\Tests\Feature;

use W360\ImageStorage\Tests\TestCase;

class ShowImagesTest extends TestCase
{

    /**
     * @test
     */
    public function get_image_from_storage_with_characters_not_allowed_and_undefined_size(){
        $img = image('myImage.png', 'local$./[pass&88a7q9# Oi-k', 'sx');
        $this->assertEquals('http://localhost/storage/localpass88a7q9-oi-k/sx/myImage.png', $img, 'Url Image thumbnail Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_thumbnail_with_https(){
        $img = image('myImage.png', 'local', 'thumbnail', true);
        $this->assertEquals('https://localhost/storage/local/thumbnail/myImage.png', $img, 'Url Image thumbnail with https Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_thumbnail(){
        $img = image('myImage.png', 'local', 'thumbnail');
        $this->assertEquals('http://localhost/storage/local/thumbnail/myImage.png', $img, 'Url Image thumbnail Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_xs(){
        $img = image('myImage.png','local','xs' );
        $this->assertEquals('http://localhost/storage/local/xs/myImage.png', $img, 'Url Image xs Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_sm(){
        $img = image('myImage.png','local','sm');
        $this->assertEquals('http://localhost/storage/local/sm/myImage.png', $img, 'Url Image sm Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_md(){
        $img = image('myImage.png','local','md');
        $this->assertEquals('http://localhost/storage/local/md/myImage.png', $img, 'Url Image md Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_lg(){
        $img = image('myImage.png','local','lg');
        $this->assertEquals('http://localhost/storage/local/lg/myImage.png', $img, 'Url Image lg Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_xl(){
        $img = image('myImage.png','local','xl');
        $this->assertEquals('http://localhost/storage/local/xl/myImage.png', $img, 'Url Image XL Fail:'.$img);
    }

    /**
     * @test
     */
    public function get_image_from_storage_xxl(){
        $img = image('myImage.png','local','xxl');
        $this->assertEquals('http://localhost/storage/local/xxl/myImage.png', $img, 'Url Image Xxl Fail:'.$img);
    }

}