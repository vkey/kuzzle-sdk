<?php

use Kuzzle\Kuzzle;
use Kuzzle\Security\Security;
use Kuzzle\Security\User;
use Kuzzle\Security\Profile;
use Kuzzle\Security\Role;
use Kuzzle\Util\UsersSearchResult;
use Kuzzle\Util\ProfilesSearchResult;
use Kuzzle\Util\RolesSearchResult;

class SecurityTest extends \PHPUnit_Framework_TestCase
{
    function testCreateProfile()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $profileId = uniqid();
        $profileContent = [
            'policies' => [
                [
                    '_id' => 'default',
                    'restrictedTo' => [],
                    'allowInternalIndex'=> true
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/profiles/_create',
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'createProfile',
                'requestId' => $requestId,
                '_id' => $profileId,
                'body' => $profileContent
            ]
        ];
        $saveResponse = [
            '_id' => $profileId,
            '_source' => $profileContent,
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $saveResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->createProfile($profileId, $profileContent, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\Profile', $result);
        $this->assertAttributeEquals($profileId, 'id', $result);
        $this->assertAttributeEquals($profileContent, 'content', $result);
    }

    function testCreateOrReplaceProfile()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $profileId = uniqid();
        $profileContent = [
            'policies' => [
                [
                    '_id' => 'default',
                    'restrictedTo' => [],
                    'allowInternalIndex'=> true
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/profiles/' . $profileId . '/_createOrReplace',
            'method' => 'PUT',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'createOrReplaceProfile',
                'requestId' => $requestId,
                '_id' => $profileId,
                'body' => $profileContent
            ]
        ];
        $saveResponse = [
            '_id' => $profileId,
            '_source' => $profileContent,
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $saveResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->createProfile($profileId, $profileContent, [
            'replaceIfExist' => true,
            'requestId' => $requestId
        ]);

        $this->assertInstanceOf('Kuzzle\Security\Profile', $result);
        $this->assertAttributeEquals($profileId, 'id', $result);
        $this->assertAttributeEquals($profileContent, 'content', $result);
    }

    function testCreateRole()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $roleId = uniqid();
        $roleContent = [
            'allowInternalIndex' => false,
            'controllers' => [
                '*' => [
                    'actions'=> [
                        ['*' => true]
                    ]
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/roles/_create',
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'createRole',
                'requestId' => $requestId,
                '_id' => $roleId,
                'body' => $roleContent
            ]
        ];
        $saveResponse = [
            '_id' => $roleId,
            '_source' => $roleContent,
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $saveResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->createRole($roleId, $roleContent, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\Role', $result);
        $this->assertAttributeEquals($roleId, 'id', $result);
        $this->assertAttributeEquals($roleContent, 'content', $result);
    }

    function testCreateOrReplaceRole()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $roleId = uniqid();
        $roleContent = [
            'allowInternalIndex' => false,
            'controllers' => [
                '*' => [
                    'actions'=> [
                        ['*' => true]
                    ]
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/roles/' . $roleId . '/_createOrReplace',
            'method' => 'PUT',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'createOrReplaceRole',
                'requestId' => $requestId,
                '_id' => $roleId,
                'body' => $roleContent
            ]
        ];
        $saveResponse = [
            '_id' => $roleId,
            '_source' => $roleContent,
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $saveResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->createRole($roleId, $roleContent, [
            'replaceIfExist' => true,
            'requestId' => $requestId
        ]);

        $this->assertInstanceOf('Kuzzle\Security\Role', $result);
        $this->assertAttributeEquals($roleId, 'id', $result);
        $this->assertAttributeEquals($roleContent, 'content', $result);
    }

    function testCreateUser()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $userId = uniqid();
        $userContent = [
            'profileId' => 'admin'
        ];

        $httpRequest = [
            'route' => '/api/1.0/users/_create',
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'createUser',
                'requestId' => $requestId,
                '_id' => $userId,
                'body' => $userContent
            ]
        ];
        $saveResponse = [
            '_id' => $userId,
            '_source' => $userContent,
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $saveResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->createUser($userId, $userContent, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\User', $result);
        $this->assertAttributeEquals($userId, 'id', $result);
        $this->assertAttributeEquals($userContent, 'content', $result);
    }

    function testCreateOrReplaceUser()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $userId = uniqid();
        $userContent = [
            'profileId' => 'admin'
        ];

        $httpRequest = [
            'route' => '/api/1.0/users/' . $userId,
            'method' => 'PUT',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'createOrReplaceUser',
                'requestId' => $requestId,
                '_id' => $userId,
                'body' => $userContent
            ]
        ];
        $saveResponse = [
            '_id' => $userId,
            '_source' => $userContent,
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $saveResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->createUser($userId, $userContent, [
            'replaceIfExist' => true,
            'requestId' => $requestId
        ]);

        $this->assertInstanceOf('Kuzzle\Security\User', $result);
        $this->assertAttributeEquals($userId, 'id', $result);
        $this->assertAttributeEquals($userContent, 'content', $result);
    }

    function testDeleteProfile()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $profileId = uniqid();

        $httpRequest = [
            'route' => '/api/1.0/profiles/' . $profileId,
            'method' => 'DELETE',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'deleteProfile',
                'requestId' => $requestId,
                '_id' => $profileId,
            ]
        ];
        $deleteResponse = [
            '_id' => $profileId,
            'found' => true
        ];
        $httpResponse = [
            'error' => null,
            'result' => $deleteResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->deleteProfile($profileId, ['requestId' => $requestId]);

        $this->assertEquals($profileId, $result);
    }

    function testDeleteUser()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $userId = uniqid();

        $httpRequest = [
            'route' => '/api/1.0/users/' . $userId,
            'method' => 'DELETE',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'deleteUser',
                'requestId' => $requestId,
                '_id' => $userId,
            ]
        ];
        $deleteResponse = [
            '_id' => $userId,
            'found' => true
        ];
        $httpResponse = [
            'error' => null,
            'result' => $deleteResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->deleteUser($userId, ['requestId' => $requestId]);

        $this->assertEquals($userId, $result);
    }

    function testDeleteRole()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $roleId = uniqid();

        $httpRequest = [
            'route' => '/api/1.0/roles/' . $roleId,
            'method' => 'DELETE',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'deleteRole',
                'requestId' => $requestId,
                '_id' => $roleId,
            ]
        ];
        $deleteResponse = [
            '_id' => $roleId,
            'found' => true
        ];
        $httpResponse = [
            'error' => null,
            'result' => $deleteResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->deleteRole($roleId, ['requestId' => $requestId]);

        $this->assertEquals($roleId, $result);
    }

    function testGetProfile()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $profileId = uniqid();
        $profileContent = [
            'policies' => [
                [
                    '_id' => 'default',
                    'restrictedTo' => [],
                    'allowInternalIndex'=> true
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/profiles/' . $profileId,
            'method' => 'GET',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'getProfile',
                'requestId' => $requestId,
                '_id' => $profileId,
            ]
        ];
        $getResponse = [
            '_id' => $profileId,
            '_source' => $profileContent
        ];
        $httpResponse = [
            'error' => null,
            'result' => $getResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->getProfile($profileId, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\Profile', $result);
        $this->assertAttributeEquals($profileId, 'id', $result);
        $this->assertAttributeEquals($profileContent, 'content', $result);
    }

    function testGetRole()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $roleId = uniqid();
        $roleContent = [
            'allowInternalIndex' => false,
            'controllers' => [
                '*' => [
                    'actions'=> [
                        ['*' => true]
                    ]
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/roles/' . $roleId,
            'method' => 'GET',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'getRole',
                'requestId' => $requestId,
                '_id' => $roleId,
            ]
        ];
        $getResponse = [
            '_id' => $roleId,
            '_source' => $roleContent
        ];
        $httpResponse = [
            'error' => null,
            'result' => $getResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->getRole($roleId, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\Role', $result);
        $this->assertAttributeEquals($roleId, 'id', $result);
        $this->assertAttributeEquals($roleContent, 'content', $result);
    }

    function testGetUser()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $userId = uniqid();
        $userContent = [
            'profileId' => 'admin'
        ];

        $httpRequest = [
            'route' => '/api/1.0/users/' . $userId,
            'method' => 'GET',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'getUser',
                'requestId' => $requestId,
                '_id' => $userId,
            ]
        ];
        $getResponse = [
            '_id' => $userId,
            '_source' => $userContent
        ];
        $httpResponse = [
            'error' => null,
            'result' => $getResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->getUser($userId, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\User', $result);
        $this->assertAttributeEquals($userId, 'id', $result);
        $this->assertAttributeEquals($userContent, 'content', $result);
    }

    function testGetUserRights()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $userId = uniqid();
        $userRightsContent = [
            [
                "action" => "*",
                "collection" => "*",
                "controller" => "*",
                "index" => "*",
                "value" => "allowed"
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/users/' . $userId . '/_rights',
            'method' => 'GET',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'getUserRights',
                'requestId' => $requestId,
                '_id' => $userId,
            ]
        ];
        $getRightsResponse = [
            'hits' => $userRightsContent
        ];
        $httpResponse = [
            'error' => null,
            'result' => $getRightsResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->getUserRights($userId, ['requestId' => $requestId]);

        $this->assertEquals($userRightsContent, $result);
    }

    function testIsActionAllowed()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;

        $kuzzle = new Kuzzle($url);
        $security = $kuzzle->security();

        $userRights = [
            [
                "controller" => "read",
                "action" => "now",
                "collection" => "*",
                "index" => "*",
                "value" => "allowed"
            ],
            [
                "controller" => "read",
                "action" => "get",
                "collection" => "*",
                "index" => "*",
                "value" => "conditional"
            ]
        ];

        $result = $security->isActionAllowed($userRights, 'read', 'now');
        $this->assertEquals(Security::ACTION_ALLOWED, $result);

        $result = $security->isActionAllowed($userRights, 'read', 'get');
        $this->assertEquals(Security::ACTION_CONDITIONAL, $result);

        $result = $security->isActionAllowed($userRights, 'read', 'listIndexes');
        $this->assertEquals(Security::ACTION_DENIED, $result);
    }

    function testSearchProfiles()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $filter = [
            'query' => [
                'bool' => [
                    'should' => [
                        'term' => ['foo' =>  'bar']
                    ]
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/profiles/_search',
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'searchProfiles',
                'requestId' => $requestId,
                'body' => $filter
            ]
        ];
        $advancedSearchResponse = [
            'hits' => [
                0 => [
                    '_id' => 'test',
                    '_source' => [
                        'foo' => 'bar',
                        'policies' => [
                            [
                                '_id' => 'default',
                                'restrictedTo' => [],
                                'allowInternalIndex'=> true
                            ]
                        ]
                    ]
                ],
                1 => [
                    '_id' => 'test1',
                    '_source' => [
                        'foo' => 'bar',
                        'policies' => [
                            [
                                '_id' => 'default',
                                'restrictedTo' => [],
                                'allowInternalIndex'=> true
                            ]
                        ]
                    ]
                ]
            ],
            'total' => 2
        ];
        $httpResponse = [
            'error' => null,
            'result' => $advancedSearchResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $searchResult = $security->searchProfiles($filter, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Util\ProfilesSearchResult', $searchResult);
        $this->assertEquals(2, $searchResult->getTotal());

        $profiles = $searchResult->getProfiles();
        $this->assertInstanceOf('Kuzzle\Security\Profile', $profiles[0]);
        $this->assertAttributeEquals('test', 'id', $profiles[0]);
        $this->assertAttributeEquals('test1', 'id', $profiles[1]);
    }

    function testSearchRoles()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $filter = [
            'query' => [
                'bool' => [
                    'should' => [
                        'term' => ['foo' =>  'bar']
                    ]
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/roles/_search',
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'searchRoles',
                'requestId' => $requestId,
                'body' => $filter
            ]
        ];
        $advancedSearchResponse = [
            'hits' => [
                0 => [
                    '_id' => 'test',
                    '_source' => [
                        'foo' => 'bar',
                        'allowInternalIndex' => false,
                        'controllers' => [
                            '*' => [
                                'actions'=> [
                                    ['*' => true]
                                ]
                            ]
                        ]
                    ]
                ],
                1 => [
                    '_id' => 'test1',
                    '_source' => [
                        'foo' => 'bar',
                        'allowInternalIndex' => false,
                        'controllers' => [
                            '*' => [
                                'actions'=> [
                                    ['*' => true]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'total' => 2
        ];
        $httpResponse = [
            'error' => null,
            'result' => $advancedSearchResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $searchResult = $security->searchRoles($filter, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Util\RolesSearchResult', $searchResult);
        $this->assertEquals(2, $searchResult->getTotal());

        $profiles = $searchResult->getRoles();
        $this->assertInstanceOf('Kuzzle\Security\Role', $profiles[0]);
        $this->assertAttributeEquals('test', 'id', $profiles[0]);
        $this->assertAttributeEquals('test1', 'id', $profiles[1]);
    }

    function testSearchUsers()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $filter = [
            'query' => [
                'bool' => [
                    'should' => [
                        'term' => ['foo' =>  'bar']
                    ]
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/users/_search',
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'searchUsers',
                'requestId' => $requestId,
                'body' => $filter
            ]
        ];
        $advancedSearchResponse = [
            'hits' => [
                0 => [
                    '_id' => 'test',
                    '_source' => [
                        'foo' => 'bar',
                        'profile' => 'default'
                    ]
                ],
                1 => [
                    '_id' => 'test1',
                    '_source' => [
                        'foo' => 'bar',
                        'profile' => 'default'
                    ]
                ]
            ],
            'total' => 2
        ];
        $httpResponse = [
            'error' => null,
            'result' => $advancedSearchResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $searchResult = $security->searchUsers($filter, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Util\UsersSearchResult', $searchResult);
        $this->assertEquals(2, $searchResult->getTotal());

        $profiles = $searchResult->getUsers();
        $this->assertInstanceOf('Kuzzle\Security\User', $profiles[0]);
        $this->assertAttributeEquals('test', 'id', $profiles[0]);
        $this->assertAttributeEquals('test1', 'id', $profiles[1]);
    }



    function testUpdateProfile()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $profileId = uniqid();
        $profileBaseContent = [
            'policies' => [
                [
                    '_id' => 'anonymous',
                    'restrictedTo' => [
                        ['index' => 'my-second-index', 'collection' => ['my-collection']]
                    ]
                ]
            ]
        ];
        $profileContent = [
            'policies' => [
                [
                    '_id' => 'default',
                    'restrictedTo' => [
                        ['index' => 'my-index'],
                        ['index' => 'my-second-index', 'collection' => ['my-collection']]
                    ],
                    'allowInternalIndex'=> false
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/profiles/' . $profileId,
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'updateProfile',
                'requestId' => $requestId,
                '_id' => $profileId,
                'body' => $profileContent
            ]
        ];
        $updateResponse = [
            '_id' => $profileId,
            '_source' => array_merge($profileBaseContent, $profileContent),
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $updateResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->updateProfile($profileId, $profileContent, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\Profile', $result);
        $this->assertAttributeEquals($profileId, 'id', $result);
        $this->assertAttributeEquals(array_merge($profileBaseContent, $profileContent), 'content', $result);
    }

    function testUpdateRole()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $roleId = uniqid();
        $roleUpdateContent = [
            'foo' => 'bar'
        ];
        $roleContent = [
            'allowInternalIndex' => false,
            'controllers' => [
                '*' => [
                    'actions'=> [
                        ['*' => true]
                    ]
                ]
            ]
        ];

        $httpRequest = [
            'route' => '/api/1.0/roles/' . $roleId,
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'updateRole',
                'requestId' => $requestId,
                '_id' => $roleId,
                'body' => $roleUpdateContent
            ]
        ];
        $updateResponse = [
            '_id' => $roleId,
            '_source' => array_merge($roleContent, $roleUpdateContent),
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $updateResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->updateRole($roleId, $roleUpdateContent, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\Role', $result);
        $this->assertAttributeEquals($roleId, 'id', $result);
        $this->assertAttributeEquals(array_merge($roleContent, $roleUpdateContent), 'content', $result);
    }

    function testUpdate()
    {
        $url = KuzzleTest::FAKE_KUZZLE_URL;
        $requestId = uniqid();

        $userId = uniqid();

        $userContent = [
            'profileId' => uniqid()
        ];
        $userBaseContent = [
            'foo' => 'bar'
        ];

        $httpRequest = [
            'route' => '/api/1.0/users/' . $userId,
            'method' => 'POST',
            'request' => [
                'metadata' => [],
                'controller' => 'security',
                'action' => 'updateUser',
                'requestId' => $requestId,
                '_id' => $userId,
                'body' => $userContent
            ]
        ];
        $updateResponse = [
            '_id' => $userId,
            '_source' => array_merge($userBaseContent, $userContent),
            '_version' => 1
        ];
        $httpResponse = [
            'error' => null,
            'result' => $updateResponse
        ];

        $kuzzle = $this
            ->getMockBuilder('\Kuzzle\Kuzzle')
            ->setMethods(['emitRestRequest'])
            ->setConstructorArgs([$url])
            ->getMock();

        $kuzzle
            ->expects($this->once())
            ->method('emitRestRequest')
            ->with($httpRequest)
            ->willReturn($httpResponse);

        /**
         * @var Kuzzle $kuzzle
         */
        $security = new Security($kuzzle);

        $result = $security->updateUser($userId, $userContent, ['requestId' => $requestId]);

        $this->assertInstanceOf('Kuzzle\Security\User', $result);
        $this->assertAttributeEquals($userId, 'id', $result);
        $this->assertAttributeEquals(array_merge($userBaseContent, $userContent), 'content', $result);
    }

}