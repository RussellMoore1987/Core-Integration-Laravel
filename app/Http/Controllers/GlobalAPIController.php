<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Список Дел:
    // test all items coming back from responseWrapper (feature testing)
    // Get tracer code then refine
    // Make tests generic then specific
        // Generic for common use
        // Specific for application

        // simple
            // relationships - with - include
            // select
            // save

    // to add 
    // includesAccepted
    // includesRejected
    // paramsAccepted
    // paramsRejected
// Список Дел eventually: 
    // nesting relationships
    // select statement


class GlobalAPIController extends Controller
{

    protected $acceptableParameters = [];
    protected $defaultAcceptableParameters = ['perPage', 'page', 'orderBy'];
    protected $class;
    protected $endpointKey;
    protected $classId;
    protected $statusCode;
    protected $indexUrlPath;
    
    protected $includes = [];
    protected $paramsAccepted  = [];
    protected $paramsRejected = [];
    protected $includesAccepted  = [];
    protected $includesRejected  = [];
    protected $defaultPerPage = 30;
    protected $query;
    protected $currentParameter;
    protected $currentParameterType;

    protected $errors = [];
    protected $message = [];

    public $acceptedClasses = [
        'caseStudies' => "App\Models\CaseStudy",
        'projects' => "App\Models\Project",
        'content' => "App\Models\Content",
        'experience' => "App\Models\Experience",
        'images' => "App\Models\Image",
        'posts' => "App\Models\Post",
        'resources' => "App\Models\Resource",
        'categories' => "App\Models\Category",
        'tags' => "App\Models\Tag",
        'skillTypes' => "App\Models\SkillType",
        'skills' => "App\Models\Skill",
        'workHistoryTypes' => "App\Models\WorkHistoryType",
        'workHistory' => "App\Models\WorkHistory"
    ];

    public function processRequest($endpointKey = null, $classId = null, Request $request){

        // Initial set up of key variables
        $this->endpointKey = $endpointKey;
        $this->classId = $classId;
        $this->indexUrlPath = substr($request->url(), 0, strpos($request->url(), "api"));
        
        // TODO:
        if ($this->endpointKey === null) {
            return $this->indexPage();
        }

        if(!$this->validateMainEndpoint()){
            return response()->json([
                'Message' => "'{$this->endpointKey}' is not a valid API endpoint. Please view the documentation at {$this->indexUrlPath} for all available endpoints."
            ], 404);
        }

        $this->checkForIncludes($request->includes);

        $columnData = $this->getAcceptableParameters();
        $this->setAcceptableParameters($columnData);
        $this->initialProcessOfAllParameters($request->all(), $this->acceptableParameters);

        // # which HTTP method
        if ($this->isGetRequest()){
            $this->getRequest();
        }else{
            // # POST, PUT, PATCH, DELETE
            $this->postRequest();
        }
    }

    protected function validateMainEndpoint() {
        if(array_key_exists($this->endpointKey, $this->acceptedClasses)){
            $this->class = $this->acceptedClasses[$this->endpointKey];
            return true;
        }
        return false;
    }

    protected function checkForIncludes($includes = '')
    {
        if ($includes) {
            $includes = explode(',', $includes);
            foreach ($includes as $relationship) {
                if($this->isRelationship($this->class, $relationship)) {
                    $this->includes[] = $relationship;
                    $this->includesAccepted[$relationship] = 'Include Accepted';
                } else {
                    $this->includesRejected[$relationship] = 'Include Not Accepted, not a valid relationship';
                }
            }
        }
    }

    protected function getAcceptableParameters()
    {
        $tempClass = new $this->class();
        $classTableName = $tempClass->gettable();
        $columnData = $this->arrayOfObjectsToArrayOfArrays(DB::select("SHOW COLUMNS FROM {$classTableName}"));
        
        return $columnData;
    }

    protected function arrayOfObjectsToArrayOfArrays(array $arrayOfObjects)
    {
        foreach ($arrayOfObjects as $object) {
            $arrayOfArrays[] = (array) $object;
        }

        return $arrayOfArrays;
    }

    protected function setAcceptableParameters(array $classDBData)
    {
        foreach ($classDBData as $columnArray) {
            foreach ($columnArray as $column_data_name => $value) {
                $column_data_name = strtolower($column_data_name);
                $value = $value === Null ? $value : strtolower($value);

                $this->acceptableParameters[$columnArray['Field']][$column_data_name] = $value; 
            }
        }
    }

    protected function initialProcessOfAllParameters(array $incomingParameters, array $acceptableParameters)
    {
        foreach ($incomingParameters as $parameterName => $parameterValue) {
            if ($parameterName === 'orderBy') {
                $this->processOrderByParameter($parameterValue);
            } else if (array_key_exists($parameterName, $acceptableParameters) || in_array($parameterName, $this->defaultAcceptableParameters)) {
                $this->paramsAccepted[$parameterName] = $parameterValue;
            } else {
                $this->paramsRejected[$parameterName] = $parameterValue;
            } 
        }
    }

    protected function processOrderByParameter($parameterValue)
    {
        $orderByColumns = explode(',', $parameterValue);
        foreach ($orderByColumns as $orderByColumn) {
            if (str_contains($orderByColumn, '::')) {
                $orderByColumnAndOrderIndicator = explode('::', $orderByColumn);
                if (array_key_exists($orderByColumnAndOrderIndicator[0], $this->acceptableParameters)) {
                    $this->paramsAccepted['orderBy'][$orderByColumnAndOrderIndicator[0]] = 'DESC';
                } else {
                    $this->paramsRejected['orderBy'][$orderByColumnAndOrderIndicator[0]] = 'Not acceptable order by parameter';
                }
            } else {
                if (array_key_exists($orderByColumn, $this->acceptableParameters)) {
                    $this->paramsAccepted['orderBy'][$orderByColumn] = 'ASC';
                }
            }
        }
    }

    protected function isGetRequest(){
        if ($this->httpMethod == 'GET'){
            return true;
        }else{
            return false;
        }
    }

    protected function getRequest(){
        
        if ($this->includes || $this->paramsAccepted) {
            return response()->json([$this->queryBuilder()], 200); 
        } else {
            return response()->json([$this->class::paginate($this->defaultPerPage)], 200);
        }
    }

    protected function queryBuilder()
    {

        if ($this->includes) {
            $this->query = $this->class::with($this->includes);
        }

        foreach ($this->paramsAccepted as $parameter => $value) {
            if (in_array($parameter, $this->defaultAcceptableParameters)) {
                $this->processDefaultParameter($parameter, $value);
                continue;
            }
            $this->currentParameter = [$parameter => $value];
            $this->currentParameterType = $this->determineParameterType($this->acceptableParameters[$parameter]['Type']); 
            if (!$this->currentParameterType) {continue;}
            $this->processParameter();
        }

        if ($this->query === null) {
            return $this->class::paginate($this->getPerPage());
        }

        $this->makeAdjustmentsToOrderByParameterToWorkWithTheAppendsMethod();
        
        return $this->query->paginate($this->getPerPage())->appends($this->paramsAccepted);;
    }

    protected function processDefaultParameter($parameter, $value)
    {
        if ($parameter === 'perPage') {
            $this->perPage = is_numeric(request()->perPage) ? (int) request()->perPage : $this->defaultPerPage;
        } elseif ($parameter === 'orderBy') {
            foreach ($this->paramsAccepted['orderBy'] as $parameter => $orderByIndicator) {
                if ($this->query) {
                    $this->query->orderBy($parameter, $orderByIndicator);
                } else{
                    $this->query = $this->class::orderBy($parameter, $orderByIndicator);
                }
            }
        }
        // $parameter === page is handed by Laravel
    }

    protected function determineParameterType($parameterType)
    {
        if ($parameterType == 'date' || $parameterType == 'timestamp' || $parameterType == 'datetime' ||str_contains($parameterType, 'date')) {
            return 'date';
        } elseif (
            str_contains($parameterType, 'varchar') || 
            str_contains($parameterType, 'char') || 
            $parameterType == 'blob' || 
            $parameterType == 'text'
        ) {
            return 'string';
        } elseif (
            $parameterType == 'integer' ||
            $parameterType == 'int' ||
            $parameterType == 'smallint' ||
            $parameterType == 'tinyint' ||
            $parameterType == 'mediumint' ||
            $parameterType == 'bigint'
        ) {
            return 'int';
        } elseif (
            $parameterType == 'decimal' ||
            $parameterType == 'numeric' ||
            $parameterType == 'float' ||
            $parameterType == 'double'
        ) {
            return 'float';
        } else {
            foreach ($this->currentParameter as $parameter => $value) {
                unset($this->paramsAccepted[$parameter]);
                $this->paramsRejected[$parameter] = "Column type for \"{$parameter}\" is not supported in the query processor, contact the API administer for help!";
            }
            return false;
        }
    }

    protected function processParameter()
    {
       switch ($this->currentParameterType) {
           case 'date': $this->dateQueryBuilder(); break;
           case 'string': $this->stringQueryBuilder(); break;
           case 'int': $this->intQueryBuilder(); break;
           case 'float': $this->floatQueryBuilder(); break;
           case 'bool': $this->boolQueryBuilder(); break;
       }
    }

    protected function makeAdjustmentsToOrderByParameterToWorkWithTheAppendsMethod()
    {
        // ! start here ************************************************************* finish
        if (isset($this->acceptableParameters['orderBy'])) {
            $oderByString = '';
            foreach ($this->acceptableParameters['orderBy'] as $orderByColumnName => $OrderByIndicator) {
                
                $oderByString .= $orderByColumnName;
            }
        }
    } 

    protected function isRelationship($class, $relationship) {
        return method_exists($class, $relationship);
    }

    protected function postRequest(){
        $this->results = "'{$this->httpMethod}' is not an accepted method. Please view the documentation at {$this->url}.";
        $this->errors['statusMessage'] = 'Bad Request';
        $this->errors['errorMessage'] = "{$this->httpMethod} not valid";
    }

    protected function responseBuilder(){

        // * constructing wrapper

        $success = $this->errors ? false : true;
        // 404, 403, 400, 200, 201
        // ? https://restfulapi.net/http-methods/#get
        $statusCode = $this->statusCode ?? ($success ? 200 : 400);
        
        $paramsAccepted = $this->paramsAccepted ?? [];
        $paramsRejected = $this->paramsRejected ?? [];

        $currentPage = $this->currentPage ?? null;
        $totalPages = $this->totalPages ?? null;
        $requestPerPage = $this->requestPerPage ?? null;

        $totalResults = $this->totalResults ?? null;

        $responseData = [
            'success' => $success,
            'statusCode' => $statusCode,
            'errors' => $this->errors,
            'requestMethod' => $this->httpMethod,
            'paramsSent' => [
                'All' => request()->all(),
                'GET' => $_GET,
                'POST' =>  $_POST
            ],
            'paramsAccepted' => $paramsAccepted,
            'paramsRejected' => $paramsRejected,
            'mainEndpoint' => $this->endpointKey,
            'endpoint' => $this->endpoint,
            'endpointUrl' => $this->url,
            'pageInfo' => [
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'requestPerPage' => $requestPerPage
            ],
            'totalResults' => $this->totalResults,
            'results' => $this->results
        ];

        return response()->json($responseData, $statusCode);
    }
}
