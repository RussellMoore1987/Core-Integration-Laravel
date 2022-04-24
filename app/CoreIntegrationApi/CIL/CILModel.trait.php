<?php
    
    namespace App\CoreIntegrationApi\CIL;

    use Illuminate\Support\Facades\Validator;

    trait CILModel
    {
        protected $validator;
        protected $validationRulesToValidate;
        protected $redirect;
        protected $data;

        public function validateAndSave(array $data = [], $redirect = false)
        {
            // TODO: $this->id set correctly ClassDataProvider::getClassPrimaryKeyName()
            $keyName = $this->classObject->getKeyName();
            $this->validationRulesToValidate = $this->$keyName ? $this->getValidationRules('update') : $this->getValidationRules('create');
            $this->data = $data;

            // TODO: add validation messages
            // just validate and give back the errors
            // create parameters required for validation
            // update parameters optional for validation
            $validator = Validator::make($data, $this->validationRulesToValidate);
     
            if ($validator->fails()) {
                if ($redirect) {
                    return redirect('post/create')
                        ->withErrors($validator)
                        ->withInput();
                }

                return $validator->errors();
            }

            dd($data, $validator->validated(), $validator->safe(), $validator->safe()->only(['name', 'email']));

            $this->fill($validator->validated());

            $this->save();
        }

        protected function validate()
        {
            
        }

        protected function getValidationRules($actionType = 'update')
        {
            if (!$this->validationRules) {
               return [];
            }

            $validationRulesToReturn = $this->validationRules['updateValidation'];

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
    }
    