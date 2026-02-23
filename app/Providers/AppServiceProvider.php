<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Discussion;
use App\Policies\CommentPolicy;
use App\Policies\DiscussionPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Discussion::class => DiscussionPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // When running in a subdirectory (e.g. XAMPP: /ClassConnect/public/), use request base URL
        // so all links (Lessons, Dashboard, etc.) point to the same base and don't 404
        if ($this->app->runningInConsole() === false && request()->getBasePath() !== '') {
            \Illuminate\Support\Facades\URL::forceRootUrl(request()->getSchemeAndHttpHost().request()->getBasePath());
        }

        // Force HTTPS in production if APP_URL is set to HTTPS
        if (config('app.env') === 'production' && str_starts_with(config('app.url', ''), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
