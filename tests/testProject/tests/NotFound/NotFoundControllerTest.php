<?php

namespace Deozza\PhilarmonyCoreBundle\Tests\testProject\tests\NotFound;

use Deozza\ApiTesterBundle\Service\TestAsserter;

class NotFoundControllerTest extends TestAsserter
{
    public function setUp()
    {
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
            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/invalid'                                     , "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entities/invalid'                                   , "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00300000-0000-4000-a000-000000000001'        , "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'PATCH' , 'url'=>'/api/entity/00300000-0000-4000-a000-000000000001'        , "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'DELETE', 'url'=>'/api/entity/00300000-0000-4000-a000-000000000001'        , "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/00300000-0000-4000-a000-000000000001/photo'  , "status"=>404, 'token'=>'token_userActive']],
            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/entity/00300000-0000-4000-a000-000000000000/invalid', "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00300000-0000-4000-a000-000000000001/photo'  , "status"=>404, 'token'=>'token_userActive']],
            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/entity/00300000-0000-4000-a000-000000000000/invalid', "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'DELETE', 'url'=>'/api/entity/00300000-0000-4000-a000-000000000001/photo'  , "status"=>404, 'token'=>'token_userActive']],
            ["kind"=>"unit", "test"=>['method'=>'DELETE', 'url'=>'/api/entity/00300000-0000-4000-a000-000000000000/invalid', "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/validate/00300000-0000-5000-a000-000000000001'     , "status"=>404, 'token'=>'token_userActive']],
            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/validate/00400000-0000-4000-a000-000000000000'     , "status"=>404, 'token'=>'token_userAdmin']],

            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/retrograde/00300000-0000-4000-a000-000000000001'   , "status"=>404, 'token'=>'token_userActive']],
            ["kind"=>"unit", "test"=>['method'=>'POST'  , 'url'=>'/api/retrograde/00400000-0000-5000-a000-000000000000'   , "status"=>404, 'token'=>'token_userAdmin']],

            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/entity/invalid'                                , "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/property/invalid'                              , "status"=>404, 'token'=>'token_userActive']],

            ["kind"=>"unit", "test"=>['method'=>'GET'   , 'url'=>'/api/doc/enumeration/invalid'                           , "status"=>404, 'token'=>'token_userActive']],
        ];
    }
}