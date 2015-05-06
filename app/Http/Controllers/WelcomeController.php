<?php namespace CodeCommerce\Http\Controllers;

use CodeCommerce\Category;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	//public function __construct()
	//prática de INDEPENDENCY INJECTION recomendada
	private $categories;

	public function __construct(Category $category)
	{
		$this->middleware('guest');
		$this->categories = $category;
	}

	public function index()
	{
		return view('welcome');
	}

	public function exemplo()
	{
		//Chamadas Estáticas : estamos fazendo um alto acoplamento (PRÁTICA RUIM)
		//Quando estamos programando OO estamos trabalhando para um Interface e não para uma Implementação
		//Criaram uma coisa chamada Método Injection : Ter um construtor e jogar no construtor a category.
		//Exemplo: private $categories;
		//Exemplo: public function __construct(Category $category)
		//Exemplo: {
		//Exemplo:    $this->categories = $category;
		//Exemplo: }
		//Aí em baixo, o correto seria: $categories = $this->categories->all();
		//O próprio Laravel vai se tocar que tem que injetar uma category no construtor.
		//O ideal seria ter ainda uma INTERFACE...mas isso já estaria melhor ok.
		//Se não usar essa metodologia irá dificultar os testes e será difícil aproveitar o código
		//Isso é trabalhar com independency injection
		//$categories = Category::all();  //Não recomendável por utilizar chamadas estáticas (PRÁTICA RUIM)

		//Mais correto seria utilizar INTERFACE, mas uma outra maneira seria:
		$categories = $this->categories->all();

		return view('exemplo', compact('categories'));
	}

}
