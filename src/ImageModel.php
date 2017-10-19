<?php

namespace Sithu\ImageHelper;

class ImageModel
{
	public $image_name;
	public $image_extension;
    
	// public function __construct( $image_extension)
 //    {
    	
 //    }

 //    public function __construct2( $image_name , $image_extension )
 //    {
 //    	$this->image_extension = $image_extension;
 //    	$this->image_name = $image_name;
 //    }

    public function setImageProperties($image_name,$image_extension)
    {
    	$this->image_extension = $image_extension;
        $this->image_name = $image_name;
    }

       
}