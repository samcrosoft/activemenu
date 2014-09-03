<?php
/**
 * Created by PhpStorm.
 * User: Adebola
 * Date: 03/09/2014
 * Time: 10:16
 */

namespace Samcrosoft\ActiveMenu\Core;

/**
 * Class ActiveMenuManager
 * @package Samcrosoft\ActiveMenu\Core
 */
class ActiveMenuManager {

    /**
     * @staticvar string
     * @const
     */
    const PARAM_SEPARATOR = "|";

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