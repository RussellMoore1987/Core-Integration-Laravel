<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\ResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\StringResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\JsonResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\DateResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\IntResourceParameterInfoProvider;
use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\FloatResourceParameterInfoProvider;
use Illuminate\Support\Facades\App;

class ResourceParameterInfoProviderFactory
{
    protected $factoryItem;
    protected $dataType;
    protected $factoryItemArray = [
        'string' => StringResourceParameterInfoProvider::class,
        'json' => JsonResourceParameterInfoProvider::class,
        'date' => DateResourceParameterInfoProvider::class,
        'int' => IntResourceParameterInfoProvider::class,
        'float' => FloatResourceParameterInfoProvider::class,
    ];

    public function getFactoryItem(string $dataType): ResourceParameterInfoProvider
    {
        $this->dataType = strtolower($dataType);
        $this->factoryItem = null; // rests if used more then once

        $this->isStringThenSetFactoryItem();
        $this->isJsonThenSetFactoryItem();
        $this->isDateThenSetFactoryItem();
        $this->isIntThenSetFactoryItem();
        $this->isFloatThenSetFactoryItem();

        return $this->factoryItem;
    }

    protected function isStringThenSetFactoryItem(): void
    {
        if (
            $this->factoryItemIsNotSet() &&
            (
                str_contains($this->dataType, 'varchar') ||
                str_contains($this->dataType, 'char') ||
                str_contains($this->dataType, 'blob') ||
                str_contains($this->dataType, 'text') ||
                $this->dataType == 'enum' ||
                $this->dataType == 'set'
            )
        ) {
            $this->setFactoryItem($this->factoryItemArray['string']);
        }
    }

    protected function isJsonThenSetFactoryItem(): void
    {
        if ($this->factoryItemIsNotSet() && str_contains($this->dataType, 'json')) {
            $this->setFactoryItem($this->factoryItemArray['json']);
        }
    }

    protected function isDateThenSetFactoryItem(): void
    {
        if (
            $this->factoryItemIsNotSet() &&
            (
                $this->dataType == 'year' ||
                $this->dataType == 'timestamp' ||
                str_contains($this->dataType, 'date')
            )
        ) {
            $this->setFactoryItem($this->factoryItemArray['date']);
        }
    }

    protected function isIntThenSetFactoryItem(): void
    {
        // @IntTypeDeterminer
        if ($this->factoryItemIsNotSet() && str_contains($this->dataType, 'int')) {
            $this->setFactoryItem($this->factoryItemArray['int']);
        }
    }

    protected function isFloatThenSetFactoryItem(): void
    {
        if (
            $this->factoryItemIsNotSet() &&
            (
                str_contains($this->dataType, 'decimal') ||
                str_contains($this->dataType, 'numeric') ||
                str_contains($this->dataType, 'float') ||
                str_contains($this->dataType, 'double')
            )
        ) {
            $this->setFactoryItem($this->factoryItemArray['float']);
        }
    }

    protected function factoryItemIsNotSet(): bool
    {
        return !$this->factoryItem;
    }

    protected function setFactoryItem($dataTypeClassPath): void
    {
        $this->factoryItem = App::make($dataTypeClassPath);
    }
}
