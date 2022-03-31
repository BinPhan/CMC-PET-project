<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductImageAPIRequest;
use App\Http\Requests\API\UpdateProductImageAPIRequest;
use App\Models\ProductImage;
use App\Repositories\ProductImageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class ProductImageController
 * @package App\Http\Controllers\API
 */

class ProductImageAPIController extends AppBaseController
{
    /** @var  ProductImageRepository */
    private $productImageRepository;

    public function __construct(ProductImageRepository $productImageRepo)
    {
        $this->productImageRepository = $productImageRepo;
    }

    /**
     * Display a listing of the ProductImage.
     * GET|HEAD /productImages
     *
     * @param Request $request
     * @return Response
     */

    /**
     * @OA\Get(
     *     path="/api/product_images",
     *     summary="List all productImages",
     *     tags={"productImages"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="product_id",
     *         description="",
     *          in = "query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *     @OA\Parameter(
     *         name="type",
     *         description="",
     *          in = "query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *     @OA\Parameter(
     *         name="status",
     *         description="",
     *          in = "query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="retrieved",
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $productImages = $this->productImageRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($productImages->toArray(), 'Product Images retrieved successfully');
    }

    /**
     * Store a newly created ProductImage in storage.
     * POST /productImages
     *
     * @param CreateProductImageAPIRequest $request
     *
     * @return Response
     */

    /**
     * @OA\Post(
     *     path="/api/product_images",
     *     summary="Create a productImage",
     *     tags={"productImages"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *           mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="files"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Product Image saved successfully"),
     *     @OA\Response(response="500", description="Internal error")
     * )
     */
    public function store(CreateProductImageAPIRequest $request)
    {
        $input = $request->all();
        $path = $request->file('image')->store('public/images/product_images');

        $input['path'] = $path;
        $productImage = $this->productImageRepository->create($input);

        return $this->sendResponse($productImage->toArray(), 'Product Image saved successfully');
    }

    /**
     * Display the specified ProductImage.
     * GET|HEAD /productImages/{id}
     *
     * @param int $id
     *
     * @return Response
     */

    /**
     * @OA\get(
     *     path="/api/product_images/{id}",
     *     summary="Get a productImages",
     *     tags={"productImages"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An Id value."),
     *     ),
     *     @OA\Response(response="200", description="Product Image retrieved successfully"),
     *     @OA\Response(response="500", description="Internal error")
     * )
     */
    public function show($id)
    {
        /** @var ProductImage $productImage */
        $productImage = $this->productImageRepository->find($id);

        if (empty($productImage)) {
            return $this->sendError('Product Image not found');
        }

        return $this->sendResponse($productImage->toArray(), 'Product Image retrieved successfully');
    }

    /**
     * Update the specified ProductImage in storage.
     * PUT/PATCH /productImages/{id}
     *
     * @param int $id
     * @param UpdateProductImageAPIRequest $request
     *
     * @return Response
     */

    /**
     * @OA\Patch(
     *     path="/api/product_images/{id}",
     *     summary="Update a productImage",
     *     tags={"productImages"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *           mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="files"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Product Image updated successfully"),
     *     @OA\Response(response="500", description="Internal error")
     * )
     */
    public function update($id, UpdateProductImageAPIRequest $request)
    {
        $input = $request->all();

        /** @var ProductImage $productImage */
        $productImage = $this->productImageRepository->find($id);

        if (empty($productImage)) {
            return $this->sendError('Product Image not found');
        }

        $productImage = $this->productImageRepository->update($input, $id);

        return $this->sendResponse($productImage->toArray(), 'ProductImage updated successfully');
    }

    /**
     * Remove the specified ProductImage from storage.
     * DELETE /productImages/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */

    /**
     * @OA\Delete(
     *     path="/api/product_images/{id}",
     *     summary="Delete a Category",
     *     tags={"productImages"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         description="Parameter with mutliple examples",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An Id value."),
     *     ),
     *     @OA\Response(response="200", description="Product Image deleted successfully")
     * )
     */
    public function destroy($id)
    {
        /** @var ProductImage $productImage */
        $productImage = $this->productImageRepository->find($id);

        if (empty($productImage)) {
            return $this->sendError('Product Image not found');
        }

        $productImage->delete();

        return $this->sendSuccess('Product Image deleted successfully');
    }
}
