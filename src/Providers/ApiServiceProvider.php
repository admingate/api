<?php

namespace Admingate\Api\Providers;

use ApiHelper;
use Admingate\Api\Facades\ApiHelperFacade;
use Admingate\Api\Http\Middleware\ForceJsonResponseMiddleware;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Admingate\Base\Traits\LoadAndPublishDataTrait;

class ApiServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        AliasLoader::getInstance()->alias('ApiHelper', ApiHelperFacade::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/api')
            ->loadRoutes()
            ->loadAndPublishConfigurations(['api', 'permissions'])
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->loadAndPublishViews();

        if (ApiHelper::enabled()) {
            $this->loadRoutes(['api']);
        }

        $this->app['events']->listen(RouteMatched::class, function () {
            if (ApiHelper::enabled()) {
                $this->app['router']->pushMiddlewareToGroup('api', ForceJsonResponseMiddleware::class);
            }

            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-packages-api',
                    'priority' => 9999,
                    'parent_id' => 'cms-core-settings',
                    'name' => 'packages/api::api.settings',
                    'icon' => null,
                    'url' => route('api.settings'),
                    'permissions' => ['api.settings'],
                ]);
        });

        $this->app->booted(function () {
            config([
                'scribe.routes.0.match.prefixes' => ['api/*'],
                'scribe.routes.0.apply.headers' => [
                    'Authorization' => 'Bearer {token}',
                    'Api-Version' => 'v1',
                ],
            ]);
        });
    }
}
