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
        // loads __construct(RestRequestValidator $requestValidator, RestQueryResolver $queryResolver, RestResponseBuilder $responseBuilder)
    }
    class ContextRequestProcessor extends RequestProcessor {
        // uses serves provider Located ...
        // loads __construct(ContextRequestValidator $requestValidator, ContextQueryResolver $queryResolver, ContextResponseBuilder $responseBuilder)
    }

    // requestValidator
    abstract class RequestValidator 
    {

        protected $requestDataPrepper;
        protected $acceptableParameters;
        protected $class;
        protected $endpoint;
        protected $errors = [];
        protected $validatedMetaData;
        
        
        protected $rejectedParameters = [];
        protected $acceptedParameters = [];
        protected $queryArguments = [];

        function __construct(RequestDataPrepper $requestDataPrepper) 
        {
            $this->requestDataPrepper = $requestDataPrepper;
        }   

        public function validate()
        {
            $this->requestDataPrepper->prep();

            $this->validateRequest($this->requestDataPrepper->getPreppedData());

            return $this->validatedMetaData;
        }

        protected function validateRequest($request)
        {
            $this->setUpPreppedRequest($request);
            $this->getAcceptableParameters();

            $this->validateEndPoint();
            $this->validateMethodCalls();
            $this->validateIncludes();
            $this->validatePerPageParameter();
            $this->validateOrderByParameter();
            $this->validateSelectParameter();
            $this->validateAllOtherParameter();
            $this->setValidatedMetaData();
        }

        protected function setValidatedMetaData()
        {
            $validatedRequestMetaData['rejectedParameters'] = $this->getRejectedParameters();
            $validatedRequestMetaData['acceptedParameters'] = $this->getAcceptedParameters();
            $validatedRequestMetaData['errors'] = $this->errors;
            $validatedRequestMetaData['queryArguments'] = $this->getQueryArguments();
            $this->validatedMetaData = $validatedRequestMetaData;
        }

        protected function setUpPreppedRequest($request)
        {
            $this->class = $request['class'];
            $this->endpoint = $request['endpoint'];
            $this->methodCalls = $request['methodCalls'] ?? [];
            $this->includes = $request['includes'] ?? [];
            $this->perPageParameter = $request['perPageParameter'] ?? 30;
            $this->orderByParameter = $request['orderByParameter'] ?? [];
            $this->selectParameter = $request['selectParameter'] ?? [];
            $this->otherParameter = $request['otherParameter'] ?? [];
        }

        // TODO: Search for field that's not displayed *hidden, kinda like a Social Security number
        protected function getAcceptableParameters()
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

        protected function setRejectedParameter()
        {

        }
        public function getRejectedParameters()
        {

        }
        protected function setAcceptedParameter()
        {

        }
        public function getAcceptedParameters()
        {

        }
        protected function setQueryArgument()
        {

        }
        public function getQueryArguments()
        {

        }
        public function getValidatedQueryData()
        {

        }
    }
    class RestRequestValidator extends RequestValidator
    {
        // uses serves provider Located ...
        // loads function __construct(RestRequestDataPrepper $restRequestDataPrepper)
    }
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

    abstract class RequestDataPrepper
    {
        protected $request;

        function __construct(Request $request) 
        {
            $this->request = $request;
        }  

        abstract public function prep();
        abstract public function getPreppedData();
    }
    class RestRequestDataPrepper extends RequestDataPrepper
    {
        public function prep()
        {
            // possess rest request
        }

        public function getPreppedData()
        {
            // get rest request
        }
    }
    class ContextRequestDataPrepper extends RequestDataPrepper
    {
        public function prep()
        {
            // possess rest request
        }

        public function getPreppedData()
        {
            // get rest request
        }
    }

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

        // uses serves provider Located ...
        // loads function __construct(CILQueryAssembler $queryAssembler, CILQueryPersister $queryPersister, RestQueryIndex $queryIndex, CILQueryDeleter $queryDeleter)

        public function resolve($validatedQueryData)
        {
            // bad endpoint / errors
            $query = $validatedQueryData->errors ? $validatedQueryData->errors : NULL;

            // get column data

            // get form data

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
        // uses serves provider Located ...
        // loads function __construct(CILQueryAssembler $queryAssembler, CILQueryPersister $queryPersister, ContextQueryIndex $queryIndex, CILQueryDeleter $queryDeleter)

        public function resolve($validatedQueryData)
        {
            $queries = [];

            foreach ($validatedQueryData as $queryArguments) {

                // bad endpoint
                $query = $queryArguments->errors ? $queryArguments->errors : NULL;

                // get column data

                // get form data
    
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
        protected $clauseBuilderFactory;
        protected $queryBuilder;

        function __construct(ClauseBuilderFactory $clauseBuilderFactory) 
        {
            $this->clauseBuilderFactory = $clauseBuilderFactory;
        }

        public function query($validatedQueryData)
        {
            // start laravel builder
            // $this->queryBuilder

            // add includes

            // loop over column arguments
                // $clauseBuilder = $this->clauseBuilderFactory->getClauseBuilder($type);
                // $this->queryBuilder = $clauseBuilder->build($this->queryBuilder, $column, $value)
            
            // return query;
            // return $this->queryBuilder->get();
        }
    }

    class ClauseBuilderFactory
    {
        public function getClauseBuilder($type)
        {
            // find and return type
        }  
    }

    interface ClauseBuilder {
        public function build(Builder $queryBuilder, $column, $value) : Builder;
    }
    class StringWhereClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }
    class DateWhereClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }
    class IntWhereClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }
    class FloatWhereClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }
    class OrderByClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }
    class SelectClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }
    class IncludesClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }
    class MethodCallsClauseBuilder implements ClauseBuilder
    {
        public function build(Builder $queryBuilder, $column, $value) : Builder
        {
            // code...
        }
    }


    interface QueryPersister {
        public function persist($validatedQueryData);
    }
    class CILQueryPersister implements QueryPersister
    {
        public function persist($validatedQueryData)
        {
            if ($validatedQueryData->action == 'PATCH') {
                // find record
                // merge filds
                // save & validate new record
            } else {
                // save & validate record
                    // id -> update PUT
                    // no id -> new POST
            }
        }
    }

    // ! working here ********************************************************************
    interface QueryIndex {
        public function get();
    }
    class RestQueryIndex implements QueryIndex
    {
        public function get()
        {
            // get rest index
        }
    }
    class ContextQueryIndex implements QueryIndex
    {
        public function get()
        {
            // get context index
        }
    }

    interface QueryDeleter {
        public function delete();
    }
    class CILQueryDeleter implements QueryDeleter
    {
        public function delete()
        {
            // delete record
        }
    }


    // ResponseBuilder
    interface ResponseBuilder
    {
        public function setValidationMetaData($metaData);
        public function setResponseData($queryResult);
        public function make();
    }
    class RestResponseBuilder implements ResponseBuilder
    {
        public function setValidationMetaData($metaData)
        {

        }

        public function setResponseData($queryResult)
        {

        }

        public function make()
        {

        }
    }
    class ContextResponseBuilder implements ResponseBuilder
    {
        public function setValidationMetaData($metaData)
        {

        }

        public function setResponseData($queryResult)
        {

        }

        public function make()
        {

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






    
?>






