<?php
    
namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory;

class InfoProviderConsts
{
    const INT_TYPE_DETERMINERS = [
        'contains-int',
        'tinyint',
        'smallint',
        'mediumint',
        'int',
        'bigint',
    ];

    const STR_TYPE_DETERMINERS = [
        'contains-char',
        'contains-varchar',
        'contains-text',
        'contains-blob',
        'enum',
        'set',
    ];

    const DATE_TYPE_DETERMINERS = [
        'contains-date',
        'timestamp',
        'year',
    ];

    const FLOAT_TYPE_DETERMINERS = [
        'contains-decimal',
        'contains-numeric',
        'contains-float',
        'contains-double',
    ];

    const JSON_TYPE_DETERMINERS = [
        'contains-json',
    ];

}

