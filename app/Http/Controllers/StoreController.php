<?php namespace CodeCommerce\Http\Controllers;

use CodeCommerce\Http\Requests;
use CodeCommerce\Http\Controllers\Controller;

use Illuminate\Http\Request;

use CodeCommerce\Category;
use CodeCommerce\Product;


class StoreController extends Controller {

	public function index()
	{

		//$pFeatured = Product::where('featured','=', 1)->get();  //método antigo sem query scope
		$pFeatured = Product::featured()->get();

		//$pRecommended = Product::where('recommended','=', 1)->get();
		$pRecommended = Product::recommended()->get();
		//dd($pFeatured);
		$categories = Category::all();

		return view('store.index', compact('categories','pFeatured','pRecommended'));
	}


}
