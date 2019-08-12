<?php

namespace Deozza\PhilarmonyCoreBundle\Tests\testProjectMongodb\tests\NotAuthorized;

use Deozza\PhilarmonyApiTesterBundle\Service\TestAsserter;

class MongodbNotAuthorizedControllerTest extends TestAsserter
{
    const TEST_DATABASE_PATH = __DIR__."/../../var/db_test/philarmony-core-test";

    public function setUp()
    {
        parent::setTestDatabasePath(self::TEST_DATABASE_PATH);
        $this->setEnv(json_decode(file_get_contents(__DIR__.'/../../src/DataFixtures/MongoDB/env.json'), true));
        parent::setUp();
    }

    /**
     * @dataProvider addDataProvider
     */
    public function testUnit($kind, $test)
    {
        parent::launchTestByKind($kind, $test);
    }

    public function addDataProvider()
    {
        return
        [
            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/annonce'                                   , "status"=>401]],

            ["kind"=>"unit", "test"=>['method'=>'PATCH' , 'url'=>'/api/entity/#annonce_8#'      , "status"=>401]],

            ["kind"=>"unit", "test"=>['method'=>'DELETE', 'url'=>'/api/entity/#annonce_8#'      , "status"=>401]],

            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/#annonce_9#/file/photo', "status"=>401]],
            ["kind"=>"unit", "test"=>['method'=>'DELETE', 'url'=>'/api/entity/#annonce_9#/file/photo', "status"=>401]],

            ["kind"=>"unit", "test"=>['method'=>'PATCH' , 'url'=>'/api/validate/#annonce_9#'    , "status"=>401]],
            ["kind"=>"unit", "test"=>['method'=>'PATCH' , 'url'=>'/api/retrograde/#annonce_9#'  , "status"=>401]],
        ];
    }
}