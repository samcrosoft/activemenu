<?php
/**
 * Created by PhpStorm.
 * User: Adebola
 * Date: 03/09/2014
 * Time: 10:16
 */

namespace Samcrosoft\ActiveMenu\Core;
use Illuminate\Routing\Router;

/**
 * Class ActiveMenuManager
 * @package Samcrosoft\ActiveMenu\Core
 */
class ActiveMenuManager {

    use ActiveMenuRouteTraits;

    /**
     * @staticvar string
     * @const
     */
    const PARAM_SEPARATOR = "|";

    /**
     * This will inject the current router class by using Dependency Injection
     * @param Router $oRouter
     */
    function __construct(Router $oRouter){
        $this->setRouterObject($oRouter);
    }

    /**
     * @return Router|null
     */
    public function getRouterObject()
    {
        return $this->oRouterObject;
    }

    /**
     * @param Router|null $oRouterObject
     */
    public function setRouterObject($oRouterObject)
    {
        $this->oRouterObject = $oRouterObject;
    }



    /**
     * @param string $sRouteClassParam
     * @return string
     */
    public function activeRouteName($sRouteClassParam = '')
    {

        $aExplodeText = explode(self::PARAM_SEPARATOR, $sRouteClassParam);
        $sResolvedRouteName = trim(strval(array_get($aExplodeText, 0, NULL)));
        $sResolvedActiveClass = trim(strval(array_get($aExplodeText, 1, NULL)));
        $sResolvedInactiveClass = trim(strval(array_get($aExplodeText, 2, NULL)));

        // get the name of the route
        $oRouteInstance = \Route::current();
        $sRouteName = $oRouteInstance->getName();
        $bActive = FALSE;

        if (!empty($sResolvedRouteName) && !empty($sRouteName)) {
            $bActive = ($sRouteName == $sResolvedRouteName) ? TRUE : FALSE;
        }

        $sClassPrint = $bActive ? $sResolvedActiveClass : $sResolvedInactiveClass;

        return $sClassPrint;
    }


} 