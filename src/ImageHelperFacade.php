<?php

namespace Sithu\Adminlte;

use Illuminate\Support\Facades\Facade;

class ImageHelperFacade extends Facade
{
    protected static function getFacadeAccessor() { 
        return 'sithu-image-helper';
    }
}
