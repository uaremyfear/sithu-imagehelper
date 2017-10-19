<?php

namespace Sithu\ImageHelper;

use Illuminate\Support\ServiceProvider;

class ImageHelperServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind('sithu-image-helper', function() {
			return new ImageHelper;
		});

		// $this->mergeConfigFrom(
		// 	__DIR__ . '/config/main.php', 'sithu-adminlte-main'
		// 	);
	}

	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config' => config_path('')
			]);			
	}
}
