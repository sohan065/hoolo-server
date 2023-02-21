<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            // admin api
            Route::prefix('admin')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
            // registration api
            Route::prefix('registration')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/registration.php'));
            // brand api
            Route::prefix('brand')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/brand.php'));
            // app api
            Route::prefix('app')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/app.php'));
            // merchant api
            Route::prefix('merchant')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/merchant.php'));
            // address api
            Route::prefix('address')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/address.php'));
            // Category api
            Route::prefix('category')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/category.php'));
            // attribute api
            Route::prefix('attribute')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/attribute.php'));
            // product and product gallery api
            Route::prefix('product')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/product.php'));
            // course api
            Route::prefix('course')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/course.php'));
            // creator api
            Route::prefix('creator')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/creator.php'));
            // live room api
            Route::prefix('live')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/live.php'));
            // user api
            Route::prefix('user')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/user.php'));
            // post api
            Route::prefix('post')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/post.php'));
            // super admin api
            Route::prefix('superadmin')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/superadmin.php'));
            // instructor api
            Route::prefix('instructor')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/instructor.php'));
            // banner api
            Route::prefix('banner')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/banner.php'));
            // workshop api
            Route::prefix('workshop')
                ->middleware('app')
                ->namespace($this->namespace)
                ->group(base_path('routes/workshop.php'));
        });
    }
    // 
    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
