<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace humhub\modules\enterprise\modules\solr\engine;

use Yii;
use yii\helpers\VarDumper;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\search\libs\SearchResult;
use humhub\modules\search\libs\SearchResultSet;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\comment\models\Comment;

/**
 * Description of SolrSearch
 *
 * @author Luke
 */
class SolrSearch extends \humhub\modules\search\engine\ZendLuceneSearch
{

    /**
     * @var string hostname
     */
    public $host = 'localhost';

    /**
     * @var int port 
     */
    public $port = 8983;

    /**
     * @var string path e.g. /solr/humhub/
     */
    public $path;

    /**
     * @var string username
     */
    public $username;

    /**
     * @var string basic auth password
     */
    public $password;

    /**
     * Returns the Solarium Solr Client
     * 
     * @return \Solarium\Core\Client\Client
     */
    public function getClient()
    {
        Yii::setAlias('@Solarium', dirname(__DIR__) . '/vendors/solarium/library/Solarium');
        Yii::setAlias('@Symfony/Component/EventDispatcher', dirname(__DIR__) . '/vendors/solarium/vendor/symfony/event-dispatcher/');

        $config = array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $this->host,
                    'port' => $this->port,
                    'path' => $this->path,
                )
            )
        );
        $client = new \Solarium\Core\Client\Client($config);

        if ($this->username != "") {
            $endpoint = $client->getEndpoint();
            $endpoint->setAuthentication($this->username, $this->password);
        }

        return $client;
    }

    /**
     * @inheritdoc
     */
    public function find($keyword, Array $options)
    {

        $options = $this->setDefaultFindOptions($options);
        $keyword = str_replace(array('*', '?', '_', '$'), ' ', mb_strtolower($keyword, 'utf-8'));
        $queryString = (string) $this->buildQuery($keyword, $options);

        $queryString = str_replace('\\', '\\\\', $queryString);

        $client = $this->getClient();
        $query = $client->createQuery($client::QUERY_SELECT);

        $query->setQuery($queryString);
        $query->setRows($options['pageSize']);
        $query->setStart($options['pageSize'] * ($options['page'] - 1));

        if (!empty($options['sortField'])) {
            $query->addSort($options['sortField'], $query::SORT_ASC);
        }

        try {
            $resultset = $client->execute($query);
        } catch (\Exception $ex) {
            Yii::error('Could not execute solr find query:' . $ex->getMessage(), 'search');
            return new SearchResultSet();
        }

        $resultSet = new SearchResultSet();
        $resultSet->total = $resultset->getNumFound();
        $resultSet->pageSize = $options['pageSize'];
        $resultSet->page = $options['page'];

        foreach ($resultset as $document) {
            $result = new SearchResult();
            $result->model = $this->getDocumentField($document, 'model');
            $result->pk = $this->getDocumentField($document, 'pk');
            $result->type = $this->getDocumentField($document, 'type');
            $resultSet->results[] = $result;
        }

        return $resultSet;
    }

    /**
     * Flush complete search index
     */
    public function flush()
    {
        $client = $this->getClient();
        $update = $client->createUpdate();
        $update->addDeleteQuery('*');
        $update->addCommit();
        $result = $client->update($update);
    }

    /**
     * @inheritdoc
     */
    public function add(Searchable $obj)
    {

        $client = $this->getClient();
        // Get Primary Key
        $attributes = $obj->getSearchAttributes();
        $update = $client->createUpdate();

        $doc = $update->createDocument();

        // Add Meta Data fields
        foreach ($this->getMetaInfoArray($obj) as $fieldName => $fieldValue) {
            $doc->setField($fieldName, $fieldValue);
        }

        // Add provided search infos
        foreach ($attributes as $key => $val) {
            $doc->setField($key, $val);
        }


        if (version_compare(Yii::$app->version, '1.2.3', '<')) {
            // Add comments - if record is content
            if ($obj instanceof ContentActiveRecord) {
                $comments = "";
                foreach (Comment::findAll(['object_id' => $obj->getPrimaryKey(), 'object_model' => $obj->className()]) as $comment) {
                    $comments .= " " . $comment->message;
                }
                $doc->comments = $comments;
            }
        } else {
            foreach ($this->getAdditionalAttributes($obj) as $attrName => $attrValue) {
                if (!empty($attrValue)) {
                    $doc->setField($attrName, VarDumper::dumpAsString($attrValue));
                }
            }
        }

        if (\Yii::$app->request->isConsoleRequest) {
            print ".";
        }

        $update->addDocument($doc);
        $update->addCommit();

        try {
            $client->update($update);
        } catch (\Exception $ex) {
            Yii::error('Could not execute solr add query:' . $ex->getMessage(), 'search');
        }
    }

    /**
     * @param Searchable $obj
     */
    public function delete(Searchable $obj)
    {
        $this->deleteRecord($obj->className(), $obj->getPrimaryKey());
    }


    public function deleteRecord($className, $primaryKey)
    {
        // Build Delete Query
        $query = new \ZendSearch\Lucene\Search\Query\MultiTerm();
        $query->addTerm(new \ZendSearch\Lucene\Index\Term($className, 'model'), true);
        $query->addTerm(new \ZendSearch\Lucene\Index\Term($primaryKey, 'pk'), true);

        $query = str_replace('\\', '\\\\', (string) $query);

        $client = $this->getClient();
        $update = $client->createUpdate();
        $update->addDeleteQuery((string) $query);
        $update->addCommit();

        try {
            $client->update($update);
        } catch (\Exception $ex) {
            Yii::error('Could not execute solr delete query:' . $ex->getMessage(), 'search');
        }
    }

    protected function getDocumentField(\Solarium\QueryType\Select\Result\Document $document, $fieldName, $asString = true)
    {
        if (empty($document->$fieldName)) {
            return '';
        }

        if (is_array($document->$fieldName) && $asString) {
            //return $document->$fieldName[0];
            
            // Fix: Community Issue
            $a = $document->$fieldName;
            return $a[0];
        }

        return $document->$fieldName;
    }

}
