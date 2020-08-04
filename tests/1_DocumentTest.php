<?php

use Kuzzle\Collection;
use Kuzzle\Document;
use Kuzzle\Kuzzle;

class DocumentTest extends \PHPUnit\Framework\TestCase
{
    private $requestId = 'requestId';
    private $documentId = 'documentId';

    function testSave()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $index = 'index';
        $collection = 'collection';

        $documentContent = [
            'foo' => 'bar'
        ];
        $documentMeta = [
            'author' => 'foo'
        ];

        $httpRequest = [
            'route'            => '/' . $index . '/' . $collection . '/' . $this->documentId,
            'method'           => 'PUT',
            'request'          => [
                'volatile'   => [],
                'controller' => 'document',
                'action'     => 'createOrReplace',
                'requestId'  => $this->requestId,
                'collection' => $collection,
                'index'      => $index,
                '_id'        => $this->documentId,
                'body'       => array_merge($documentContent, ['baz' => 'baz']),
                'meta'       => ['author' => 'foo'],
            ],
            'query_parameters' => []
        ];
        $saveResponse = [
            '_id'      => $this->documentId,
            '_source'  => array_merge($documentContent, ['baz' => 'baz']),
            '_meta'    => ['author' => 'foo'],
            '_version' => 1
        ];
        $httpResponse = [
            'error'  => null,
            'result' => $saveResponse
        ];

        $kuzzle = new Kuzzle($url);
        $kuzzle->query($httpRequest['request'], $httpRequest['request']);

        /**
         * @var Kuzzle $kuzzle
         */
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = new Document($dataCollection, $this->documentId, $documentContent, $documentMeta);

        $document->setContent(['baz' => 'baz']);
        $result = $document->save(['requestId' => $this->requestId]);

        $this->assertInstanceOf('Kuzzle\Document', $result);
        $this->assertAttributeEquals($this->documentId, 'id', $result);
        $this->assertAttributeEquals(array_merge($documentContent, ['baz' => 'baz']), 'content', $result);
        $this->assertAttributeEquals(array_merge($documentMeta, ['author' => 'foo']), 'meta', $result);
//        $this->assertAttributeEquals(2, 'version', $result);
    }

    function testExists()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $requestId = $this->requestId;
        $index = 'index';
        $collection = 'collection';

        $documentId = $this->documentId;
        $documentContent = [
            'foo' => 'bar'
        ];

        $httpRequest = [
            'route'            => '/' . $index . '/' . $collection . '/' . $documentId . '/_exists',
            'method'           => 'GET',
            'request'          => [
                'volatile'   => [],
                'controller' => 'document',
                'action'     => 'exists',
                'requestId'  => $requestId,
                'collection' => $collection,
                'index'      => $index,
                '_id'        => $documentId,
                'body'       => $documentContent,
                'meta'       => null
            ],
            'query_parameters' => []
        ];
        $httpResponse = [
            'error'  => null,
            'result' => true
        ];

        $kuzzle = new Kuzzle($url);
        $kuzzle->query($httpRequest['request'], $httpRequest['request']);

        /**
         * @var Kuzzle $kuzzle
         */
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = new Document($dataCollection, $documentId, $documentContent);

        $result = $document->exists(['requestId' => $requestId]);

        $this->assertEquals(true, $result);
    }

    function testExistsWithoutId()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $requestId = $this->requestId;
        $index = 'index';
        $collection = 'collection';

        $kuzzle = new \Kuzzle\Kuzzle($url);
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = new Document($dataCollection);

        try {
            $document->exists(['requestId' => $requestId]);

            $this->fail('DocumentTest::testExistsWithoutId => Should raise an exception');
        } catch (Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('Kuzzle\Document::exists: cannot check if the document exists without a document ID',
                $e->getMessage());
        }
    }

    function testPublish()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $requestId = $this->requestId;
        $index = 'index';
        $collection = 'collection';

        $documentId = $this->documentId;
        $documentContent = [
            'foo' => 'bar'
        ];
        $documentMeta = [
            'author' => 'foo'
        ];

        $httpRequest = [
            'route'            => '/' . $index . '/' . $collection . '/_publish',
            'method'           => 'POST',
            'request'          => [
                'volatile'   => [],
                'controller' => 'realtime',
                'action'     => 'publish',
                'requestId'  => $requestId,
                'collection' => $collection,
                'index'      => $index,
                '_id'        => $documentId,
                'body'       => array_merge($documentContent, ['baz' => 'baz']),
                'meta'       => array_merge($documentMeta, ['author' => 'foo'])
            ],
            'query_parameters' => []
        ];
        $publishResponse = [
            'published' => true
        ];
        $httpResponse = [
            'error'  => null,
            'result' => $publishResponse
        ];

        $kuzzle = new Kuzzle($url);
        $kuzzle->query($httpRequest['request'], $httpRequest['request']);

        /**
         * @var Kuzzle $kuzzle
         */
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = new Document($dataCollection, $documentId, $documentContent, $documentMeta);

        $document->setContent(['baz' => 'baz']);
        $result = $document->publish(['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Document', $result);
        $this->assertAttributeEquals($documentId, 'id', $result);
        $this->assertAttributeEquals(array_merge($documentContent, ['baz' => 'baz']), 'content', $result);
        $this->assertAttributeEquals(array_merge($documentMeta, ['author' => 'foo']), 'meta', $result);
    }

    function testSerialize()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $index = 'index';
        $collection = 'collection';

        $documentId = $this->documentId;
        $documentContent = [
            'foo' => 'bar'
        ];
        $documentMeta = [
            'author' => 'foo'
        ];

        $kuzzle = new \Kuzzle\Kuzzle($url);
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = $dataCollection->document($documentId, array_merge($documentContent, ['_version' => 1]),
            $documentMeta);

        $result = $document->serialize();

        $this->assertEquals([
            '_id'      => $documentId,
            'body'     => $documentContent,
            'meta'     => $documentMeta,
            '_version' => 1,
        ], $result);
    }


    function testRefreshWithoutId()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $requestId = $this->requestId;
        $index = 'index';
        $collection = 'collection';

        $kuzzle = new \Kuzzle\Kuzzle($url);
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = new Document($dataCollection);

        try {
            $document->refresh(['requestId' => $requestId]);

            $this->fail('DocumentTest::testRefreshWithoutId => Should raise an exception');
        } catch (Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('Kuzzle\Document::refresh: cannot retrieve a document without a document ID',
                $e->getMessage());
        }
    }

    function testDelete()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $requestId = $this->requestId;
        $index = 'index';
        $collection = 'collection';

        $documentId = $this->documentId;
        $documentContent = [
            'foo' => 'bar'
        ];
        $documentMeta = [
            'author' => 'foo'
        ];

        $httpRequest = [
            'route'            => '/' . $index . '/' . $collection . '/' . $documentId,
            'method'           => 'DELETE',
            'request'          => [
                'volatile'   => [],
                'controller' => 'document',
                'action'     => 'delete',
                'requestId'  => $requestId,
                'collection' => $collection,
                'index'      => $index,
                '_id'        => $documentId,
                'body'       => $documentContent,
                'meta'       => $documentMeta
            ],
            'query_parameters' => []
        ];
        $deleteResponse = [
            '_id' => $documentId
        ];
        $httpResponse = [
            'error'  => null,
            'result' => $deleteResponse
        ];

        $kuzzle = new Kuzzle($url);
//        $kuzzle->query($httpRequest['request'], ['_id' => $documentId,]);
        /**
         * @var Kuzzle $kuzzle
         */
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = new Document($dataCollection, $documentId, $documentContent, $documentMeta);

        $result = $document->delete(['requestId' => $requestId]);

        $this->assertEquals($documentId, $result);
    }

    function testDeleteWithoutId()
    {
        $url = KuzzleTest::FAKE_KUZZLE_HOST;
        $requestId = $this->requestId;
        $index = 'index';
        $collection = 'collection';

        $kuzzle = new \Kuzzle\Kuzzle($url);
        $dataCollection = new Collection($kuzzle, $collection, $index);
        $document = new Document($dataCollection);

        try {
            $document->delete(['requestId' => $requestId]);

            $this->fail('DocumentTest::testDeleteWithoutId => Should raise an exception');
        } catch (Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
            $this->assertEquals('Kuzzle\Document::delete: cannot delete a document without a document ID',
                $e->getMessage());
        }
    }
}
