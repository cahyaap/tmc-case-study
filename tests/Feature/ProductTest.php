<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_successfully()
    {
        $payload = [
            'sku' => 'SKU0001',
            'name' => 'Bawang Bombay',
            'price' => 10000,
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(200)
            ->assertJson([
                'data' => $payload
            ]);
    }

    public function test_store_without_api_key()
    {
        $payload = [
            'sku' => 'SKU0001',
            'name' => 'Bawang Bombay',
            'price' => 10000,
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];
 
        $this->json('POST', 'api/products', $payload)
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Authentication required!'
            ]);
    }

    public function test_store_with_empty_payload()
    {
        $payload = [];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'sku' => [
                        'The sku field is required.'
                    ],
                    'name' => [
                        'The name field is required.'
                    ],
                    'price' => [
                        'The price field is required.'
                    ]
                ]
            ]);
    }

    public function test_store_with_empty_sku()
    {
        $payload = [
            'sku' => '',
            'name' => 'Bawang Bombay',
            'price' => 10000,
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'sku' => [
                        'The sku field is required.'
                    ]
                ]
            ]);
    }

    public function test_store_with_empty_name()
    {
        $payload = [
            'sku' => 'SKU0001',
            'name' => '',
            'price' => 10000,
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ]
                ]
            ]);
    }

    public function test_store_with_empty_price()
    {
        $payload = [
            'sku' => 'SKU0001',
            'name' => 'Bawang Bombay',
            'price' => '',
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'price' => [
                        'The price field is required.'
                    ]
                ]
            ]);
    }

    public function test_store_with_duplicate_sku()
    {
        $payload = [
            'sku' => 'SKU0001',
            'name' => 'Bawang Bombay',
            'price' => 10000,
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];

        Product::create($payload);
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'sku' => [
                        'The sku has already been taken.'
                    ]
                ]
            ]);
    }

    public function test_store_with_long_name()
    {
        $payload = [
            'name' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.",
            'sku' => 'SKU0001',
            'price' => 10000,
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name must not be greater than 255 characters.'
                    ]
                ]
            ]);
    }

    public function test_store_with_negative_price()
    {
        $payload = [
            'sku' => 'SKU0001',
            'name' => 'Bawang Bombay',
            'price' => -10000,
            'stock' => 12,
            'categoryId' => '6daa51b6-d1c0-47b9-ba0f-a8455dc95367'
        ];
 
        $this->withHeaders([
                'Authorization' => env('API_KEY', 'TMCCASESTUDYCAHYA')
            ])->json('POST', 'api/products', $payload)
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'price' => [
                        'The price must be greater than 0.'
                    ]
                ]
            ]);
    }
}
