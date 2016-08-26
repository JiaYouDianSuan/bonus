<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/7/21
 * Time: 16:00
 */
class Excel
{
    protected $_sFileName = "filename";
    protected $_arrHeader = array();
    protected $_arrRecord = array();

    /**
     * @param string $sFileName
     */
    public function setSFileName($sFileName)
    {
        $this->_sFileName = $sFileName;
    }

    /**
     * @param array $arrHeader
     */
    public function setArrHeader($arrHeader)
    {
        $this->_arrHeader = $arrHeader;
    }

    /**
     * @param array $arrRecord
     */
    public function setArrRecord($arrRecord)
    {
        $this->_arrRecord = $arrRecord;
    }

    public function outPutCsv()
    {
        header("Content-Type: application/vnd.ms-excel;charset=gbk");
        header("Content-Disposition: attachment;filename=$this->_sFileName.csv");
        header("Cache-Control: max-age=0");
        $fp = fopen('php://output', 'a');

        $arrHeadContent = array();
        foreach ($this->_arrHeader as $v) {
            $sContent = iconv('utf-8', 'gbk', $v);
            $arrHeadContent[] = $sContent;
        }
        fputcsv($fp, $arrHeadContent);

        foreach ($this->_arrRecord as $arrRow) {
            $arrRowContent = array();
            foreach ($arrRow as $sCell) {
                $sContent = iconv('utf-8', 'gbk', $sCell);
                $arrRowContent[] = $sContent;
            }
            fputcsv($fp, $arrRowContent);
        }
    }

    public function outPutXls()
    {
        require_once JFRAME_DISK_ROOT . '/Lib/phpexcell/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objSheet = $objPHPExcel->getActiveSheet();
        foreach ($this->_arrHeader as $k => $v) {
            $objSheet->setCellValueByColumnAndRow($k, 1, $v);
        }

        foreach($this->_arrRecord as $iRowNum =>$arrRow){
            $iColumnNum = 0;
            foreach($arrRow as $sCell){
                $objSheet->setCellValueExplicitByColumnAndRow($iColumnNum++, $iRowNum + 2, $sCell, PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->_sFileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}