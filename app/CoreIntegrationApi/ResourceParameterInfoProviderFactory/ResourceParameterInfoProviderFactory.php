<?php

namespace App\CoreIntegrationApi\ResourceParameterInfoProviderFactory;

use App\CoreIntegrationApi\ResourceParameterInfoProviderFactory\ResourceParameterInfoProviders\{
    ResourceParameterInfoProvider,
    StringResourceParameterInfoProvider,
    JsonResourceParameterInfoProvider,
    DateResourceParameterInfoProvider,
    IntResourceParameterInfoProvider,
    FloatResourceParameterInfoProvider
};
use Illuminate\Support\Facades\App;

class ResourceParameterInfoProviderFactory
{
    protected ?ResourceParameterInfoProvider $factoryItem;
    protected string $dataType;
    protected array $factoryItemArray = [
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
        if ($this->factoryItemIsNotSet() && $this->checkForType(InfoProviderConsts::STR_TYPE_DETERMINERS)) {
            $this->setFactoryItem($this->factoryItemArray['string']);
        }
    }

    protected function isJsonThenSetFactoryItem(): void
    {
        if ($this->factoryItemIsNotSet() && $this->checkForType(InfoProviderConsts::JSON_TYPE_DETERMINERS)) {
            $this->setFactoryItem($this->factoryItemArray['json']);
        }
    }

    protected function isDateThenSetFactoryItem(): void
    {
        if ($this->factoryItemIsNotSet() && $this->checkForType(InfoProviderConsts::DATE_TYPE_DETERMINERS)) {
            $this->setFactoryItem($this->factoryItemArray['date']);
        }
    }

    protected function isIntThenSetFactoryItem(): void
    {
        if ($this->factoryItemIsNotSet() && $this->checkForType(InfoProviderConsts::INT_TYPE_DETERMINERS)) {
            $this->setFactoryItem($this->factoryItemArray['int']);
        }
    }

    protected function isFloatThenSetFactoryItem(): void
    {
        if ($this->factoryItemIsNotSet() && $this->checkForType(InfoProviderConsts::FLOAT_TYPE_DETERMINERS)) {
            $this->setFactoryItem($this->factoryItemArray['float']);
        }
    }

    protected function factoryItemIsNotSet(): bool
    {
        return !$this->factoryItem;
    }

    protected function checkForType(array $checks): bool
    {
        $foundType = false;
        foreach ($checks as $type) {
            if (str_contains($type, 'contains-')) {
                $type = explode('-', $type)[1];
                if (str_contains($this->dataType, $type)) {
                    $foundType = true;
                    break;
                }
            } else {
                if ($this->dataType == $type) {
                    $foundType = true;
                    break;
                }
            }
        }

        return $foundType;
    }

    protected function setFactoryItem($dataTypeClassPath): void
    {
        $this->factoryItem = App::make($dataTypeClassPath);
    }
}
