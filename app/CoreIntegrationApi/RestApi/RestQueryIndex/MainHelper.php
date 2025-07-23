<?php

namespace App\CoreIntegrationApi\RestApi\RestQueryIndex;

class MainHelper
{
    private array $validatedMetaData;

    public function setMetaData(array &$validatedMetaData): void
    {
        $this->validatedMetaData = $validatedMetaData;
    }

    public function getMainInformation(): array
    {
        $main = $this->getMain();

        $partialOverride = config('coreintegration.partialOverride') ?? true;
        $overrides = config('coreintegration.indexOverrides') ?? [];
        if ($partialOverride === true) {
            foreach ($overrides as $key => $value) {
                $main[$key] = $value;
            }
        }

        return $main;
    }

    private function getMain(): array
    {
        return [
            'companyName' => 'Placeholder Company',
            'termsOfUse' => 'Placeholder Terms URL',
            'version' => '1.0.0',
            'contact' => 'someone@someone.com',
            'description' => 'v1.0.0 of the api. This API may be used to retrieve data. restrictions and limitations are detailed below in the _______ section.', // TODO: fix this _______
            'siteRoot' => substr($this->validatedMetaData['endpointData']['indexUrl'], 0, -7),
            'apiRoot' => $this->validatedMetaData['endpointData']['indexUrl'],
            'defaultReturnRequestStructure' => config('coreintegration.defaultReturnRequestStructure', 'dataOnly'),
        ];
    }
}
