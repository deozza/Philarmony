<?php

namespace Deozza\PhilarmonyCoreBundle\Tests\testProjectMongodb\tests\Ok;

use Deozza\PhilarmonyApiTesterBundle\Service\TestAsserter;

class MongodbOkControllerTest extends TestAsserter
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
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entities/annonce'                                 , "status"=>200, "out"=>"listOfAnnonces"]],
            
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00300000-0000-4000-a000-000000000000'      , "status"=>200, "out"=>"getAnnonce"]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00100000-0000-4000-a000-000000000000'      , "status"=>200, "token"=>"token_userActive", "out"=>"getAnnonce2"]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00100000-0000-4000-a000-000000000000'      , "status"=>200, "token"=>"token_userAdmin" , "out"=>"getAnnonce2"]],
            ["kind"=>"unit", "test"=>['method'=>'DELETE', 'url'=>'/api/entity/00100000-0000-4000-a000-000000000000'      , "status"=>204, "token"=>"token_userActive"]],
            ["kind"=>"unit", "test"=>['method'=>'DELETE', 'url'=>'/api/entity/00100000-0000-4000-a000-000000000000'      , "status"=>204, "token"=>"token_userAdmin" ]],
            
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00400000-0000-4000-a000-000000000000/file/photo', "status"=>200]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00500000-0000-4000-a000-000000000000/file/photo', "status"=>200, "token"=>"token_userActive"]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00500000-0000-4000-a000-000000000000/file/photo', "status"=>200, "token"=>"token_userAdmin"]],
            
            ["kind"=>"unit", "test"=>['method'=>'PATCH' , 'url'=>'/api/validate/00500000-0000-4000-a000-000000000000'    , "status"=>200, "token"=>"token_userAdmin"]],
            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/reservation'                               , "status"=>201, "token"=>"token_userActive2", "in"=>"postReservation", "out"=>"postedReservation"]],
            
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/entities'                                     , "status"=>200]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/entity/annonce'                               , "status"=>200]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/properties'                                   , "status"=>200]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/property/title'                               , "status"=>200]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/enumerations'                                 , "status"=>200]],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/enumeration/boolean'                          , "status"=>200]],
            [
                "kind"=>"scenario",
                "test"=>[
                    ['method'=>'POST' , 'url'=>'/api/entity/reservation'                               , "status"=>201, "token"=>"token_userActive2", "in"=>"postReservation", "out"=>"postedReservationScenario"],
                    ['method'=>'GET'  , 'url'=>'/api/entity/#uuid#'                                     , "status"=>200, "token"=>"token_userActive2"],
                    ['method'=>'GET'  , 'url'=>'/api/entity/#uuid#'                                     , "status"=>200, "token"=>"token_userActive"],

                ]
            ],

        ];
    }
}