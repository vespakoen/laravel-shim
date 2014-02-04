# Shim

This package bootstraps a L4 application.
It can be loaded as a L3 bundle, and will NOT register any aliases so it will not conflict with L3.

Shim will run the L4 application, and will see if the response code is 404.
If so, it will not render the result and assume no L4 route was defined for the current url and continues running the L3 application.

This has a side effect that when an error occurs in a L4 package, which most of the times will trigger a 404 error, the error will not be shown.

To avoid this, you can set the 'debug_errors' flag to true in the `vendor/vespakoen/shim/src/app/config/app.php` file.


Let's get a L4 config value, note that we alias the class so we won't screw up other routes that use `Config`.
```php
<?php

use Illuminate\Support\Facades\Config as L4Config;

// routes.php
Route::get('test', function()
{
	$value = L4Config::get('some.value');
});
```

Here, we use the L4 mail class, since L3 didn't have one, we can "use" it normally, without aliasing since it cannot conflict with anything else.
```php
<?php
// routes.php

use Illuminate\Support\Facades\Mail;

// l3 route
Route::get('send_some_funky_mail', function()
{
	Mail::to('bladiebla')->etc();
});
```

Within other packages, you don't have to alias (prefix with "L4") classes, just make sure you put the use statement at the top.
