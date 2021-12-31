<?php

namespace App\CoreIntegrationApi\ContextApi;

use App\CoreIntegrationApi\RequestValidator;

class ContextRequestValidator extends RequestValidator
{
    protected $validatedMetaData = [];

    // uses serves provider Located ...
    // loads function __construct(ContextRequestDataPrepper $contextRequestDataPrepper)

    public function validate()
    {
        $this->requestDataPrepper->prep();

        foreach ($this->requestDataPrepper->getPreppedData() as $request) {
            $this->validateRequest($request);
        }

        return $this->validatedMetaData;
    }

    protected function setUpPreppedRequest($request)
    {
        parent::setUpPreppedRequest($request);
        
        $this->rejectedParameters = [];
        $this->acceptedParameters = [];
        $this->errors = [];
        $this->queryArguments = [];
    }

    protected function setValidatedMetaData()
    {
        $validatedRequestMetaData['rejectedParameters'] = $this->getRejectedParameters();
        $validatedRequestMetaData['acceptedParameters'] = $this->getAcceptedParameters();
        $validatedRequestMetaData['errors'] = $this->errors;
        $validatedRequestMetaData['queryArguments'] = $this->getQueryArguments();
        $this->validatedMetaData[] = $validatedRequestMetaData;
    }
}
