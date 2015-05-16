<?php namespace CodeCommerce\Http\Controllers;

use CodeCommerce\Category;
use CodeCommerce\Http\Requests;
use CodeCommerce\Product;
use CodeCommerce\ProductImage;

use Illuminate\Http\Request;
//não recomenda usar as façades abaixo
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{

	private $model;
	public function __construct(Product $model)
	{
		$this->model = $model;
	}
	public function index()
	{
		//return "Listagem de Produtos";
		//$products = $this->model->all();
		//Para paginar
		$products = $this->model->paginate(5);
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

		$product = $this->model->fill($input);

		$product->save();

		//return redirect('products');
		return redirect(Route('products'));
	}

	public function destroy($id)
	{
		$this->model->find($id)->delete();
		//return redirect('/admin/products');
		return redirect(Route('products'));
	}

	public function edit($id, Category $category)
	{
		$categories = $category->lists('name', 'id');

		$product = $this->model->find($id);
		return view('products.edit', compact('product', 'categories'));
	}

	public function update(Requests\ProductRequest $request, $id)
	{
		$this->model->find($id)->update($request->all());
		//return redirect('/admin/products');
		return redirect(Route('products'));
	}

	public function images($id)
	{
		$product = $this->model->find($id);

		return view('products.images', compact('product'));
	}

	public function createImage($id)
	{
		$product = $this->model->find($id);
		return view('products.create_image', compact('product'));
	}

	//public function storeImage(Request $request, $id, ProductImage $productImage)
	public function storeImage(Requests\productImageRequest $request, $id, ProductImage $productImage)
	{
		$file = $request->file('image');

		$extension = $file->getClientOriginalExtension();

		$image = $productImage::create(['product_id'=>$id, 'extension'=>$extension]);

		Storage::disk('public_local')->put($image->id.".".$extension,File::get($file));

		return redirect()->route('products.images',['id'=>$id]);

	}
	public function destroyImage(ProductImage $productImage, $id)
	{
		$image = $productImage->find($id);

		//Se o arquivo existir ele apaga
		if(file_exists(public_path() . '/uploads/'.$image->id.'.'.$image->extension)) {

			Storage::disk('public_local')->delete($image->id . '.' . $image->extension);
		}
		$product = $image->product;
		$image->delete();

		return redirect()->route('products.images', ['id'=>$product->id]);
	}
}
