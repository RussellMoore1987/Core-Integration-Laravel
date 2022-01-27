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











        // # version 1
        // methods in process() 
        protected function validate() 
        {
            $this->validateAndGetMetadata();
        }
        protected function resolve() 
        {
            $this->getValidatedQueryData();
            $this->resolveQuery();
        }
        protected function respond()
        {
            $this->setMetaDataInResponse();
            $this->setQueryResultInResponse();
            return $this->createResponse();
        }

        // methods in resolve()
        private function validateAndGetMetadata()
        {
            $this->metaData = $this->requestValidator->validate($this->request);
        }

        // methods in resolve()
        private function getValidatedQueryData() {
            $this->queryArgs = $this->requestValidator->getValidatedQueryData();
        }
        private function resolveQuery() {
            $this->queryResult = $this->queryResolver->resolve($this->queryArgs);
        }

        
        // methods in respond()
        protected function createResponse() {
            return $this->responseBuilder->make();
        }
        private function setMetaDataInResponse()
        {
            $this->responseBuilder->setValidationMetaData($this->metaData);
        }
        private function setQueryResultInResponse() {
            $this->responseBuilder->setResponseData($this->queryResult);
        }







        // # version 2
        // methods in process() 
        // ====================================================================================
        protected function validate() 
        {
            $this->validateAndGetMetadata();
        }
        protected function resolve() 
        {
            $this->getValidatedQueryData();
            $this->resolveQuery();
        }
        protected function respond()
        {
            $this->setMetaDataInResponse();
            $this->setQueryResultInResponse();
            return $this->createResponse();
        }
        // ====================================================================================

        // methods in resolve() 
        // ====================================================================================
        private function validateAndGetMetadata()
        {
            $this->metaData = $this->requestValidator->validate($this->request);
        }
        // ====================================================================================

        // methods in resolve() 
        // ====================================================================================
        private function getValidatedQueryData() {
            $this->queryArgs = $this->requestValidator->getValidatedQueryData();
        }
        private function resolveQuery() {
            $this->queryResult = $this->queryResolver->resolve($this->queryArgs);
        }
        // ====================================================================================

        // methods in respond()
        // ====================================================================================
        protected function createResponse() {
            return $this->responseBuilder->make();
        }
        private function setMetaDataInResponse()
        {
            $this->responseBuilder->setValidationMetaData($this->metaData);
        }
        private function setQueryResultInResponse() {
            $this->responseBuilder->setResponseData($this->queryResult);
        }
        // ====================================================================================













        // # version 3
        // @ methods in process() 
        protected function validate() 
        {
            $this->validateAndGetMetadata();
        }
        protected function resolve() 
        {
            $this->getValidatedQueryData();
            $this->resolveQuery();
        }
        protected function respond()
        {
            $this->setMetaDataInResponse();
            $this->setQueryResultInResponse();
            return $this->createResponse();
        }

        // @ methods in resolve() 
        private function validateAndGetMetadata()
        {
            $this->metaData = $this->requestValidator->validate($this->request);
        }

        // @ methods in resolve() 
        private function getValidatedQueryData() {
            $this->queryArgs = $this->requestValidator->getValidatedQueryData();
        }
        private function resolveQuery() {
            $this->queryResult = $this->queryResolver->resolve($this->queryArgs);
        }

        // @ methods in respond()
        private function setMetaDataInResponse()
        {
            $this->responseBuilder->setValidationMetaData($this->metaData);
        }
        private function setQueryResultInResponse() {
            $this->responseBuilder->setResponseData($this->queryResult);
        }
        protected function createResponse() {
            return $this->responseBuilder->make();
        }





        // or



        // # version 4
        protected function validate() 
        {
            $this->responseBuilder->setValidationMetaData($this->requestValidator->validate($this->request));
        }

        protected function resolve() 
        {
            $this->responseBuilder->setResponseData($this->queryResolver->resolve($this->requestValidator->getValidatedQueryData()));
        }

        protected function respond()
        {
            return $this->responseBuilder->make();
        }




        // or



        // # version 5
        protected function validate() 
        {
            $metaData = $this->requestValidator->validate($this->request);
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