<?php

namespace Tests\Unit;

use App\CoreIntegrationApi\RestApi\RestQueryIndex\MainHelper;
use Tests\TestCase;

class MainHelperTest extends TestCase
{
    public function test_about_details_are_correct()
    {
        $validatedMetaData = [
            'endpointData' => [
                'resource' => 'index',
                'indexUrl' => 'http://localhost:8000/api/v1',
                'url' => 'http://localhost:8000/api/v1',
                'requestMethod' => 'GET',
                'defaultReturnRequestStructure' => 'dataOnly',
                'resourceId' => ''
            ]
        ];

        $this->mainHelper = new MainHelper();
        $this->mainHelper->setMetaData($validatedMetaData);

        $mainInfo = $this->mainHelper->getMainInformation();

        $this->assertEquals([
            'companyName' => 'Placeholder Company',
            'termsOfUse' => 'Placeholder Terms URL',
            'version' => '1.0.0',
            'contact' => 'someone@someone.com',
            'description' => 'v1.0.0 of the api. This API may be used to retrieve data. restrictions and limitations are detailed below in the _______ section.',
            'siteRoot' => 'http://localhost:8000',
            'apiRoot' => 'http://localhost:8000/api/v1',
            'defaultReturnRequestStructure' => 'dataOnly'
        ], $mainInfo);
    }
}
