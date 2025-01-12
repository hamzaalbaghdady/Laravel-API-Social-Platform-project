<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Policies\ProfilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ProfilePolicy::class => Profile::class,
        PostPolicy::class => Post::class,
        CommentPolicy::class => Comment::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
