<?php


declare(strict_types=1);

use Slim\App;
use Slim\Psr7\Response;
use App\Lib\Misc\Helper;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

return function (App $app) {

    // Log the requests/responses
    $app->add(function (Request $request, RequestHandler $handler)  {

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();



        $response = $handler->handle($request);



        return $response;
    });

    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Handle some Http exceptions
    $app->add(function (Request $request, RequestHandler $handler)  {

        try {
            return $handler->handle($request);
        } catch (HttpNotFoundException $httpException) {

            $response = new Response();
            $response->getBody()->write(Helper::responseWith(404));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(404);

        } catch (HttpMethodNotAllowedException $httpException) {
            $response = new Response();
            $response->getBody()->write(Helper::responseWith(405));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(405);
        }
    });

    // Add Error Middleware
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler(function (Request $request, Throwable $exception) {


        $response = new Response();
        //$response->getBody()->write(Helper::responseWith(500));
        $response->getBody()->write("File: " . $exception->getFile() . "; Message: " . $exception->getMessage() . "; Line: " . $exception->getLine());

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    });
};
