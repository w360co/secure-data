<?php

namespace W360\ImageStorage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use W360\ImageStorage\Models\ImageStorage;

class ImageService
{
    /**
     * @param UploadedFile $image
     * @param $storage
     * @param $model
     * @return mixed
     */
    public function save(UploadedFile $image, $storage, &$model)
    {
        $disk = Storage::disk($storage);
        $manager = new ImageManager();

        $fileName = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        $img = $manager->make($image->getRealPath());
        $disk->put($storage . "/" . $fileName, $img->encode('webp'));
        $this->sizesImages($fileName, $disk, $storage, function ($sizePath, $width, $height, $quality) use ($img) {
            $img->fit($width, $height)->encode('webp', $quality);
            $img->save($sizePath);
        });

        $imageStore = ImageStorage::firstOrCreate([
            'name' => $fileName,
            'storage' => $storage,
            'author' => (Auth::check() ? Auth::user()->username : null),
            'model_type' => get_class($model),
            'model_id' => $model->id
        ]);

        $model->image_storage_id = $imageStore->id;

        return $imageStore;
    }


    /**
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $image = ImageStorage::where('id', $id)->first();
        if ($image) {
            $disk = Storage::disk($image->storage);
            $paths = [];
            $this->sizesImages($image->name, $disk, $image->storage, function ($sizePath) use ($paths) {
                $paths[] = $sizePath;
            });
            if (!empty($paths))
                return $disk->delete($paths);
        }
        return true;
    }

    /**
     * @param $imageName
     * @param $disk
     * @param $storage
     * @param \Closure $function
     */
    private function sizesImages($imageName, $disk, $storage, \Closure $function)
    {
        $sizes = config('image-storage.sizes');
        if ($disk->exists($storage . "/" . $imageName)) {
            foreach ($sizes as $sizeName => $widthHeight) {
                list($width, $height) = $widthHeight;
                $quality = config('image-storage.quality');
                $sizePath = $storage . "/" . $sizeName . "/" . $imageName;
                if (isset($quality[$sizeName]) && is_numeric($quality[$sizeName])) {
                    $function($sizePath, $width, $height, $quality[$sizeName]);
                }
            }
        }
    }
}
