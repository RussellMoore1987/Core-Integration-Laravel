<?php
    // RequestProcessor
    abstract class RequestProcessor
    {
        protected $request;
        protected $requestValidator;
        protected $queryResolver;
        protected $responseBuilder;

        function __construct(Request $request, RequestValidator $requestValidator, QueryResolver $queryResolver, ResponseBuilder $responseBuilder) 
        {
            $this->request = $request;
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
            $this->responseBuilder->setValidationMetaData($this->requestValidator->validate($this->request));
        }

        protected function resolve() 
        {
            $this->responseBuilder->setResponseData($this->queryResolver->resolve($this->requestValidator->getQueryArguments()));
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


    // validation -> meta data -> pass to response wraper
            // requestValidator->setRejected
            // requestValidator->getRejected
            // requestValidator->setAccepted
            // requestValidator->getAccepted
            // requestValidator->getMetaData
            // requestValidator->setQueryArgument
            // requestValidator->getQueryArguments
                // rest requestValidator->getQueryArguments
                    // [
                    //     'GET' => [
                    //         [column => title, value => someting, dataType => string],
                    //         [column => title, value => someting, dataType => string],
                    //         [column => title, value => someting, dataType => string]
                    //     ]
                    // ]
                // context requestValidator->getQueryArguments
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
            // what if errors / not valid end point -> send with getQueryArguments
            // what about index -> send with getQueryArguments
            // what about single record -> send with getQueryArguments

            // $this->responseBuilder->setResponseData($this->queryResolver->resolve($this->requestValidator->getQueryArguments()));
                // Query
                // Persist
                // index
                // Error / bad endpoint

    // ! working here ***************************************************************************************************** 
    // QueryResolver
    abstract class QueryResolver
    {
        abstract public function resolve(QueryBuilder $queryBuilder, $queryArguments);
    }
    class RestQueryResolver extends QueryResolver
    {
        public function resolve(QueryBuilder $queryBuilder, $queryArguments)
        {
            // check for bad endpoint

            // check for index

            // check for GET
            $this->queryBuilder->query($queryArguments);
            
            // check on prosister
            $this->queryBuilder->Processed($queryArguments);

            return $this->queryBuilder->get();
        }
    }
    class ContextQueryResolver extends QueryResolver
    {
        public function resolve(QueryBuilder $queryBuilder, $queryArguments)
        {
            // loop over $queryArguments
            // make array of queries
            // return queries
        }
    }
    
    // QueryBuilder
    abstract class QueryBuilder
    {
        abstract public function query($formattedData);
    }
    class CILQueryBuilder extends QueryBuilder
    {
        protected $dataTypeDefiner;

        function __construct(DataTypeDefiner $dataTypeDefiner) 
        {
            $this->dataTypeDefiner = $dataTypeDefiner;
        }

        public function query($formattedData)
        {
            // start the query
            // loop over colomns
                // determine type
                // get type QueryBuilder  
                    // $dataType = $this->dataTypeDefiner->define($type);
                    // $queryBuilderClassName = $dataType . "QueryBuilder"
                    // $this->queryBuilder = $queryBuilderClassName::add($this->queryBuilder, $column, $value)
        }
    }

    // TODO: still to add
    // DataTypeDefiner
    // ParameterBuilder
        // DateParameterBuilder
        // StringParameterBuilder
        // IntParameterBuilder
        // FloatIntParameterBuilder


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