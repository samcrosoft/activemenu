<?php namespace Samcrosoft\ActiveMenu;

use Illuminate\Support\ServiceProvider;
use Samcrosoft\ActiveMenu\Core\ActiveMenuManager;
use Samcrosoft\ActiveMenu\Facade\Facade;

/**
 * Class ActiveMenuServiceProvider
 * @package Samcrosoft\Activemenu
 */
class ActiveMenuServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('samcrosoft/activemenu');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        // bind the active menu object to the IOC container
        $this->app->bind(Facade::FACADE_NAME, function(){
            return new ActiveMenuManager($this->app['router']);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
