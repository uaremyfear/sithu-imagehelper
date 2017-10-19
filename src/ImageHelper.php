<?php

namespace Sithu\ImageHelper;

use Sithu\ImageHelper\ImageModel;

use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

class ImageHelper
{
	public $destinationFolder;
    public $destinationThumbnail;
    public $extension;
    public $file;
    public $imageDefaults;
    public $imageName;
    public $imagePath;
    public $thumbHeight;
    public $thumbPrefix;
    public $thumbnailPath;
    public $thumbWidth;

    public function storeImage($fileImage, $imageTypeKey='image-helper')
    {
    	$imageModel = New ImageModel();

    	$imageName = $this->generateRandomString();

    	$imageModel->setImageProperties($imageName,$fileImage->getClientOriginalExtension());

    	$this->setImageDefaultsFromConfig($imageTypeKey);

        $file = $this->getUploadedFile();

        $this->saveImageFiles($file, $imageModel);

        return $imageModel; 
    }

    private function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString . '-' . mt_rand(10,10000);
    }


    public function updateImage($imageName,$imageExtension,$fileImage,$imageTypeKey='image-helper')
    {
    	$this->setImageDefaultsFromConfig($imageTypeKey);

    	$imageModel = New ImageModel( );

    	$imageModel->setImageProperties( $imageName, $imageExtension);

    	$this->deleteExistingImages($imageModel);

    	return $this->storeImage( $fileImage, $imageTypeKey);
    }
    
    /**
     * @param $modelImage
     * hand in the model
     */
    private function deleteExistingImages($modelImage)
    {
         // delete old images before saving new

        $this->deleteImage($modelImage, $this->destinationFolder);

        $this->deleteThumbnail($modelImage, $this->destinationThumbnail);
    }

    Private function deleteImage($modelImage, $destination)
    {

        File::delete(public_path($destination) .
            $modelImage->image_name . '.' .
            $modelImage->image_extension);
    }

    Private function deleteThumbnail($modelImage, $destination)
    {

        File::delete(public_path($destination) . $this->thumbPrefix .
            $modelImage->image_name . '.' .
            $modelImage->image_extension);
    }

    private function getUploadedFile()
    {
        return  $file = Input::file('image');
    }

    private function makeImageAndThumbnail()
    {
        //create instance of image from temp upload

        $image = Image::make($this->file->getRealPath());

        //save image with thumbnail

        $image->save(public_path() . $this->destinationFolder
                . $this->imageName
                . '.'
                . $this->extension)
            ->resize($this->thumbWidth, $this->thumbHeight)
            // ->greyscale()
            ->save(public_path() . $this->destinationThumbnail
                . $this->thumbPrefix
                . $this->imageName
                . '.'
                . $this->extension);
    }

    /**
     * @return bool
     */
    private function newFileIsUploaded()
    {
        return !empty(Input::file('image'));
    }

    private function saveImageFiles(UploadedFile $file, $model)
    {

        $this->setImageFile($file);

        $this->setFileAttributes($model);

        $this->makeImageAndThumbnail();


    }

    private function setImageDefaultsFromConfig($imageTypeKey)
    {
        $imageType = 'image-helper.' . $imageTypeKey;

        $this->imageDefaults = \Config::get($imageType);

        $this->setImageProperties();

    }

    private function setFileAttributes($model)
    {
        $this->imageName = $model->image_name;
        $this->extension = $model->image_extension;

    }

    private function setImageProperties()
    {
        foreach ($this->imageDefaults as $propertyName => $propertyValue){

            if ( property_exists( $this , $propertyName) ){

                $this->$propertyName = $propertyValue;

            }
        }
    }

    private function setImageFile(UploadedFile $file)
    {
        $this->file = $file;
    }
}