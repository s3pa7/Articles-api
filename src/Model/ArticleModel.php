<?php


declare(strict_types=1);

namespace App\Model;

use App\Lib\Database\DB;
use App\Lib\Misc\Constants;
use Psr\Container\ContainerInterface;

/**
 * Class FaxSendingModel
 */
class ArticleModel
{

    private DB $conn;
    /**
     * @param DB $conn
     */
    public function __construct(ContainerInterface $container){

        $this->conn  = $container->get('db');
    }


    public function loadArticle($params)
    {
        $limit = $params['page_size'];
        if ($params['page_number'] != 0){
            $offset = ($limit * $params['page_number']) - $limit;
        }else {
            $offset = $params['page_number'];
        }

        try {
            $query = "SELECT 
                               id , title , description , content , date_added
                            FROM " . Constants::TABLE_ARTICLES . " WHERE status = 1  
                            LIMIT ". $limit ."  OFFSET ". $offset ."
                            ";

            $statement = $this->conn->prepare($query);
            $statement->execute();

            return $statement->fetchAll();
        } catch (\PDOException $e) {
            print_r($e);
            die();
            throw new \Exception("Can't fetch needed data: " . $e->getMessage());
        }
    }


    /**
     * @param string $title
     * @param string $descriptions
     * @param string $content
     * @return mixed
     */
    public function createArticle(array $postData)
    {

        try {
            $query = "INSERT INTO  " . Constants::TABLE_ARTICLES . " SET  title = :title , description = :description,  content = :content , date_added = NOW()";


            $statement = $this->conn->prepare($query)
                ->execute([
                    ":title"     => trim($postData['title']),
                    ":description" => trim($postData['description']),
                    ":content" => trim($postData['content']),
                ]);
            return $statement;
        } catch (\PDOException $e) {
            throw new \Exception("Can't fetch needed data: " . $e->getMessage());
        }
    }
    /**
     * @param string $id
     * @return bool
     */
    # This is soft delete
    public function deleteArticle($id)
        {

            try {
                $query = "UPDATE  " . Constants::TABLE_ARTICLES . " SET  status = 0 WHERE id = :id";

                $statement = $this->conn->prepare($query)
                    ->execute([
                        ":id" => $id,
                    ]);

                return $statement;

            } catch (\PDOException $e) {
                throw new \Exception("Can't fetch needed data: " . $e->getMessage());
            }
        }

        public function haveArticle ($id) {
            try {
                $query = "SELECT 
                                id , title , description , content
                            FROM " . Constants::TABLE_ARTICLES . " WHERE status = 1 AND id = :id
                            ";

                $statement = $this->conn->prepare($query);

                $statement->execute([
                    ":id" => $id,
                ]);

                return $statement->fetch();

            } catch (\PDOException $e) {
                throw new \Exception("Can't fetch needed data: " . $e->getMessage());
            }

        }
        public function updateArticle(array $articleData) {


            try {
                $query = "UPDATE  " . Constants::TABLE_ARTICLES . " SET  title = :title , description = :description,  content = :content , date_added = NOW() WHERE id = :id";

                $statement =   $this->conn->prepare($query)
                    ->execute([
                        ":title"     => $articleData['title'],
                        ":description" => $articleData['description'],
                        ":content" => $articleData['content'],
                        ":id" => $articleData['id'],
                    ]);
              return $statement;
            } catch (\PDOException $e) {
                throw new \Exception("Can't fetch needed data: " . $e->getMessage());
            }

        }
}
