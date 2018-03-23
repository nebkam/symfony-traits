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