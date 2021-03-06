<?php

namespace Deozza\PhilarmonyCoreBundle\Tests\testProjectMongodb\tests\Conflict;

use Deozza\PhilarmonyApiTesterBundle\Service\TestAsserter;

class MongodbConflictControllerTest extends TestAsserter
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
            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/annonce'                                   , "status"=>409, "token"=>"token_userActive", "in"=>"postAnnonce", "out"=>"postedAnnonce"]],
            ["kind"=>"unit", "test"=>['method'=>'PATCH' , 'url'=>'/api/retrograde/#annonce_7#'  , "status"=>409, "token"=>"token_userAdmin"]],

        ];
    }
}