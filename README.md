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

### `handleUpload`
- controller helper method to validate a single file upload
- a lightweight alternative to a [File constraint](http://symfony.com/doc/current/reference/constraints/File.html), when you need just one file, not the whole form

```php
public function uploadImage(Request $request)
	{
	$file = $this->handleUpload($request, 'image');
	
	// do something with $file
	}
```

### `ValidationExceptionListener`
Since all `handle*` methods in this trait throw a `Nebkam\SymfonyTraits\ValidationException`, you have to catch it, either via `try {..} catch` in the controller or via global exception listener.
To ease this, this package includes a sample exception listener, which returns validation errors in JSON. You just have to register it as a service:

```yaml
Nebkam\SymfonyTraits\EventListener\ValidationExceptionListener:
    tags:
        - { name: kernel.event_listener, event: kernel.exception }
```
