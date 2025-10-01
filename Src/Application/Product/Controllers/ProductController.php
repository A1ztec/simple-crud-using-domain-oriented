<?php


namespace Application\Product\Controllers;


use GuzzleHttp\Promise\Create;
use Domain\Product\Models\Product;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Domain\Product\Actions\CreateProductAction;
use Domain\Product\Actions\DeleteProductAction;
use Domain\Product\Actions\UpdateProductAction;
use Domain\Product\Actions\ShowOneProductAction;
use Domain\Product\Actions\ListAllProductsAction;
use Domain\Product\DataObjects\CreateProductData;
use Domain\Product\DataObjects\UpdateProductData;
use Application\Product\ViewModels\ProductViewModel;
use Application\Product\Requests\CreateProductRequest;
use Application\Product\Requests\UpdateProductRequest;
use Application\Product\ViewModels\ListProductsViewModel;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;

#[Prefix('products')]
class ProductController
{
    public function __construct(
        private ListAllProductsAction $listAllProductsAction,
        private ShowOneProductAction $showOneProductAction,
        private CreateProductAction $createProductAction,
        private UpdateProductAction $updateProductAction,
        private DeleteProductAction $deleteProductAction
    ) {}


    #[Get(
        uri: '/',
        name: 'products.list'
    )]
    public function listAll()
    {
        return (new ListProductsViewModel($this->listAllProductsAction->execute()))->toResponse();
    }

    #[Get(
        uri: '/{product}',
        name: 'products.show'
    )]

    public function show(Product $product)
    {
        $dto = new ShowOrDeleteOneProductData(id: $product->id);
        return (new ProductViewModel($this->showOneProductAction->execute($dto)))->toResponse();
    }

    #[Post(
        uri: '/',
        name: 'products.store'
    )]
    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $dto = new CreateProductData(...$data);
        return (new ProductViewModel($this->createProductAction->execute($dto)))->toResponse();
    }

    #[Post(
        uri: '/{product}',
        name: 'products.update'
    )]
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['id'] = $product->id;
        $dto = new UpdateProductData(...$data);
        return (new ProductViewModel($this->updateProductAction->execute($dto)))->toResponse();
    }

    #[Post(
        uri: '/{product}/delete',
        name: 'products.destroy'
    )]
    public function destroy(Product $product)
    {
        $dto = new ShowOrDeleteOneProductData(id: $product->id);
        return (new ProductViewModel($this->deleteProductAction->execute($dto)))->toResponse();
    }
}
