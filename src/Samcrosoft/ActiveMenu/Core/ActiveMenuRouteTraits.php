<?php
/**
 * Created by PhpStorm.
 * User: Adebola
 * Date: 18/11/2014
 * Time: 14:34
 */

namespace Samcrosoft\ActiveMenu\Core;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;

trait ActiveMenuRouteTraits {

    /**
     * @todo - make this settable by config
     * @var string
     */
    static $sActiveClassName = "active";

    /**
     * @var Router|null
     */
    private $oRouterObject =null;

    /**
     * @return string
     */
    static private function getStaticClassName(){
        return static::$sActiveClassName;
    }

    /**
     * @return Router|null
     */
    protected function getRouterObject()
    {
        return $this->oRouterObject;
    }

    /**
     * @param Router|null $oRouterObject
     */
    protected function setRouterObject($oRouterObject)
    {
        $this->oRouterObject = $oRouterObject;
    }

    /**
     * @param null $sClassName
     * @return null|string
     */
    private function mapClassName($sClassName = null){
        $sClassName = is_null($sClassName) ? self::getStaticClassName() : $sClassName;
        return $sClassName;
    }

    /*
    |--------------------------------------------------------------------------
    | PATTERNS / REGEX
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Return 'active' class if current route match a pattern
     *
     * @param string|array $patterns
     * @param string $sClass
     *
     * @return string
     */
    public function pattern($patterns, $sClass=null)
    {
        $sClass = $this->mapClassName($sClass);

        $oCurrentRequest = $this->getRouterObject()->getCurrentRequest();
        $oCurrentRequest = !$oCurrentRequest ? '' : $oCurrentRequest;

        if(empty($oCurrentRequest))
        {
            return '';
        }

        $uri = urldecode($oCurrentRequest->path());

        if (!is_array($patterns))
        {
            $patterns = [$patterns];
        }

        foreach ($patterns as $p)
        {
            if (str_is($p, $uri))
            {
                return $sClass;
            }
        }

        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | ROUTES
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Return 'active' class if current route name match one of provided names
     *
     * @param string|array $sName
     * @param string $sClass
     *
     * @return string
     */
    public function route($sName, $sClass = null)
    {
        $sReturn = '';
        $sClass = $this->mapClassName($sClass);
        $sRouteName = $this->getRouterObject()->currentRouteName();

        if (!$sRouteName) return '';

        if($sRouteName == $sName)
            $sReturn = $sClass;

        return $sReturn;
    }

    /**
     * @param      $aRoutes
     * @param null $sClass
     * @return null|string
     */
    public function routes($aRoutes, $sClass = null){
        $sReturn = '';
        $sClass = $this->mapClassName($sClass);

        if(!is_array($aRoutes)){
            $aRoutes = [$aRoutes];
        }

        $sRouteName = $this->getRouterObject()->currentRouteName();

        if (!$sRouteName) return $sReturn;

        $sReturn = in_array($sRouteName, $aRoutes) ? $sClass : $sReturn;

        return $sReturn;
    }

    /**
     * Check the current route name with one or some patterns
     *
     * @param string|array $sPatterns
     * @param string $sClass
     *
     * @return string the <code>$class</code> if matched
     * @since 1.2
     */
    public function routePattern($sPatterns, $sClass=null)
    {
        if(is_array($sPatterns)) return $this->routePatterns($sPatterns, $sClass);


        $sClass = $this->mapClassName($sClass);
        $sRouteName = $this->getRouterObject()->currentRouteName();

        if (!$sRouteName) return '';

        $sPatterns = strval($sPatterns);
        $sReturn = Str::is($sPatterns, $sRouteName) ? $sClass : '';

        return $sReturn;
    }


    /**
     * this is a clone of the routePattern method except that it works both on arrays and string
     * @param      $aPatterns
     * @param null $sClass
     * @return null|string
     */
    public function routePatterns($aPatterns, $sClass=null)
    {
        // fallback on route pattern
        if(!is_array($aPatterns)) return $this->routePattern(strval($aPatterns), $sClass);

        $sClass = $this->mapClassName($sClass);

        $sRouteName = $this->getRouterObject()->currentRouteName();

        if (!$sRouteName) return '';

        $aPatterns = !is_array($aPatterns) ? [$aPatterns] : $aPatterns;

        foreach ($aPatterns as $sPattern)
        {
            if (str_is($sPattern, $sRouteName)) return $sClass;
        }

        return '';
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    |
    */
    /**
     * Return 'active' class if current route action match one of provided action names
     *
     * @param string|array $actions
     * @param string $sClass
     *
     * @return string
     */
    public function action($actions, $sClass = null)
    {
        $sReturn = '';
        $sClass = $this->mapClassName($sClass);
        $routeAction = $this->getRouterObject()->currentRouteAction();
        if (!is_array($actions)) $actions = [$actions];
        $sReturn = in_array($routeAction, $actions) ? $sClass : $sReturn;
        return $sReturn;
    }



    /*
    |--------------------------------------------------------------------------
    | CONTROLLERS
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Return 'active' class if current controller match a controller name and
     * current method doest not belong to excluded methods. The controller name
     * and method name are gotten from <code>getController</code> and <code>getMethod</code>.
     *
     * @param string $controller
     * @param string $sClass
     * @param array $excludedMethods
     *
     * @return string
     */
    public function controller($controller, $sClass = null, $excludedMethods = [])
    {
        $sClass = $this->mapClassName($sClass);
        $currentController = $this->getController();

        if ($currentController !== $controller)
        {
            return '';
        }

        $currentMethod = $this->getMethod();

        if (in_array($currentMethod, $excludedMethods))
        {
            return '';
        }

        return $sClass;
    }

    /**
     * Return 'active' class if current controller name match one of provided
     * controller names.
     *
     * @param array $controllers
     * @param string $sClass
     * @return string
     */
    public function controllers(array $controllers, $sClass = null)
    {
        $sClass = $this->mapClassName($sClass);

        $currentController = $this->getController();
        $sReturn = '';
        $sReturn = in_array($currentController, $controllers)? $sClass : $sReturn;
        return $sReturn;
    }

    /**
     * Get the current controller name with the suffix 'Controller' trimmed
     *
     * @return string|null
     */
    private function getController()
    {
        $action = $this->getRouterObject()->currentRouteAction();

        if ($action)
        {
            $extractedController = head(Str::parseCallback($action, null));
            // Trim the "Controller" word if it is the last word
            return preg_replace('/^(.+)(Controller)$/', '${1}', $extractedController);
        }

        return null;
    }

    /**
     * Get the current method name with the prefix 'get', 'post', 'put', 'delete', 'show' trimmed
     *
     * @return string|null
     */
    private function getMethod()
    {
        $action = $this->getRouterObject()->currentRouteAction();

        if ($action)
        {
            $extractedController = last(Str::parseCallback($action, null));
            // Trim the "show", "post", "put", "delete", "get" if this is the
            // prefix of the method name
            return $extractedController ? preg_replace('/^(show|get|put|delete|post)(.+)$/', '${2}', $extractedController) : null;
        }

        return null;
    }
} 