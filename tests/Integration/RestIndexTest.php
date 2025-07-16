<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RestIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @dataProvider indexParameterProvider
     * @group get
     * @group rest
     */
    public function test_rest_index_returns_the_correct_partials(array $options): void
    {
        $indexOptions = '';
        foreach ($options as $option) {
            if ($indexOptions) {
                $indexOptions .= '&';
            }
            $indexOptions .= "{$option}=yes";
        }

        $response = $this->get("/api/v1/?{$indexOptions}");

        $responseArray = json_decode($response->content(), true);

        $response->assertStatus(200);
        $optionCount = $options ? count($options) : 4; // Default options count
        $this->assertEquals($optionCount, count($responseArray));

        foreach ($options as $option) {
            $this->assertArrayHasKey($option, $responseArray);
        }
    }

    public function indexParameterProvider(): array
    {
        return [
            'all' => [[]],
            'about' => [['about']],
            'generalDocumentation' => [['generalDocumentation']],
            'quickRouteReference' => [['quickRouteReference']],
            'routes' => [['routes']],
            'aboutAndGeneralDocumentation' => [['about', 'generalDocumentation']],
            'aboutAndQuickRouteReferenceAndRoutes' => [['about', 'quickRouteReference', 'routes']],
            'allOptions' => [['about', 'generalDocumentation', 'quickRouteReference', 'routes']],
        ];
    }

    /**
     * @group get
     * @group rest
     * just testing general index information, other tests test content more thoroughly
     */
    public function test_rest_index_returns_the_correct_information(): void
    {
        $response = $this->get("/api/v1/?");
        $responseArray = json_decode($response->content(), true);

        $response->assertStatus(200);
        $this->assertEquals(4, count($responseArray));

        $this->assertAbout($responseArray['about']);
        $this->assertGeneralDocumentation($responseArray['generalDocumentation']);

        $endpoints = [
            'caseStudies',
            'projects',
            'content',
            'experience',
            'images',
            'posts',
            'resources',
            'categories',
            'tags',
            'skillTypes',
            'skills',
            'workHistoryTypes',
            'workHistory',
        ];
        $this->assertQuickRouteReference($responseArray['quickRouteReference'], $endpoints);
        $this->assertRoutes($responseArray['routes'], $endpoints);
    }

    private function assertAbout(array $about): void
    {
        $this->assertEquals([
            'companyName' => 'Placeholder Company',
            'termsOfUse' => 'Placeholder Terms URL',
            'version' => '1.0.0',
            'contact' => 'someone@someone.com',
            'description' => 'v1.0.0 of the api. This API may be used to retrieve data. restrictions and limitations are detailed below in the _______ section.',
            'siteRoot' => 'http://localhost:8000',
            'apiRoot' => 'http://localhost:8000/api/v1',
            'defaultReturnRequestStructure' => 'dataOnly'
        ], $about);
    }

    private function assertGeneralDocumentation(array $documentation): void
    {
        $this->assertCount(4, $documentation);
        $this->assertArrayHasKey('mainAuthentication', $documentation);
        $this->assertArrayHasKey('httpMethods', $documentation);
        $this->assertArrayHasKey('defaultParametersForRoutes', $documentation);
        $this->assertArrayHasKey('parameterDataTypes', $documentation);
    }

    private function assertQuickRouteReference(array $quick_routes, array $endpoints): void
    {
        $this->assertCount(13, $quick_routes);
        foreach ($endpoints as $endpoint) {
            $this->assertArrayHasKey($endpoint, $quick_routes);
        }
    }

    private function assertRoutes(array $routes, array $endpoints): void
    {
        $this->assertCount(13, $routes);
        foreach ($endpoints as $endpoint) {
            $this->assertArrayHasKey($endpoint, $routes);
        }
    }
}
