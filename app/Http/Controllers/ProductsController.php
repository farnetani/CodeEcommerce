<?php namespace CodeCommerce\Http\Controllers;

use CodeCommerce\Category;
use CodeCommerce\Http\Requests;
use CodeCommerce\Product;
use CodeCommerce\ProductImage;

use CodeCommerce\Tag;
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

		$request['recommended'] = $request->get('recommended')?$request->get('recommended'):0;
		$request['featured'] = $request->get('featured')?$request->get('featured'):0;

		$input = $request->all();

		$product = $this->model->fill($input);

		//Verificar se é realmente a forma mais elegante de fazer
		$array_tags_id = ProductsController::tratarTags($request);

		//Não dá pra usar se tiver que salvar uma tag nova...pq irá precisar do id do produto que eu ainda nao tenho
		//$product->tags()->sync($array_tags_id);

		$product->save();

		//depois que eu tenho o id eu chamo a função que irá verificar se tem a tag/cadastrar e vincular
		$product->tags()->sync($array_tags_id);
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

    public function tratarTags(Requests\ProductRequest $request)
    {
	    //Paga salvar as tags
	    $tags = $request->all()['tags'];
	    $array_tags = explode(',', $tags);

	    $array_tags_id = [];
	    foreach ($array_tags as $tag) {
		    $mTag = Tag::where('name','=',trim($tag))->get();

		    $aTag = $mTag->toArray();
		    foreach ($aTag as $r) {
			    $array_tags_id[]=(integer)$r['id'];

		    }
		    //Video: Relacionamento: MANYTOMANY - Relacionando: http://portal.code.education/lms/#/42/56/60/conteudos?capitulo=375&conteudo=2362
		    //Verifica se a Tag não existe, se não existir cadastra ela!
		    if ($mTag->isEmpty())
		    {
			    $regTag = new Tag;
			    $regTag->name=trim($tag);
			    $regTag->save();

			    $mTag = Tag::where('name','=',trim($tag))->get();

			    $aTag = $mTag->toArray();
			    foreach ($aTag as $r) {
				    $array_tags_id[]=(integer)$r['id'];
			    }

		    }
	    }
	    //Finalizado: salvar tags
	    return $array_tags_id;

    }

	public function update(Requests\ProductRequest $request, $id)
	{
		$request['recommended'] = $request->get('recommended')?$request->get('recommended'):0;
		$request['featured'] = $request->get('featured')?$request->get('featured'):0;

		//Verificar se é realmente a forma mais elegante de fazer
		$array_tags_id = ProductsController::tratarTags($request);

		//$this->model->find($id)->update($request->all());

		//Desmembrei para jogar as tags na jogada
		$modProduct = $this->model->find($id);
		//$modProduct->tags()->sync([5,6]);
		$modProduct->tags()->sync($array_tags_id);
		$modProduct->update($request->all());

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
