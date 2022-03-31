<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Http\Requests\API\UpdateProductAPIRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use Response;

/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */

/**
 * @OA\Schema(
 *  schema="Product",
 *  title="Product schema",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string",
 *      example="Shirt"
 * 	),
 * 	@OA\Property(
 * 		property="description",
 * 		type="string",
 *      example="This is a shirt"
 * 	),
 * 	@OA\Property(
 * 		property="features",
 * 		type="integer",
 *      example="1000"
 * 	),
 * 	@OA\Property(
 * 		property="attribute",
 * 		type="string",
 *      example="{'name': 'price', 'value': 123},{'name': 'sale_price', 'value': 100},{'name': 'size', 'value': 'md'},{'name': 'size', 'value': 'sm'}"
 * 	),
 * 
 *
 * )
 */


class ProductAPIController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return Response
     * 
     *     @OA\Get(
     *     summary="Get list product",
     *     operationId="List product",
     *     security={{"sanctum":{}}},
     *     tags={"Product"},
     *     path="/api/products",
     *     description="Get list of products",
     *     @OA\Parameter(
     *          name="seach params",
     *          description="Search params",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              ref="#/components/schemas/Product"
     *          )
     *      ),
     *     @OA\Response(
     *      response="200", 
     *      description="Product list", 
     *      @OA\JsonContent(
     *              ref="#/components/schemas/Product"
     *      )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $products = $this->productRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        $products->load('attributeValue.attribute');

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    /**
     * Store a newly created Product in storage.
     * POST /products
     *
     * @param CreateProductAPIRequest $request
     *
     * @return Response
     * 
     *  @OA\Post(
     *  tags={"Product"},
     *  operationId="Create Product",
     *  path="/api/products",
     *  security={{"sanctum":{}}},
     * 
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *              ref="#/components/schemas/Product"
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success"
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Internal error"
     *  )
     * )
     */
    public function store(CreateProductAPIRequest $request)
    {
        $input = $request->all();

        $product = $this->productRepository->create($input);

        if (isset($input['attribute'])) { // If this is a product with attribute
            $attributeIds = [];
            foreach ($input['attribute'] as $key => $value) {
                $attributeIds[] = [
                    'attribute_id' => Attribute::updateOrCreate(['name' => $value['name']], ['name' => $value['name']])->id,
                    'value' => $value['value']
                ];
            }

            // \dd($attributeIds);
            $this->productRepository->syncAttribute($product, $attributeIds);
        }
        return $this->sendResponse($product->toArray(), 'Product saved successfully');
    }

    /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     *
     * @param int $id
     *
     * @return Response
     * 
     * @OA\Get(
     *  
     *  tags={"Product"},
     *  operationId="Show Product",
     *  path="/api/products/{id}",
     *  security={{"sanctum":{}}},
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      @OA\Schema(
     *              type="integer"
     *      )
     *  ),
     *  @OA\RequestBody(
     *      
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success"
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Internal error"
     *  )
     * )
     */
    public function show($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        $product->load('attributeValue.attribute');

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully');
    }

    /**
     * Update the specified Product in storage.
     * PUT/PATCH /products/{id}
     *
     * @param int $id
     * @param UpdateProductAPIRequest $request
     *
     * @return Response
     * 
     * 
     *  @OA\Put(
     *  path="/api/products/{id}",
     *  tags={"Product"},
     *  operationId="Edit product",
     *  security={{"sanctum":{}}},
     *  @OA\Parameter(
     *      name="id",
     *      in="path"
     *  ),
     *  
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *              ref="#/components/schemas/Product"
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success"
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Internal error"
     *  )
     * )
     */
    public function update($id, UpdateProductAPIRequest $request)
    {
        $input = $request->all();

        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        if (isset($input['attribute'])) { // If this is a product with attribute
            $attributeIds = [];
            foreach ($input['attribute'] as $key => $value) {
                $attributeIds[] = [
                    'attribute_id' => Attribute::updateOrCreate(['name' => $value['name']], ['name' => $value['name']])->id,
                    'value' => $value['value']
                ];
            }

            // \dd($attributeIds);
            $this->productRepository->syncAttribute($product, $attributeIds);
        }


        $product = $this->productRepository->update($input, $id);

        // $product->load('attributeValue.attribute');

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully');
    }

    /**
     * Remove the specified Product from storage.
     * DELETE /products/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     * 
     *      @OA\Delete(
     *      path="/api/products/{id}",
     *      operationId="delete product",
     *      tags={"Product"},
     *      summary="Delete existing product",
     *      security={{"sanctum":{}}},
     * 
     *      description="Deletes an product and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $this->productRepository->syncAttribute($product, []);

        $product->delete();

        return $this->sendSuccess('Product deleted successfully');
    }
}
