<?php

namespace App\CoreIntegrationApi\FunctionalityProviders;

class Helper
{
    public static function isSingleRestIdRequest($resourceId): bool
    {
        return $resourceId && !str_contains($resourceId, ',') && !str_contains($resourceId, '::');
    }
}
