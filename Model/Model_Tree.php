<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Tree
 *
 * @author jinlee
 */
class Model_Tree extends Model {

    protected $_sTable;

    function __construct($sModel, $sApp) {
        parent::__construct($sModel, $sApp);
        $mapAppStruct = $this->getAppStruct();
        $this->setTable($mapAppStruct[$this->getApp()]['table']['name']);
    }

    function setTable($sTable) {
        $this->_sTable = $sTable;
    }

    function getTable() {
        return $this->_sTable;
    }

    function fetchTreeNode() {
        $sTable = $this->getTable();
        $sSql = 'Select * From ' . $sTable . ' Order By iLftVal Asc';
        $arrTreeNode = $this->getDb()->select($sSql);

        $arrStack = array();
        foreach ($arrTreeNode as $v) {
            $arrNode = array(
                'pk' => $v['iPk'],
                'parentPk' => $v['iParentPk'],
                'lft' => $v['iLftVal'],
                'rgt' => $v['iRgtVal'],
                'text' => $v['sName'],
                'child' => array()
            );

            if (count($arrStack) > 0) {
                while ($arrStack[count($arrStack) - 1]['rgt'] < $arrNode['rgt']) {
                    array_pop($arrStack);
                }

                $refLastNode = &$arrStack[count($arrStack) - 1];
                $refLastNode['child'][] = $arrNode;
                $arrStack[] = &$refLastNode['child'][count($refLastNode['child']) - 1];
            } else {
                $arrTree = $arrNode;
                $arrStack[] = &$arrTree;
            }
        }
        $arrTree = empty($arrTree) ? array() : array($arrTree);
        return $arrTree;
    }

    function insertChild($iParentPk) {
        if ($iParentPk == 0) {
            $sSql = 'Select count(iPk) As iCount From ' . $this->getTable() . ' Where iParentPk=0';
            $RS = $this->getDB()->select($sSql);
            $iCount = $RS[0]['iCount'];

            if ($iCount > 0) {//只能有一个根节点
                return false;
            }
        }

        $sText = '';
        $sSql = 'Select iLftVal, iRgtVal From ' . $this->getTable() . ' Where iPk=' . $iParentPk;
        $RS = $this->getDB()->select($sSql);
        //如果结果集是空则说明要插入的是根节点,iParentPk=0
        $intRgt_Parent = empty($RS[0]['iRgtVal']) ? 1 : $RS[0]['iRgtVal'];

        $arrNode = array(
            'parentPk' => $iParentPk,
            'lft' => $intRgt_Parent,
            'rgt' => $intRgt_Parent + 1,
            'text' => $sText
        );
        return $this->insertNode($arrNode);
    }

    function insertNode($arrNode) {
        $sTable = $this->getTable();

        $sSql = 'Update ' . $sTable . ' Set iRgtVal=iRgtVal+2 Where iRgtVal>=' . $arrNode['lft'];
        $this->getDb()->query($sSql);

        $sSql = 'Update ' . $sTable . ' Set iLftVal=iLftVal+2 Where iLftVal>=' . $arrNode['lft'];
        $this->getDb()->query($sSql);

        $sSql = "Insert Into $sTable (sName, iParentPk, iLftVal, iRgtVal) values(
            '" . $arrNode['text'] . "', 
            '" . $arrNode['parentPk'] . "', 
            '" . $arrNode['lft'] . "', 
            '" . $arrNode['rgt'] . "')";

        $iInsertPk = $this->getDb()->insert($sSql);
        return $iInsertPk;
    }

    function deleteNode($iNodePk){
        $sTable = $this->getTable();
        $sSql = "Select iLftVal,iRgtVal,(iRgtVal-iLftVal+1) As iWidth From $sTable Where iPk='$iNodePk'";
        $arrRs = $this->getDb()->select($sSql);
        $iLftVal = $arrRs[0]['iLftVal'];
        $iRgtVal = $arrRs[0]['iRgtVal'];
        $iWidth = $arrRs[0]['iWidth'];

        $sSql = "Delete From $sTable Where iLftVal>=$iLftVal and iLftVal<=$iRgtVal";
        $this->getDb()->query($sSql);

        $sSql = "Update $sTable Set iRgtVal=iRgtVal-$iWidth Where iRgtVal>$iRgtVal";
        $this->getDb()->query($sSql);

        $sSql = "Update $sTable Set iLftVal=iLftVal-$iWidth Where iLftVal>$iRgtVal";
        $this->getDb()->query($sSql);
    }

}

?>
