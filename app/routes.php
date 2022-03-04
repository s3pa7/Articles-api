<?php


declare(strict_types=1);

use Slim\App;
use App\Controller\ArticleController;
use Slim\Routing\RouteCollectorProxy;
use \App\Action\Docs\SwaggerUiAction;
return function (App $app) {


    $app->group('/api', function (RouteCollectorProxy $app) {
        $app->get('/getArticles', ArticleController::class);
    });

    $app->group('/api', function (RouteCollectorProxy $app) {
        $app->post('/createArticle', ArticleController::class . ':createArticle');
    });

    $app->group('/api', function (RouteCollectorProxy $app) {
        $app->get('/deleteArticle/{id}', ArticleController::class . ':deleteArticle');
    });

   $app->group('/api', function (RouteCollectorProxy $app) {
        $app->post('/editArticle', ArticleController::class . ':editArticle');
    });

};
