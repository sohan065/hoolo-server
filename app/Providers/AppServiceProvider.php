<?php

namespace App\Providers;

use App\Services\SmsRepositoryServices;
use App\Services\SslRepositoryServices;
use Illuminate\Support\ServiceProvider;
use App\Services\MailRepositoryServices;
use App\Services\PostRepositoryservices;
use App\Services\UserRepositoryServices;
use App\Services\BrandRepositoryServices;
use App\Services\TokenRepositoryServices;
use App\Services\BannerRepositoryServices;
use App\Services\CourseRepositoryServices;
use App\Services\AddressRepositoryServices;
use App\Services\ProductRepositoryServices;
use App\Services\CategoryRepositoryServices;
use App\Services\MerchantRepositoryServices;
use App\Services\AttributesRepositoryServices;
use App\Services\FileSystemRepositoryServices;
use App\Services\InstructorRepositoryServices;
use App\Services\SuperAdminRepositoryServices;
use App\Services\AppRegisterRepositoryServices;
use App\Services\PostGalleryRepositoryServices;
use App\Services\LiveroomRepositoryServices;
use App\Services\BkashRepositoryServices;
use App\Services\InvoiceRepositoryServices;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // token services register
        $this->app->singleton('TokenRepositoryServices', function ($app) {
            return new TokenRepositoryServices;
        });
        //app register
        $this->app->singleton('AppRegisterRepositoryServices', function ($app) {
            return new AppRegisterRepositoryServices;
        });
        //All File Services register
        $this->app->singleton('FileSystemRepositoryServices', function ($app) {
            return new FileSystemRepositoryServices;
        });
        //Merchant services register
        $this->app->singleton('MerchantRepositoryServices', function ($app) {
            return new MerchantRepositoryServices;
        });
        //mail services register
        $this->app->singleton('MailRepositoryServices', function ($app) {
            return new MailRepositoryServices;
        });
        //sms services register
        $this->app->singleton('SmsRepositoryServices', function ($app) {
            return new SmsRepositoryServices;
        });
        //product services register
        $this->app->singleton('ProductRepositoryServices', function ($app) {
            return new ProductRepositoryServices;
        });
        //brand services register
        $this->app->singleton('BrandRepositoryServices', function ($app) {
            return new BrandRepositoryServices;
        });
        //Address services register
        $this->app->singleton('AddressRepositoryServices', function ($app) {
            return new AddressRepositoryServices;
        });
        //Category services register
        $this->app->singleton('CategoryRepositoryServices', function ($app) {
            return new CategoryRepositoryServices;
        });
        //Attributes or product varient services register
        $this->app->singleton('AttributesRepositoryServices', function ($app) {
            return new AttributesRepositoryServices;
        });
        //course services register
        $this->app->singleton('CourseRepositoryServices', function ($app) {
            return new CourseRepositoryServices;
        });
        // // creator services register
        // $this->app->singleton('CreatorRepositoryServices', function ($app) {
        //     return new CreatorRepositoryServices;
        // });
        //live room service register
        $this->app->singleton('LiveroomRepositoryServices', function ($app) {
            return new  LiveroomRepositoryServices;
        });
        // user services register
        $this->app->singleton('UserRepositoryServices', function ($app) {
            return new  UserRepositoryServices;
        });
        // post gallery services register
        $this->app->singleton('PostGalleryRepositoryServices', function ($app) {
            return new  PostGalleryRepositoryServices;
        });
        // post  services register
        $this->app->singleton('PostRepositoryservices', function ($app) {
            return new  PostRepositoryservices;
        });
        // super admin  services register
        $this->app->singleton('SuperAdminRepositoryServices', function ($app) {
            return new  SuperAdminRepositoryServices;
        });
        // instructor  services register
        $this->app->singleton('InstructorRepositoryServices', function ($app) {
            return new  InstructorRepositoryServices;
        });
        // banner  services register
        $this->app->singleton('BannerRepositoryServices', function ($app) {
            return new  BannerRepositoryServices;
        });
        // bkash  services register
        $this->app->singleton('BkashRepositoryServices', function ($app) {
            return new  BkashRepositoryServices;
        });
        // invoice  services register
        $this->app->singleton('InvoiceRepositoryServices', function ($app) {
            return new  InvoiceRepositoryServices;
        });
         // SSL  services register
        $this->app->singleton('SslRepositoryServices', function ($app) {
            return new  SslRepositoryServices;
        });
    }
    //
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
