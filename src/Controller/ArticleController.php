<?php
declare(strict_types = 1);

namespace App\Controller;

use Monolog\Logger;
use App\Lib\Misc\Helper;
use App\Lib\Database\DB;
use App\Lib\Misc\Constants;
use App\Model\ArticleModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



/**
 * Class ArticleController
 */
class ArticleController
{



    /**
     * @var DB|\db|mixed
     */
    private DB $db;



    /**
     * @var ArticleModel
     */
    private ArticleModel $article;


    /**
     * @param  ContainerInterface  $container
     */
    public function __construct(ContainerInterface $container)
    {


        $this->article = new ArticleModel($container);
    }

    /**
     * @param  Request   $request
     * @param  Response  $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response , $args): Response
    {
        $postData  = $request->getQueryParams();



        if (!isset($postData['page_size']) || !isset($postData['page_number']) ) {

            $postData['page_size'] = 10;
            $postData['page_number'] = 0;

        }else if($postData['page_size'] < 0 || $postData['page_number'] < 0){

            $msg = "The parameters is negative!";

            $response->getBody()
                ->write(Helper::responseWith(400, [
                    'text' => $msg
                ]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }else if(!is_numeric($postData['page_size']) || !is_numeric($postData['page_number'])){
            $msg = "The parameters is wrong!";

            $response->getBody()
                ->write(Helper::responseWith(400, [
                    'text' => $msg
                ]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
        $statement = $this->article->loadArticle($postData);

        if(empty($statement)){
            $msg = "empty data";

            $response->getBody()
                ->write(Helper::responseWith(200, [
                    'text' => $msg
                ]));

            return$response->withHeader('Content-Type', 'application/json')
                ->withHeader('Cache-Control', 'no-store')
                ->withStatus(200);
        }else{

        $response->getBody()->write(Helper::responseWith(200,
            $statement));

        return $response->withHeader('Content-Type', 'application/json')
            ->withHeader('Cache-Control', 'no-store')
            ->withStatus(200);

        }


    }
    /**
     * @param  Request   $request
     * @param  Response  $response
     * @return Response
     */

    public function createArticle(Request $request, Response $response): Response
    {
        $postData  = $request->getParsedBody();



        if (!isset($postData['title']) || !isset($postData['description']) || !isset($postData['content']) ) {
            $msg = "Missing required request parameters!";

            $response->getBody()
                ->write(Helper::responseWith(400, [
                    'text' => $msg
                ]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        if($postData['type'] == 'file'){
            $file = 'article.txt';
            $string = '';
            if(file_exists($file)){
                $f = fopen($file, "r") or exit("Unable to open file!");
                // Read line by line until end of file
                while (!feof($f)) {

                    // Make an array using comma as delimiter
                     $arrM = explode(',',fgets($f));

                    $id['id'] = uniqid();
                    array_push($postData, $id);
                    //strore text file row to an array
                    foreach ($arrM as $m) {
                        $string .= $m . ',';
                    }
                    $currSting = '' . uniqid() . ',' . trim($postData['title']) . ',' . trim($postData['content']) .  ',' . trim($postData['content']);
                    $string = $string . $currSting;
                    file_put_contents($file,  $string);
                }

                fclose($f);

            }else {

                $string = '' . uniqid() . ',' . trim($postData['title']) . ',' . trim($postData['content']) .  ',' . trim($postData['content']);

               file_put_contents($file,  $string);
            }
        }

        $statement =  $this->article->createArticle($postData);

        if($statement){
            $msg = "Article is successfully created";

            $response->getBody()
                ->write(Helper::responseWith(200, [
                    'text' => $msg
                ]));
            return $response->withHeader('Content-Type', 'application/json')
                ->withHeader('Cache-Control', 'no-store')
                ->withStatus(200);
        }



    }

    /**
     * @param  Request   $request
     * @param  Response  $response
     * @return Response
     */
    public function deleteArticle(Request $request, Response $response , $args): Response
    {

        if (!isset($args['id']) ) {
            $msg = "Missing required request parameters!";

            $response->getBody()
                ->write(Helper::responseWith(400, [
                    'text' => $msg
                ]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }else if($args['id'] < 0 ){
            $msg = "Missing valid parameters!";

            $response->getBody()
                ->write(Helper::responseWith(400, [
                    'text' => $msg
                ]));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $haveArticle = $this->article->haveArticle($args['id']);

        if(empty($haveArticle)){
            $msg = "This article doest exist";

            $response->getBody()
                ->write(Helper::responseWith(400, [
                    'text' => $msg
                ]));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);

        }else{



            $statement =  $this->article->deleteArticle($args['id']);

            if($statement){
                $msg = "Article is successfully deleted";

                $response->getBody()
                    ->write(Helper::responseWith(200, [
                        'text' => $msg
                    ]));
                return $response->withHeader('Content-Type', 'application/json')
                    ->withHeader('Cache-Control', 'no-store')
                    ->withStatus(200);
            }


        }

    }
    /**
     * @param  Request   $request
     * @param  Response  $response
     * @return Response
     */
    public function editArticle(Request $request, Response $response): Response
    {
         $postData  = $request->getParsedBody();


        $haveArticle = $this->article->haveArticle($postData['id']);

        if(empty($haveArticle)){
            $msg = "This article doest exist";

            $response->getBody()
                ->write(Helper::responseWith(400, [
                    'text' => $msg
                ]));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(400);

        }else{
            $statement =  $this->article->updateArticle($postData);
            if($statement){
                $msg = "Article is successfully update";

                $response->getBody()
                    ->write(Helper::responseWith(200, [
                        'text' => $msg
                    ]));
                return $response->withHeader('Content-Type', 'application/json')
                    ->withHeader('Cache-Control', 'no-store')
                    ->withStatus(200);
            }



        }

    }







}
