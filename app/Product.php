<?php namespace CodeCommerce;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

	protected $fillable = [
		'category_id',
		'name',
		'description',
		'price',
		'featured',
		'recommended'
	];

	public function images()
	{
		return $this->hasMany('CodeCommerce\ProductImage');
	}

	//Relacionamento com a Categoria
	public function category()
	{
		return $this->belongsTo('CodeCommerce\Category');
	}


	public function tags()
	{
		return $this->belongsToMany('CodeCommerce\Tag');
	}

	//Query Scope
	public function scopeFeatured($query)
	{
		return $query->where('featured','=',1);
	}

	public function scopeRecommended($query)
	{
		return $query->where('recommended','=',1);
	}

}
