# ZeroemDeferredRequestBundle
Provides utilities to mark controller actions as deferred.  By simply 
adding the `@Defer` annotation to any Controller or Action, the request
will be intercepted during the `KernelEvents::CONTROLLER` phase of the
HttpKernel.  At this point in time, the Request object will be serialized
and stored in the database for later processing via the `deferred-request:process`
console command.

When a Request is deferred, an appropriate HTTP 202 Response is returned
containing :
- A Content-Location Header indicating where the deferred Response will be found
- A Link Header indicating where the status of the deferred Request may be monitored


# Examples

## Defer a single controller action
```php
// ...

/**
 * @Defer
 */
public function indexAction() 
{

}

// ...
```

## Defer all actions on a controller
```php
/**
 * @Defer
 */
class FooController extends Controller
{
   // Actions...
}
```
