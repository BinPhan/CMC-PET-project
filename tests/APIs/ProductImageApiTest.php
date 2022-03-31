<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ProductImage;

class ProductImageApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_product_image()
    {
        $productImage = ProductImage::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/product_images', $productImage
        );

        $this->assertApiResponse($productImage);
    }

    /**
     * @test
     */
    public function test_read_product_image()
    {
        $productImage = ProductImage::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/product_images/'.$productImage->id
        );

        $this->assertApiResponse($productImage->toArray());
    }

    /**
     * @test
     */
    public function test_update_product_image()
    {
        $productImage = ProductImage::factory()->create();
        $editedProductImage = ProductImage::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/product_images/'.$productImage->id,
            $editedProductImage
        );

        $this->assertApiResponse($editedProductImage);
    }

    /**
     * @test
     */
    public function test_delete_product_image()
    {
        $productImage = ProductImage::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/product_images/'.$productImage->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/product_images/'.$productImage->id
        );

        $this->response->assertStatus(404);
    }
}
