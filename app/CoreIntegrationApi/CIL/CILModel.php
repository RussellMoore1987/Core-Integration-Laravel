<?php
    
    namespace App\CoreIntegrationApi\CIL;

    use Illuminate\Support\Facades\Validator;

    trait CILModel
    {
        protected $validator;
        protected $validationRulesToValidate;
        protected $redirect;
        protected $data;

        // https://laravel.com/docs/8.x/validation#available-validation-rules

        public function validateAndSave(array $data = [], $redirect = false)
        {
            $keyName = $this->getKeyName();
            $validationRulesToValidate = isset($data[$keyName]) ? $this->getValidationRules('update') : $this->getValidationRules('create');

            // ! start on required sometimes validation rules
            // TODO: add validation messages
            $validator = $this->validate($data, $validationRulesToValidate);
            if ($validator->fails()) {
                if ($redirect) {
                    // TODO: Not tested, need to test
                    return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
                }

                return $validator->errors();
            }
            

            // TODO: test all non class properties
            // test mix and mach
            $this->setValidatedProperties($validator->validated());

            $this->save();

            return [];
        }

        public function validate($data, $validationRulesToValidate = null)
        {
            $validationRulesToValidate = $validationRulesToValidate ?? $this->getValidationRules('update');
            $validator = Validator::make($data, $validationRulesToValidate);

            return $validator;
        }

        protected function getValidationRules($actionType = 'update')
        {
            if (!$this->validationRules || !isset($this->validationRules['updateValidation'])) {
               return [];
            }

            $validationRulesToReturn = $this->validationRules['updateValidation'];

            if ($actionType != 'update' && isset($this->validationRules['createValidation'])) {
                // merge update and create validation rules
                foreach ($validationRulesToReturn as $columnName => $validationRules) {
                    if (isset($this->validationRules['createValidation'][$columnName])) {
                        $validationRulesToReturn[$columnName] = array_merge($validationRules, $this->validationRules['createValidation'][$columnName]);
                    }
                }
            } else {
                // add 'sometimes' to validation options
                $keyName = $this->getKeyName();
                foreach ($validationRulesToReturn as $columnName => $validationRules) {
                    if ($columnName == $keyName) { continue; }
                    $validationRulesToReturn[$columnName][] = 'sometimes';
                }
            }

            return $validationRulesToReturn;
        }

        protected function setValidatedProperties(array $validatedProperties = [])
        {
            foreach ($validatedProperties as $property => $value) {
                $this->$property = $value;
            }
        }
    }
    