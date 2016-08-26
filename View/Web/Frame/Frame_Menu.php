<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Frame_Menu
 *
 * @author jinlee
 */
class Frame_Menu extends Frame {

    function __construct() {
        
    }

    function show() {
        $arrAuthority = $this->getAuthority();
        $sSql = 'Select 
            MENU_CLASS.sName As sClassName, 
            MENU.* 
            From sys_menu As MENU 
            Left Join sys_menu_class As MENU_CLASS 
            On MENU_CLASS.iPk=MENU.iClassPk
            Where MENU.bDisplay=1';
        if($arrAuthority != 'All'){
        	empty($arrAuthority['Authority']) && $arrAuthority['Authority'] = array();
            $sSql .= ' And MENU.sApp In ("'.implode('","', array_keys($arrAuthority['Authority'])).'")';
        }
        $arrRecord = $this->getDb()->select($sSql);

        $arrMenuClass = array();
        foreach ($arrRecord as $v) {
            if (empty($arrMenuClass[$v['iClassPk']])) {
                $arrMenuClass[$v['iClassPk']] = array(
                    'name' => $v['sClassName'],
                    'menu' => array()
                );
            }

            $arrMenuClass[$v['iClassPk']]['menu'][] = array(
                'pk' => $v['iPk'],
                'name' => $v['sName'],
                'app' => $v['sApp'],
                'page' => $v['sPage'],
            );
        }

        $sHtml = '<div id="leftside">';
        $sHtml .= '<div id="sidebar_s"><div class="collapse"><div class="toggleCollapse"><div></div></div></div></div>';
        $sHtml .= '<div id="sidebar">';
        $sHtml .= '<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>';
        $sHtml .= '<div class="accordion" fillSpace="sidebar">';
        foreach ($arrMenuClass as $arrClass) {
            $sHtml .= '<div class="accordionHeader"><h2><span>Folder</span>' . $arrClass['name'] . '</h2></div>';
            $sHtml .= '<div class="accordionContent">';
            $sHtml .= '<ul class="tree">';
            foreach ($arrClass['menu'] as $arrMenu) {
                $sHtml .= '<li><a 
                        href="' . JFRAME_WWW_ROOT . 'Router.php?App=' . $arrMenu['app'] . '&Page=' . $arrMenu['page'] . '" 
                        rel="navTab_' . $arrMenu['app'] . '" 
                        target="navTab" >' . $arrMenu['name'] . '</a></li>';
            }
            $sHtml .= '</ul>';
            $sHtml .= '</div>';
        }
        $sHtml .= '</div>';
        $sHtml .= '</div>';
        $sHtml .= '</div>';

        return $sHtml;
    }

}

?>
