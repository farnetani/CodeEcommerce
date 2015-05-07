<?php namespace CodeCommerce\Http\Controllers;

use CodeCommerce\Category;
use CodeCommerce\Http\Requests;
use CodeCommerce\Product;
use Illuminate\Support\Facades\Route;

class ProductsController extends Controller
{

	private $productModel;
	public function __construct(Product $productModel)
	{
		$this->productModel = $productModel;
	}
	public function index()
	{
		//return "Listagem de Produtos";
		//$products = $this->productModel->all();
		//Para paginar
		$products = $this->productModel->paginate(5);
		return view('products.index',compact('products'));
	}

	public function create(Category $category)
	{

		$categories = $category->lists('name','id');

		return view('products.create', compact('categories'));
	}

	public function store(Requests\ProductRequest $request)
	{
		$input = $request->all();

		$product = $this->productModel->fill($input);

		$product->save();

		//return redirect('products');
		return redirect(Route('products'));
	}

	public function destroy($id)
	{
		$this->productModel->find($id)->delete();
		//return redirect('/admin/products');
		return redirect(Route('products'));
	}

	public function edit($id, Category $category)
	{
		$categories = $category->lists('name', 'id');

		$product = $this->productModel->find($id);
		return view('products.edit', compact('product', 'categories'));
	}

	public function update(Requests\ProductRequest $request, $id)
	{
		$this->productModel->find($id)->update($request->all());
		//return redirect('/admin/products');
		return redirect(Route('products'));
	}

}