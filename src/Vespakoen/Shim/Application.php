<?php namespace Vespakoen\Shim;

use Illuminate\Foundation\Application as IlluminateApplication;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Config\FileLoader;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Exception\ExceptionServiceProvider;
use Illuminate\Foundation\EnvironmentDetector;
use Illuminate\Foundation\ProviderRepository;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Illuminate\Support\Contracts\ResponsePreparerInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Application extends IlluminateApplication {

	/**
	 * Run the application and send the response.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @return void
	 */
	public function run(SymfonyRequest $request = null)
	{
		$request = $request ?: $this['request'];

		$response = with($stack = $this->getStackedClient())->handle($request);

		if($response->getStatusCode() !== 404 || $this['config']['app.debug_errors'])
		{
			$response->send();

			$stack->terminate($request, $response);

			$this->shutdown();
		}

		return false;
	}

}
