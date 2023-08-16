<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_search_without_query()
    {
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('GET', 'api/search')
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'paging' => []
            ]);
    }

    public function test_search_without_api_key()
    {
        $this->json('GET', 'api/search')
            ->assertStatus(401)
            ->assertJson([
                'Message' => 'Authentication Required!'
            ]);
    }

    public function test_search_with_single_parameter()
    {
        $queryParams = "sku[]=SK0001&sku[]=SK0002";
        
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('GET', 'api/search?' . $queryParams)
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'paging' => []
            ]);
    }

    public function test_search_with_multiple_parameters()
    {
        $queryParams = "price.start=8000&price.end=10000";
        
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('GET', 'api/search?' . $queryParams)
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'paging' => []
            ]);
    }
}
