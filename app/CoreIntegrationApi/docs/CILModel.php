<?php 
    // @validationRules
        // ! warning empty validationRules will throw an error 
        // ! warning empty createValidation & updateValidation will not allow records to be created properly 
            // will create records with DB default values or throw an error if DB columns don't have default values
        // ! warning empty createValidation & updateValidation may cause unexpected behavior 

        // All columns you want to be able to update in the database must be listed in the updateValidation array
        // Aplyy extra validation rules that you would like on creation in the createValidation array

        // validationRules need to be set on a class that utilizing the CILModel trait. It must have a validationRules property set with the following structure:
            // protected $validationRules = [
            //     'updateValidation' => [
            //         'column_name' => [
            //             'laravel_validation_rules',
            //         ],
            //     ],
            //     'createValidation' => [
            //         'column_name' => [
            //             'laravel_validation_rules_you_want_to_apply_to_the_create_action',
            //         ],
            //     ],
            // ];
            // 
            // project example:
            // protected $validationRules = [
            //     'updateValidation' => [
            //         'id' => [
            //             'integer',
            //             'min:1',
            //             'max:18446744073709551615',
            //         ],
            //         'title' => [
            //             'string',
            //             'max:75',
            //             'min:2',
            //         ],
            //         'roles' => [
            //             'string',
            //             'max:50',
            //         ],
            //         'client' => [
            //             'string',
            //             'max:50',
            //         ],
            //         'description' => [
            //             'string',
            //             'max:255',
            //             'min:10',
            //         ],
            //         'content' => [
            //             'string',
            //             'json',
            //         ],
            //         'video_link' => [
            //             'string',
            //             'max:255',
            //         ],
            //         'code_link' => [
            //             'string',
            //             'max:255',
            //         ],
            //         'demo_link' => [
            //             'string',
            //             'max:255',
            //         ],
            //         'start_date' => [
            //             'date',
            //         ],
            //         'end_date' => [
            //             'date',
            //         ],
            //         'is_published' => [
            //             'integer',
            //             'min:0',
            //             'max:1',
            //         ],
            //         'budget' => [
            //             'numeric',
            //             'max:999999.99',
            //             'min:0',
            //         ],
            //     ],
            //     'createValidation' => [
            //         'title' => [
            //             'required',
            //         ],
            //         'roles' => [
            //             'required',
            //         ],
            //         'description' => [
            //             'required',
            //         ],
            //         'start_date' => [
            //             'required',
            //         ],
            //         'budget' => [
            //             'required',
            //         ],
            //     ],
            // ];