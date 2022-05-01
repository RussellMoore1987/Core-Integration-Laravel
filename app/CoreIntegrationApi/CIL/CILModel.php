<?php
    
    namespace App\CoreIntegrationApi\CIL;

    use Illuminate\Support\Facades\Validator;

    trait CILModel
    {
        // https://laravel.com/docs/8.x/validation#available-validation-rules

        public function validateAndSave(array $data = [], $redirect = false)
        {
            // TODO: add validation messages
            $validator = $this->validate($data, $this->getValidationRules());
            if ($validator->fails()) {
                if ($redirect) {
                    // TODO: Not tested, need to test
                    return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
                }

                return $validator->errors()->toArray();
            }

            $this->setValidatedProperties($validator->validated());

            $this->save();

            return [];
        }

        public function validate($data, $validationRulesToValidate = null)
        {
            $validationRulesToValidate = $validationRulesToValidate ?? $this->getValidationRules();
            $validator = Validator::make($data, $validationRulesToValidate);

            return $validator;
        }

        protected function getValidationRules()
        {
            if (
                !$this->validationRules || 
                (!isset($this->validationRules['modelValidation']) || !is_array($this->validationRules['modelValidation'])) || 
                (!isset($this->validationRules['createValidation']) || !is_array($this->validationRules['createValidation']))
            ) {
                throw new \Exception('validationRules rules not set. A class utilizing the CILModel trait must have validationRules, see the documentation located at app\CoreIntegrationApi\docs\CILModel.php');
            }
            
            $validationRulesToReturn = $this->validationRules['modelValidation'];

            $keyName = $this->getKeyName();
            $actionType = $this->$keyName != null ? 'update' : 'create';

            if ($actionType != 'update') {
                // merge update and create validation rules
                foreach ($validationRulesToReturn as $columnName => $validationRules) {
                    if (isset($this->validationRules['createValidation'][$columnName])) {
                        $validationRulesToReturn[$columnName] = array_merge($validationRules, $this->validationRules['createValidation'][$columnName]);
                    }
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
    