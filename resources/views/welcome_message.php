To refactor and move the `.htaccess` rules into a Laravel 11 project, we'll need to translate these rules into Laravel-compatible configurations. Here's how we can do it:

1. **ErrorDocument Directive**:
   - In Laravel, handle 404 errors by creating a custom error view in `resources/views/errors/404.blade.php`.

2. **RewriteEngine and RewriteRules**:
   - Laravel uses routing defined in `routes/web.php`. We’ll convert each `RewriteRule` into a corresponding route.

3. **Access Restrictions**:
   - Implement access restrictions using middleware.

4. **Rewrite Conditions**:
   - Use middleware to handle conditions for specific user agents or request methods.

### Steps to Implement:

1. **Create Custom Error View**:
   ```bash
   mkdir -p resources/views/errors
   echo "<h1>Page not found</h1>" > resources/views/errors/404.blade.php
   ```

2. **Define Routes in `routes/web.php`**:
   ```php
   use Illuminate\Support\Facades\Route;

   Route::get('index', function () {
       return view('index');
   });

   Route::get('ajaxinfo', function () {
       return view('ajax');
   });

   // Add routes for all other rewrite rules similarly
   ```

3. **Implement Middleware for Access Restrictions**:
   ```php
   // Create a middleware to block specific IP ranges
   php artisan make:middleware BlockIP

   // In BlockIP middleware (app/Http/Middleware/BlockIP.php):
   public function handle($request, Closure $next)
   {
       $denyIps = [
           '41.87.128.0-41.87.159.255',
           // Add other IP ranges
       ];

       $clientIp = $request->ip();

       foreach ($denyIps as $range) {
           // Logic to check if $clientIp falls within $range
       }

       return $next($request);
   }

   // Register the middleware in app/Http/Kernel.php
   protected $routeMiddleware = [
       'block.ip' => \App\Http\Middleware\BlockIP::class,
   ];
   ```

4. **Handle Rewrite Conditions**:
   - Use middleware to check for user agents or request methods.

### Example Middleware for User Agent Checks:
```php
php artisan make:middleware CheckUserAgent

// In CheckUserAgent middleware (app/Http/Middleware/CheckUserAgent.php):
public function handle($request, Closure $next)
{
    $blockedAgents = [
        'libwww-perl', 'wget', 'python', 'nikto', 'curl', 'scan', 'java', 'winhttp', 'clshttp', 'loader'
    ];

    $userAgent = $request->header('User-Agent');

    foreach ($blockedAgents as $agent) {
        if (stripos($userAgent, $agent) !== false) {
            return response('Forbidden', 403);
        }
    }

    return $next($request);
}

// Register the middleware in app/Http/Kernel.php
protected $routeMiddleware = [
    'check.user.agent' => \App\Http\Middleware\CheckUserAgent::class,
];
```

5. **Apply Middleware to Routes**:
```php
Route::middleware(['block.ip', 'check.user.agent'])->group(function () {
    Route::get('index', function () {
        return view('index');
    });

    // Add other routes similarly
});
```

This converts the `.htaccess` rules into Laravel-compatible configurations. Ensure to test the Laravel application to verify that all configurations work as expected.