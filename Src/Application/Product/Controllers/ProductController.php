<?php

namespace Application\Product\Controllers;

use Domain\Product\Models\Product;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Product\Actions\CreateProductAction;
use Domain\Product\Actions\DeleteProductAction;
use Domain\Product\Actions\UpdateProductAction;
use Domain\Product\DataObjects\CreateProductData;
use Domain\Product\DataObjects\UpdateProductData;
use Application\Product\ViewModels\ProductViewModel;
use Application\Product\Requests\CreateProductRequest;
use Application\Product\Requests\UpdateProductRequest;
use Application\Product\ViewModels\ProductShowViewModel;
use Application\Product\ViewModels\ListProductsViewModel;
use Application\Product\ViewModels\SimpleProductViewModel;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;

#[Prefix('products')]
class ProductController
{
    public function __construct() {}

    #[Get(
        uri: '/',
        name: 'products.list'
    )]
    public function listAll()
    {
        return (new ListProductsViewModel())->toResponse();
    }

    #[Get(
        uri: '/{product}',
        name: 'products.show'
    )]
    public function show(Product $product)
    {
        $dto = new ShowOrDeleteOneProductData(id: $product->id);
        return (new ProductShowViewModel($dto))->toResponse();
    }

    #[Post(
        uri: '/',
        name: 'products.store'
    )]
    public function store(CreateProductRequest $request, CreateProductAction $createProductAction)
    {
        $data = $request->validated();
        $dto = new CreateProductData(...$data);
        $resource = $createProductAction->execute($dto);
        return (new ProductViewModel($resource))->toResponse();
    }

    #[Post(
        uri: '/{product}',
        name: 'products.update'
    )]
    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $updateProductAction)
    {
        $data = $request->validated();
        $data['id'] = $product->id;
        return (new ProductViewModel($updateProductAction->execute(new UpdateProductData($data))))->toResponse();
    }

    #[Delete(
        uri: '/{product}/delete',
        name: 'products.destroy'
    )]
    public function destroy(Product $product, DeleteProductAction $deleteProductAction)
    {
        $dto = new ShowOrDeleteOneProductData(id: $product->id);
        $resource = $deleteProductAction->execute($dto);
        return (new SimpleProductViewModel($resource))->toResponse();
    }
}
