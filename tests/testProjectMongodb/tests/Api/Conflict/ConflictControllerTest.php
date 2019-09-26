<?php

namespace Deozza\PhilarmonyCoreBundle\Tests\testProjectMongodb\tests\Api\Conflict;

use Deozza\PhilarmonyApiTesterBundle\Service\TestAsserter;

class ConflictControllerTest extends TestAsserter
{
    const TEST_DATABASE_PATH = __DIR__ . "/../../../var/db_test/philarmony-core-test";

    public function setUp()
    {
        parent::setTestDatabasePath(self::TEST_DATABASE_PATH);
        parent::setEnv(json_decode(file_get_contents(__DIR__ . "/../../../src/DataFixtures/MongoDB/env.json"), true));
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
                ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/gear'    , 'token'=>'token_userAdmin', "status"=>409, 'out'=>'entityPostedWithDuplicatedProperty', 'in'=>'entityPostWithDuplicatedProperty']],
            ];
    }
}