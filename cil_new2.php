<?php
    // RequestProcessor
    abstract class RequestProcessor
    {
        protected $requestValidator;
        protected $queryResolver;
        protected $responseBuilder;

        function __construct(RequestValidator $requestValidator, QueryResolver $queryResolver, ResponseBuilder $responseBuilder) 
        {
            $this->requestValidator = $requestValidator;
            $this->queryResolver = $queryResolver;
            $this->responseBuilder = $responseBuilder;
        }

        /**
         * @return The return string is a JSON string
         */
        public function process() : string
        {
            $this->validate();
            $this->resolve();
            return $this->respond();
        }

        protected function validate() 
        {
            $metaData = $this->requestValidator->validate();
            $this->responseBuilder->setValidationMetaData($metaData);
        }

        protected function resolve() 
        {
            $ValidatedQueryData = $this->requestValidator->getValidatedQueryData();
            $queryResult = $this->queryResolver->resolve($ValidatedQueryData);
            $this->responseBuilder->setResponseData($queryResult);
        }

        protected function respond()
        {
            return $this->responseBuilder->make();
        }
    }
    class RestRequestProcessor extends RequestProcessor {
        // uses serves provider Located ...
        // loads function __construct(Request $request, RestRequestFormatter, CILQueryBuilder, RestResponseWrapper)
    }
    class ContextRequestProcessor extends RequestProcessor {
        // uses serves provider Located ...
        // loads function __construct(Request $request, ContextRequestFormatter, CILQueryBuilder, ContextResponseWrapper)
    }

    // requestValidator
    abstract class RequestValidator 
    {

        protected $request;
        protected $acceptableParameters;
        protected $class;
        protected $endpoint;

        function __construct(Request $request) 
        {
            $this->request = $request;
        }   

        abstract protected function setRejectedParameter();
        abstract public function getRejectedParameters();
        abstract protected function setAcceptedParameter();
        abstract public function getAcceptedParameters();
        abstract protected function setQueryArgument();
        abstract public function getQueryArguments();
        abstract public function getValidatedQueryData();

        // Shared logic
        public function validate()
        {
            // Unique preparations
                // prep data

            // defalt prosses
                // loop???
            $this->validateEndPoint();
            $this->validateMethodCalls();
            $this->validateIncludes();
            $this->validatePerPageParameter();
            $this->validateOrderByParameter();
            $this->validateSelectParameter();
            $this->validateAllOtherParameter();
                // get/set Groups of validated data
            
            return $this->getValidatedMetaData();
        }
        
        // TODO: Search for field that's not displayed *hidden, kinda like a Social Security number
        protected function getAcceptableParameters(Model $class)
        {
            // set $this->acceptableParameters
        }

        protected function validateEndPoint()
        {
            // see if end point is in config('coreintegration.acceptedclasses')
        }

        protected function validateMethodCalls()
        {
            // class have method
        }

        protected function validateIncludes()
        {
            // class have Includes
        }

        protected function validatePerPageParameter()
        {
            // code...
        }

        protected function validateOrderByParameter()
        {
            // code...
            // use $this->acceptableParameters
        }

        protected function validateSelectParameter()
        {
            // code...
            // use $this->acceptableParameters
        }

        protected function validateAllOtherParameter()
        {
            // code...
            // use $this->acceptableParameters
        }
    }
    class RestRequestValidator extends RequestValidator
    {
        public function validate()
        {
            // get/validate class 
            // get/validate id ? nullable 
            $this->validateEndPoint();

            // get/validate long path 
            $this->validateEndPointFullPath();
            
            // get/validate methodCalls parameters ?? will this work or We need to build out something to handle this
                // setRejectedParameter();
                // setAcceptedParameter();
                // setQueryArgument();
            $this->validateMethodCalls();
            
            // get/validate includes parameters
                // setRejectedParameter();
                // setAcceptedParameter();
                // setQueryArgument();
            $this->validateIncludes();
            
            // get/validate perPage parameter
                // setAcceptedParameter();
                // setQueryArgument();
            $this->validatePerPageParameter();
            
            // get/validate orderBy parameters
                // setRejectedParameter();
                // setAcceptedParameter();
                // setQueryArgument();
            $this->validateOrderByParameter();

            // get/validate select parameters
                // setRejectedParameter();
                // setAcceptedParameter();
                // setQueryArgument();
            $this->validateSelectParameter();
            
            // get/validate get parameters
                // setRejectedParameter();
                // setAcceptedParameter();
                // setQueryArgument();
            $this->validateAllOtherParameter();

            return $this->getValidatedMetaData();
        }
    }
    class ContextRequestValidator extends RequestValidator
    {
        public function validate()
        {
            // * loop over requests and validate each one
                // get/validate class 
                // get/validate id ? nullable 
                $this->validateEndPoint();

                // get/validate methodCalls parameters ?? will this work or We need to build out something to handle this
                    // setRejectedParameter();
                    // setAcceptedParameter();
                    // setQueryArgument();
                $this->validateMethodCalls();
                
                // get/validate includes parameters
                    // setRejectedParameter();
                    // setAcceptedParameter();
                    // setQueryArgument();
                $this->validateIncludes();
                
                // get/validate perPage parameter
                    // setAcceptedParameter();
                    // setQueryArgument();
                $this->validatePerPageParameter();
                
                // get/validate orderBy parameters
                    // setRejectedParameter();
                    // setAcceptedParameter();
                    // setQueryArgument();
                $this->validateOrderByParameter();

                // get/validate select parameters
                    // setRejectedParameter();
                    // setAcceptedParameter();
                    // setQueryArgument();
                $this->validateSelectParameter();
                
                // get/validate get parameters
                    // setRejectedParameter();
                    // setAcceptedParameter();
                    // setQueryArgument();
                $this->validateAllOtherParameter();

            return $this->getValidatedMetaData();
        }
    }
    

    // responseBuilder->setValidationMetaData
    // responseBuilder->setResponseData
    // responseBuilder->make

    // validation -> meta data -> pass to response wraper
            // requestValidator->validate
            // requestValidator->setRejected
            // requestValidator->getRejected
            // requestValidator->setAccepted
            // requestValidator->getAccepted
            // requestValidator->getMetaData
            // requestValidator->setQueryArgument
            // requestValidator->getValidatedQueryData
                // rest requestValidator->getValidatedQueryData
                    // [
                    //     'GET' => [
                    //         [column => title, value => someting, dataType => string],
                    //         [column => title, value => someting, dataType => string],
                    //         [column => title, value => someting, dataType => string]
                    //     ]
                    // ]
                // context requestValidator->getValidatedQueryData
                    // [
                    //     'getUsers' => [
                    //         'GET' => [
                    //             [column => title, value => someting, dataType => string],
                    //             [column => title, value => someting, dataType => string],
                    //             [column => title, value => someting, dataType => string]
                    //         ]
                    //     ],
                    //     'setprojects' => [
                    //         'SET' => [
                    //             [column => title, value => someting, dataType => string],
                    //             [column => title, value => someting, dataType => string],
                    //             [column => title, value => someting, dataType => string]
                    //         ]
                    //     ]
                    // ]

        // $this->requestValidator->validate($this->request);
            // what if errors / not valid end point -> send with getValidatedQueryData
            // what about index -> send with getValidatedQueryData
            // what about single record -> send with getValidatedQueryData

            // $this->responseBuilder->setResponseData($this->queryResolver->resolve($this->requestValidator->getValidatedQueryData()));
                // Query
                // Persist
                // index
                // Error / bad endpoint

     
    // QueryResolver
    abstract class QueryResolver
    {
        protected $queryAssembler;
        protected $queryPersister;
        protected $queryIndex;
        protected $queryDeleter;

        function __construct(QueryAssembler $queryAssembler, QueryPersister $queryPersister, QueryIndex $queryIndex, QueryDeleter $queryDeleter) 
        {
            $this->queryAssembler = $queryAssembler;
            $this->queryPersister = $queryPersister;
            $this->queryIndex = $queryIndex;
            $this->queryDeleter = $queryDeleter;
        }

        abstract public function resolve($validatedQueryData);
    }
    class RestQueryResolver extends QueryResolver
    {
        public function resolve($validatedQueryData)
        {
            // bad endpoint
            $query = $validatedQueryData->errors ? $validatedQueryData->errors : NULL;

            // index
            $query = $query === NULL && $validatedQueryData->action == 'INDEX' ? $this->queryIndex->get() : NULL;

            // GET
            $query = $query === NULL && $validatedQueryData->action == 'GET' ? $this->queryAssembler->query($validatedQueryData) : NULL;
            
            // persist - POST = add, PUT = update, PATCH = copy
            $query = $query === NULL && in_array($validatedQueryData->action, ['POST', 'PUT', 'PATCH']) ? $this->queryPersister->persist($validatedQueryData) : NULL;

            // delete
            $query = $query === NULL && $validatedQueryData->action == 'DELETE' ? $this->queryDeleter->delete($validatedQueryData) : NULL;

            // return errors???
            return $query;
        }
    }
    class ContextQueryResolver extends QueryResolver
    {
        public function resolve($validatedQueryData)
        {
            $queries = [];

            foreach ($validatedQueryData as $queryArguments) {

                // bad endpoint
                $query = $queryArguments->errors ? $queryArguments->errors : NULL;
    
                // index
                $query = $query === NULL && $queryArguments->action == 'index' ? $this->queryIndex->get() : NULL;
    
                // GET
                $query = $query === NULL && $queryArguments->action == 'get' ? $this->queryAssembler->query($queryArguments) : NULL;
                
                // persist save = add/update
                $query = $query === NULL && in_array($queryArguments->action, ['save', 'copy']) ? $this->queryPersister->persist($queryArguments) : NULL;
    
                // delete
                $query = $query === NULL && $queryArguments->action == 'delete' ? $this->queryDeleter->delete($queryArguments) : NULL;

                $queries[] = $query;
            }
            
            return $queries;
        }
    }

    // $queryArguments = $requestedQueryData
    // $queryArguments = $queryData
    // $queryArguments = $validatedQueryData // ***
    
    // QueryAssembler
    interface QueryAssembler {
        public function query($validatedQueryData);
      }
    class CILQueryAssembler implements QueryAssembler
    {
        protected $dataTypeDefiner;
        protected $query;

        function __construct(DataTypeDefiner $dataTypeDefiner) 
        {
            $this->dataTypeDefiner = $dataTypeDefiner;
        }

        public function query($validatedQueryData)
        {
            // start laravel builder
            // $this->query

            // add includes

            // loop over column arguments
                // determine type
                // $dataType = $this->dataTypeDefiner->define($type);
                
                // Get the specific dataType query builder
                // $queryBuilderClassName = $dataType . "QueryBuilder"
                    // $this->queryBuilder = $queryBuilderClassName::build($this->queryBuilder, $column, $value)
            
            // return query;
            // return $this->queryBuilder->get();
        }
    }

// CILQueryAssembler
    // definer
    // seter

    // DataTypeDefiner = QueryAssembler
    // stringPerimeterBuilder
    // idWhereClauseBuilder
    // stringWhereClauseBuilder
    // ...
    // orderByClauseBuilder
    // selectClauseBuilder






    // TODO: still to add
    // DataTypeDefiner
    // ParameterBuilder
        // DateQueryBuilder
        // StringQueryBuilder
        // IntQueryBuilder
        // FloatIntQueryBuilder


    // ResponseWrapper
    abstract class ResponseWrapper
    {
        abstract public function wrap($formattedData, $query);
    }
    class RestResponseWrapper extends ResponseWrapper
    {
        public function wrap($formattedData, $query)
        {
            // return final processing of data    
        }
    }
    class ContextResponseWrapper extends ResponseWrapper
    {
        public function wrap($formattedData, $query)
        {
            // return final processing of data    
        }
    }
    
    



    // Service provider dependency injection
    Route::any("v1/{class?}/{id?}/{path?}", [RestRequestProcessor::class, 'process'])->where('path', '.+'); 





















    // For Fox
    Route::any("v1/payees", [GlobalAPIController::class, 'payees'])->where('path', '.+');
    Route::any("v1/payees/{id?}", [GlobalAPIController::class, 'payees'])->where('path', '.+');
    Route::any("v1/paystubs", [GlobalAPIController::class, 'paystubs'])->where('path', '.+');
    Route::any("v1/paystubs/{id?}", [GlobalAPIController::class, 'paystubs'])->where('path', '.+');
    Route::any("v1/{class?}/{id?}/{path?}", [GlobalAPIController::class, 'custom'])->where('path', '.+'); 

    class GlobalAPIController
    {

        function __construct(RestRequestProcessor $restRequestProcessor) 
        {
            $this->restRequestProcessor = $restRequestProcessor;
        }

        public function payees()
        {
            $payees = $this->restRequestProcessor->process();

            // proses
            // ...

            return $payees;
        }

        public function paystubs()
        {
            return $this->restRequestProcessor->process();
        }

        public function custom()
        {
            // find main class
            // try catch
            // call function $this->$mainClass()
            // url payroll/33/paystub/16/payees
            // call function $this->payees()
            return $this->$mainClass();
        }
    }

















































     // abstract RequestProcessor
     abstract class RequestProcessor
     {
         protected $request;
         protected $requestFormatter;
         protected $formattedData;
         protected $queryAssembler;
         protected $query;
         protected $responseWrapper;
 
         function __construct(Request $request, RequestFormatter $requestFormatter, QueryAssembler $queryAssembler, ResponseWrapper $responseWrapper) 
         {
             $this->request = $request;
             $this->requestFormatter = $requestFormatter;
             $this->queryAssembler = $queryAssembler;
             $this->responseWrapper = $responseWrapper;
         }
 
         /**
          * @return The return string is a JSON string
          */
         public function process() : string
         {
             $this->prepareRequestData();
             $this->retrieveRequestedData();
             return $this->packageReturnData();
         }
 
         protected function prepareRequestData() 
         {
             $this->formattedData = $this->requestFormatter->format($this->request);
         }
 
         protected function retrieveRequestedData() 
         {
             $this->query = $this->queryAssembler->query($this->formattedData);
         }
 
         protected function packageReturnData()
         {
             return $this->responseWrapper->wrap($this->formattedData, $this->query);
         }
 
         // abstract protected function prepareRequestData(Request $request);
 
         // abstract protected function retrieveRequestedData(QueryBuilder $queryBuilder, $prepareData);
 
         // abstract protected function packageReturnData(ResponseWrapper $responseWrapper, $prepareData, $query);
     }
    
?>