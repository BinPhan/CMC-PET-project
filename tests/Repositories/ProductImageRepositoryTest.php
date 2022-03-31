<?php namespace Tests\Repositories;

use App\Models\ProductImage;
use App\Repositories\ProductImageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ProductImageRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ProductImageRepository
     */
    protected $productImageRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productImageRepo = \App::make(ProductImageRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_product_image()
    {
        $productImage = ProductImage::factory()->make()->toArray();

        $createdProductImage = $this->productImageRepo->create($productImage);

        $createdProductImage = $createdProductImage->toArray();
        $this->assertArrayHasKey('id', $createdProductImage);
        $this->assertNotNull($createdProductImage['id'], 'Created ProductImage must have id specified');
        $this->assertNotNull(ProductImage::find($createdProductImage['id']), 'ProductImage with given id must be in DB');
        $this->assertModelData($productImage, $createdProductImage);
    }

    /**
     * @test read
     */
    public function test_read_product_image()
    {
        $productImage = ProductImage::factory()->create();

        $dbProductImage = $this->productImageRepo->find($productImage->id);

        $dbProductImage = $dbProductImage->toArray();
        $this->assertModelData($productImage->toArray(), $dbProductImage);
    }

    /**
     * @test update
     */
    public function test_update_product_image()
    {
        $productImage = ProductImage::factory()->create();
        $fakeProductImage = ProductImage::factory()->make()->toArray();

        $updatedProductImage = $this->productImageRepo->update($fakeProductImage, $productImage->id);

        $this->assertModelData($fakeProductImage, $updatedProductImage->toArray());
        $dbProductImage = $this->productImageRepo->find($productImage->id);
        $this->assertModelData($fakeProductImage, $dbProductImage->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_product_image()
    {
        $productImage = ProductImage::factory()->create();

        $resp = $this->productImageRepo->delete($productImage->id);

        $this->assertTrue($resp);
        $this->assertNull(ProductImage::find($productImage->id), 'ProductImage should not exist in DB');
    }
}
