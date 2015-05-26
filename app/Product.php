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

	//Query Scope | Escopos de consulta
	public function scopeFeatured($query)
	{
		return $query->where('featured','=',1);
	}

	public function scopeRecommended($query)
	{
		return $query->where('recommended','=',1);
	}

	//Dica: para trabalhar com apenas os produtos de uma categoria$
	public function scopeOfCategory($query, $type)
	{
		return $query->where('category_id', '=', $type);
	}

	public function getTagListAttribute()
	{
//		$tags = [];
//		foreach($this->tags as $tag) {
//			$tags[] = $tag->name;
//		}
//		return implode(",", $tags);

		$tags = $this->tags->lists('name');
		return implode(',', $tags);
	}
}
