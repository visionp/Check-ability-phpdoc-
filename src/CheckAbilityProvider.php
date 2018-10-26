<?php
/**
 * Created by PhpStorm.
 * User: Visionp
 * Date: 10/26/18
 * Time: 3:06 PM
 */
namespace Visionp\CheckAbilityPhpdoc;

use Carbon\Laravel\ServiceProvider;
use Visionp\CheckAbilityPhpdoc\Middleware\CheckAbility;

class CheckAbilityProvider extends ServiceProvider
{

    public function boot()
    {
        /**
         * @var \Illuminate\Routing\Router $router
         */
        $router = app('router');
        $router->aliasMiddleware('auth.check_ability.php_doc', CheckAbility::class);
    }

}