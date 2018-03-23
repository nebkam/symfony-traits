# Symfony Traits
Few helper traits for quicker API  development in Symfony

## FormTrait
### `handleJSONForm` 
- controller helper method for JSON data sent in `POST`, `PUT` or `PATCH` request content
- generally speaking, sending JSON content is more flexible than forms, for CRUD with JS frameworks.
```php
public function create(Request $request)
	{
	$entity = new Entity();
	$this->handleJSONForm($request, $entity, EntityType::class);
	// persist and flush $entity
```
```php
public function edit(Request $request,Entity $entity)
	{
	$this->handleJSONForm($request, $entity, EntityType::class);
	// flush entity
```

### `handleForm` 
- controller helper method for traditional form data in `GET` or `POST`
- I advice using traditional form data only when JSON is out of place (i.e. `GET` params)
```php
public function example(Request $request)
	{
	$domain = new Domain();
	if ($request->query->count() > 0)
		{
		$this->handleForm($request, $params, DomainType::class);
		}
	
	// do something with $domain
```

## SearchParamParseableTrait
### `parseSearchParam`
- filter helper method for parsing `SearchParam` annotations
- used for generic mapping filter classes to common query builder setters
- currently supports Doctrine ODM Query Builder
```php
use Nebkam\SymfonyTraits\Annotation\SearchParam;

class Filter {
	/**
	* @SearchParam(type="int")
	*/
	public $foo;
	/**
	* @SearchParam(type="string", field="customField")
	*/
	public $bar;
	/**
	* @SearchParam(type="int_array")
	*/
	public $baz;
	/**
	* @SearchParam(type="float_range", field="price", direction="from")
	*/
	public $priceFrom;
	/**
	* @SearchParam(type="float_range", field="price", direction="to")
	*/
	public $priceTo;
	/**
    * @SearchParam(callback={"Filter", "myCallback"})
    */
	public $custom;
	
	public static function myCallback($field, $value, $queryBuilder)
		{
		// call some setters on $queryBuilder
		
		return $queryBuilder;
		}
}

$filter = new Filter();
$filter->foo = "14";
$filter->bar = "Hello";
$filter->baz = [1,2];
$filter->priceFrom = 450.00;
$filter->priceTo = 1000.00;
```
would map to..
```php
$queryBuilder
	->field("foo")->equals(14)              // casted to int
	->field("customField")->equals("Hello") // field alias
	->field("baz")->in([1, 2])              // multiple values
	->field("price")->gte(450.00)           // range
	->field("price")->lte(1000.00)          // range
	...                                     // some setters called in Filter::myCallback
```
- use `callback` for multiple or complex builder setters