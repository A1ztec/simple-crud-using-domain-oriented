<?php


namespace Application\Product\Controllers;


use GuzzleHttp\Promise\Create;
use Domain\Product\Models\Product;
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
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;

class ProductController
{
    public function __construct(
        private ListAllProductsAction $listAllProductsAction,
        private ShowOneProductAction $showOneProductAction,
        private CreateProductAction $createProductAction,
        private UpdateProductAction $updateProductAction,
        private DeleteProductAction $deleteProductAction
    ) {}
    public function index()
    {
        return (new ProductViewModel($this->listAllProductsAction->execute()))->toResponse();
    }

    public function show(Product $product)
    {
        $dto = new ShowOrDeleteOneProductData(id: $product->id);
        return (new ProductViewModel($this->showOneProductAction->execute($dto)))->toResponse();
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $dto = new CreateProductData(...$data);
        return (new ProductViewModel($this->createProductAction->execute($dto)))->toResponse();
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['id'] = $product->id;
        $dto = new UpdateProductData(...$data);
        return (new ProductViewModel($this->updateProductAction->execute($dto)))->toResponse();
    }
}
