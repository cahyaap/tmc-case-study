<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_store_successfully()
    {
        $payload = [
            'name' => 'Sayur'
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/categories', $payload)
            ->assertStatus(200)
            ->assertJson([
                'data' => $payload
            ]);
    }

    public function test_store_without_api_key()
    {
        $payload = [
            'name' => 'Sayur'
        ];
 
        $this->json('POST', 'api/categories', $payload)
            ->assertStatus(401)
            ->assertJson([
                'Message' => 'Authentication Required!'
            ]);
    }

    public function test_store_with_empty_name()
    {
        $payload = [
            'name' => ""
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/categories', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ]
                ]
            ]);
    }

    public function test_store_with_long_name()
    {
        $payload = [
            'name' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged."
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/categories', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name must not be greater than 255 characters.'
                    ]
                ]
            ]);
    }
}
