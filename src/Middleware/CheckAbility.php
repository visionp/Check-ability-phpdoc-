<?php

namespace Visionp\CheckAbilityPhpdoc\Middleware;

use Closure;
use Illuminate\Routing\Route;

class CheckAbility
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \ReflectionException
     */
    public function handle($request, Closure $next)
    {
        $route = $request->route();

        foreach($this->getDocBlocks($route) as $block) {
            if($ability = $this->isDeniedByAbility($block)) {
                abort(401, (string) $ability);
            }
        }

        return $next($request);
    }

    /**
     * @param Route $route
     * @return array
     * @throws \ReflectionException
     */
    protected function getDocBlocks($route)
    {
        if(is_callable($route->action['uses'])) {
            return [];
        }

        $reflectionClass = new \ReflectionClass($route->getController());
        $method = $reflectionClass->getMethod($route->getActionMethod());
        return explode("\n", $method->getDocComment());
    }

    /**
     * @param $block
     * @return bool
     */
    protected function isDeniedByAbility($block)
    {
        if(preg_match('#\*\s{0,}@ability\s{1,}(.*)$#Ui', trim($block), $ability)){
            if($ability[1] && !\Auth::user()->can($ability[1])) {
                return $ability[1];
            }
        }
        return false;
    }

}
