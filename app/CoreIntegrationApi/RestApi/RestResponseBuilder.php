<?php

namespace App\CoreIntegrationApi\RestApi;

use App\CoreIntegrationApi\ResponseBuilder;

class RestResponseBuilder extends ResponseBuilder
{
    protected $validatedMetaData;
    protected $queryResult;

    public function setValidatedMetaData($validatedMetaData)
    {
        $this->validatedMetaData = $validatedMetaData;
    }
    
    public function setResponseData($queryResult)
    {
        $this->queryResult = $queryResult;
    }

    public function make()
    {
        return $this->makeRequest();
    }

    protected function makeRequest()
    {
        // $this->checkGetRequest();

        // ! start here ********************************************************************** make it work, resarch best rest proticals
        $httpMethodResponseBuilder = $this->httpMethodResponseBuilderFactory->getFactoryItem($this->validatedMetaData['endpointData']['httpMethod']);

        return $httpMethodResponseBuilder->buildResponse($this->validatedMetaData, $this->queryResult);

        // $response = $httpMethodResponseBuilder->buildResponse($this->validatedMetaData, $this->queryResult);

        // return $response;
        // TODO: see if I can't merge these two lines

        // return $this->response;
    }

    // protected function checkGetRequest()
    // {
    //     if ($this->validatedMetaData['endpointData']['httpMethod'] == 'GET') {
    //         if (is_array($this->queryResult)) {
    //             // form data or column data
    //             $this->response = response()->json($this->queryResult, 200);
    //         } else {
    //             $paginateObj = json_decode($this->queryResult->toJson(), true);
    //             $paginateObj = $this->setGetResponse($paginateObj);
    
    //             $endpointId = $this->validatedMetaData['endpointData']['endpointId'];
    
    //             if ($endpointId && !str_contains($endpointId, ',')) {
    //                 if (count($paginateObj['data']) == 0) {
    //                     $endpoint = $this->validatedMetaData['endpointData']['endpoint'];
    //                     $this->response = response()->json(['message' => "The record with the id of $endpointId at the \"$endpoint\" endpoint was not found"], 404);
    //                 } else {
    //                     $this->response = response()->json($paginateObj['data'][0], 200);
    //                 }
    //             } else {
    //                 $this->response = response()->json($paginateObj, 200);
    //             }
    //         }
    //     }
    // }

    // protected function setGetResponse($paginateObj)
    // {
    //     if (isset($this->validatedMetaData['extraData']['acceptableParameters'])) {
    //         foreach ($this->validatedMetaData['extraData']['acceptableParameters'] as $columnName => $columnArray) {
    //             $paginateObj['availableEndpointParameters']['parameters'][$columnName] = $columnArray['api_data_type'];
    //         }
    //         $paginateObj['availableEndpointParameters']['parameters']['info'] = [
    //             'message' => 'Documentation on how to utilize parameter data types can be found in the index response, in the apiDocumentation.parameterDataTypes section.', 
    //             'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
    //         ];
    //         $paginateObj['availableEndpointParameters']['defaultParameters'] = [
    //             'columns' => 'endpoint parameters',
    //             'orderBy' => 'endpoint parameters',
    //             'methodCalls' => [
    //                 'value' => 'endpoint methods',
    //                 'availableMethodCalls' => $this->validatedMetaData['extraData']['availableMethodCalls'],
    //             ],
    //             'includes' => [
    //                 'value' => 'endpoint includes/relationships',
    //                 'availableIncludes' => $this->validatedMetaData['extraData']['availableIncludes'],
    //             ],
    //             'page' => 'int',
    //             'perPage' => 'int',
    //             'columnData' => true,
    //             'formData' => true,
    //             'includeData' => true,
    //             'methodCallData' => true,
    //             'info' => [
    //                 'message' => 'Documentation on how to utilize default parameter data types can be found in the index response, in the apiDocumentation.defaultParameterDataTypes section.', 
    //                 'index_url' => $this->validatedMetaData['endpointData']['indexUrl']
    //             ]
    //         ];
            
    //     }

    //     $paginateObj['rejectedParameters'] = $this->validatedMetaData['rejectedParameters'];
    //     $paginateObj['acceptedParameters'] = $this->validatedMetaData['acceptedParameters'];
    //     $paginateObj['endpointData'] = $this->validatedMetaData['endpointData'];
    //     unset($paginateObj['endpointData']['class']);

    //     return $paginateObj;
    // }
}

// TODO: add ability to ask for methodcalls and includes, like columndata
// TODO: add default Parameters
// TODO: documentation builder
// // TODO: move to separate class to ues in many places, formData, columnData, index, Get response - test it - set in validator
// $paginateObj['availableEndpointParameters']['defaultParameters']['columns'] = [
//     'parameterNameOptions' => ['columns','select'],
//     'info' => [
//         'description' => '"columns" is used to select data attributes/columns from an endpoint.', 
//         'use' => 'Example .../projects/?columns=id,title,roles,client,budget will return only the data attributes of id, title, roles, client and budget.',
//         'exampleResponse' => [
//             'id' => 34,
//             'title' => 'Laudantium Nesciunt Est Molestiae',
//             'roles' => 'Backend Developer',
//             'client' => 'Schmitt, Gerhold and Lemke',
//             'budget' => '900.00'
//         ],
//     ],
// ];
// $paginateObj['availableEndpointParameters']['defaultParameters']['orderby'] = [
//     'parameterNameOptions' => ['orderby','order_by'],
//     'info' => [
//         'description' => '"orderby" is used to order the return data for an end point. All parameter in the availableEndpointParameters.parameters for a given endpoint are available for sorting, ascending/ASC and descending/DESC.', 
//         'use' => 'Example .../projects/?orderby=roles,title will return data attributes sorted by roles then title.',
//         'exampleResponse' => [
//             [
//                 'id' => 34,
//                 'title' => 'Laudantium Nesciunt Est Molestiae',
//                 'roles' => 'Backend Developer',
//             ],
//             [
//                 'id' => 45,
//                 'title' => 'Est Molestiae',
//                 'roles' => 'Developer',
//             ],
//             [
//                 'id' => 30,
//                 'title' => 'Nesciunt Est Molestiae',
//                 'roles' => 'Developer',
//             ],
            
//         ],
//     ],
// ];
// // TODO: add ascending and descending
// $paginateObj['availableEndpointParameters']['defaultParameters']['methodcalls'] = [
//     'parameterNameOptions' => ['methodcalls','method_calls'],
//     'info' => [
//         'description' => '"methodcalls" allows you to access specific method calls from this given endpoint. Only the method calls below are available for this endpoints.', 
//         'use' => 'Example .../projects/?methodcalls=profits,projectSore will return data attributes and method call data.',
//         'exampleResponse' => [
//             'id' => 78,
//             'title' => 'Laudantium Nesciunt Est Molestiae',
//             'roles' => 'Backend Developer',
//             'client' => 'Schmitt, Gerhold and Lemke',
//             'budget' => '1000.00',
//             'profits' => '200.00',
//             'projectSore' => 'A+',
//         ],
//     ],
//     // TODO: add MethodCalls
//     'availableMethodCalls' => []
// ];
// $paginateObj['availableEndpointParameters']['defaultParameters']['includes'] = [
//     'parameterNameOptions' => 'includes',
//     'info' => [
//         'description' => '"includes" allows you to access endpoint relationships. Only the includes below are available for this endpoints.', 
//         'use' => 'Example .../projects/?includes=tags,images will return data/records and their relationships.',
//         'exampleResponse' => [
//             'id' => 78,
//             'title' => 'Laudantium Nesciunt Est Molestiae',
//             'roles' => 'Backend Developer',
//             'tags' => [
//                 [
//                     'id' => 16,
//                     'name' => 'Web Site',
//                 ],
//                 [
//                     'id' => 17,
//                     'name' => 'App',
//                 ]
//             ],
//             'images' => [
//                 [
//                     'id' => 12,
//                     'name' => 'blue night',
//                     'fileName' => 'BN.png',
//                     'alt' => 'pic',
//                 ],
//                 [
//                     'id' => 25,
//                     'name' => 'blue day',
//                     'fileName' => 'BD.png',
//                     'alt' => 'pic2',
//                 ]
//             ],
//         ],
//     ],
//     // TODO: add Includes
//     'availableIncludes' => []
// ];
// $paginateObj['availableEndpointParameters']['defaultParameters']['perpage'] = [
//     'parameterNameOptions' => ['perpage','per_page'],
//     'info' => '"perpage" is used to set the number of records returned this end point, the default is 50.'
// ];
// $paginateObj['availableEndpointParameters']['defaultParameters']['page'] = '"page" is used to set the page number or offset of records returned to this end point. For example if you had 50 records per page and set the page parameter to 2 you would receive records from 51 to 100.';
// $paginateObj['availableEndpointParameters']['defaultParameters']['columndata'] = [
//     'parameterNameOptions' => ['columndata','column_data'],
//     'info' => '"columndata" is used as a reference tool to know how to utilize this endpoint\'s parameters. Setting this parameter will activate the column data being returned. "columndata" doesn\'t care about the value set, for example columndata=yes and columndata=no returns the same response.'
// ];
// $paginateObj['availableEndpointParameters']['defaultParameters']['formdata'] = [
//     'parameterNameOptions' => ['formdata','form_data'],
//     'info' => '"formdata" is used as a reference tool to know how to utilize this endpoint\'s parameters for form creation. Setting this parameter will activate the form data being returned. "formdata" doesn\'t care about the value set, for example formdata=yes and formdata=no returns the same response.'
// ];