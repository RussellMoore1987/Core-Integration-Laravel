<?php

namespace App\Http\Controllers;

use App\QueryBuilders\StringQueryBuilder;
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

    // add
        // get form data

// Список Дел eventually:
    // nesting relationships
    // select statement

// TODO: Simplify this controller. It should be much thinner than it is...
class GlobalAPIController extends Controller
{

    protected $acceptableParameters = [];
    protected $defaultAcceptableParameters = ['perPage', 'page', 'orderBy'];
    protected $class;
    protected $endpointKey;
    protected $classId;
    protected $statusCode;
    protected $indexUrlPath;
    protected $url;
    protected $httpMethod;

    protected $includes = [];
    protected $paramsAccepted  = [];
    protected $paramsRejected = [];
    protected $includesAccepted  = [];
    protected $perPageParameter = 30;
    protected $query;
    protected $currentParameter;
    protected $currentParameterType;

    protected $errors = [];
    protected $message = [];

    public $acceptedClasses;

    public function processRequest($endpointKey = null, $classId = null, Request $request){
        // Initial set up of key variables
        $this->endpointKey = $endpointKey;
        $this->classId = $classId;
        $this->acceptedClasses = config('coreintegration.acceptedclasses');
        $this->indexUrlPath = $endpointKey !== NULL ? substr($request->url(), 0, strpos($request->url(), $this->endpointKey)) : $request->url();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? request()->method() ?? null;
        $this->url = $request->url();

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
        // If they just want to see what columns are available for this in point
        if ($request->availableColumns) {
            return response()->json([
                'availableColumns' => array_keys($this->acceptableParameters)
            ], 200);
        }
        $this->initialProcessOfAllParameters($request->all(), $this->acceptableParameters);

        // # which HTTP method
        if ($this->isGetRequest()){
            return $this->getRequest();
        }else{
            // # POST, PUT, PATCH, DELETE
            return $this->postRequest();
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
                // TODO: improve isRelationship, Not specific enough to real relationships
                if($this->isRelationship($this->class, $relationship)) {
                    $this->includes[] = $relationship;
                    $this->includesAccepted[$relationship] = 'Include Accepted';
                } else {
                    $this->paramsRejected['includes'][$relationship] = 'Include Not Accepted, not a valid relationship';
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
                if ($parameterName != 'includes') {
                    $this->paramsRejected[$parameterName] = $parameterValue;
                }
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
                } else {
                    $this->paramsRejected['orderBy'][$orderByColumn] = 'Not acceptable order by parameter';
                }
            }
        }
    }

    protected function isGetRequest(){
        return $this->httpMethod == 'GET';
    }

    protected function getRequest(){

        if ($this->includes || $this->paramsAccepted) {
            return response()->json($this->responseBuilder($this->queryBuilder()), 200);
        } else {
            return response()->json($this->responseBuilder($this->class::paginate($this->perPageParameter)), 200);
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
            $this->currentParameterType = $this->determineParameterType($this->acceptableParameters[$parameter]['type']);
            if (!$this->currentParameterType) {continue;}
            $this->processParameter();
        }

        if ($this->query === null) {
            return $this->class::paginate($this->perPageParameter);
        }

        return $this->query->paginate($this->perPageParameter)->withQueryString();
    }

    protected function processDefaultParameter($parameter, $value)
    {
        if ($parameter === 'perPage') {
            $this->perPage = is_numeric(request()->perPage) ? (int) $value : $this->perPageParameter;
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
        if (
            $parameterType == 'date' ||
            $parameterType == 'timestamp' ||
            $parameterType == 'datetime' ||
            str_contains($parameterType, 'date')
        ) {
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
        // TODO: Replace this with polymorphism...
       switch ($this->currentParameterType) {
           case 'date': $this->dateQueryBuilder(); break;
           case 'string': $this->stringQueryBuilder(); break;
           case 'int': $this->intQueryBuilder(); break;
           case 'float': $this->floatQueryBuilder(); break;
       }
    }

    protected function dateQueryBuilder()
    {
        // Should only ever one parameter at a time this is just an easy way to get the key and value
        foreach ($this->currentParameter as $parameter_name => $date) {
            if (str_contains($date, '::')) {
                $this->formatDateStringWithOperator($parameter_name, $date);
            } else {
                $date = date('Y-m-d', strtotime($date));
                if ($this->query) {
                    $this->query->whereDate($parameter_name, $date);
                } else{
                    $this->query = $this->class::whereDate($parameter_name, $date);
                }
            }
        }
    }

    // TODO: Finish implementing this
    protected function stringQueryBuilder()
    {
        // TODO: Use dependancy injection rather than hard coded class instanitation here...
        $queryBuilder = new StringQueryBuilder;
        // Should only ever one parameter at a time this is just an easy way to get the key and value
        // TODO: Find a better way to access the key and value without a for loop. It gives the wrong impression....
        foreach ($this->currentParameter as $parameter_name => $string) {
            $queryBuilder->parse($string);
            $queryBuilder->setColumn($parameter_name);
            $queryBuilder->setModel($this->class);
            $this->query = $queryBuilder->build();
        }
    }

    protected function formatDateStringWithOperator($parameter_name, $date_string)
    {
        $date_array = explode('::', $date_string);
        if (str_contains($date_array[0], ',')) {
            $between_dates = explode(',', $date_array[0]);
            $date[] = date('Y-m-d', strtotime($between_dates[0])); // Beginning of day
            $date[] = date('Y-m-d H:i:s', strtotime("tomorrow", strtotime($between_dates[1])) - 1); // End of day
        } else {
            $date = date('Y-m-d', strtotime($date_array[0]));
        }
        $date_action = $date_array[1];

        $this->applyDateComparisonOperator($date_action, $date, $parameter_name);
    }

    protected function applyDateComparisonOperator($date_action, $date, $parameter_name)
    {
        if (strtolower($date_action) == strtolower('greaterThan') || strtolower($date_action) == strtolower('GT')) {
            $comparison_operator = '>';
        } else if (strtolower($date_action) == strtolower('greaterThanOrEqual') || strtolower($date_action) == strtolower('GTE')) {
            $comparison_operator = '>=';
        } else if (strtolower($date_action) == strtolower('lessThan') || strtolower($date_action) == strtolower('LT')) {
            $comparison_operator = '<';
        } else if (strtolower($date_action) == strtolower('lessThanOrEqual') || strtolower($date_action) == strtolower('LTE')) {
            $comparison_operator = '<=';
        } else if (strtolower($date_action) == strtolower('Between') || strtolower($date_action) == strtolower('BT')) {
            if ($this->query) {
                $this->query->whereBetween($parameter_name, $date);
            } else{
                $this->query = $this->class::whereBetween($parameter_name, $date);
            }
            return true;
        } else {
            $comparison_operator = '=';
        }

        if ($this->query) {
            $this->query->whereDate($parameter_name, $comparison_operator, $date);
        } else{
            $this->query = $this->class::whereDate($parameter_name, $comparison_operator, $date);
        }
    }

    protected function isRelationship($class, $relationship) {
        return method_exists($class, $relationship);
    }

    protected function postRequest(){
        return response()->json([
            "Content" => "'{$this->httpMethod}' is not an accepted method. Please view the documentation at {$this->indexUrlPath}."
        ], 400);
    }

    protected function responseBuilder(Object $paginateObj){

        $paginateObj = json_decode($paginateObj->toJson(), true);

        $success = $this->errors ? false : true;

        $paramsAccepted = array_merge($this->paramsAccepted, $this->includesAccepted);
        $paramsRejected = $this->paramsRejected;

        $responseData = [
            'success' => $success,
            'errors' => $this->errors,
            'requestMethod' => $this->httpMethod,
            'paramsSent' => [
                'All' => request()->all(),
                'GET' => $_GET,
                'POST' =>  $_POST
            ],
            'paramsAccepted' => $paramsAccepted,
            'paramsRejected' => $paramsRejected,
            'endpoint' => $this->endpointKey,
            'indexUrlPath' => $this->indexUrlPath,
            'endpointUrl' => $this->url
        ];

        return array_merge($responseData, $paginateObj);
    }


    public function indexPage()
    {
        return response()->json([
            "Content" => "index Page"
        ], 200);
    }
}
