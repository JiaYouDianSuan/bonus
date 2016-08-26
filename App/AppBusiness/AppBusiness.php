<?php

/**
 * Created by PhpStorm.
 * User: jinli
 * Date: 2016/7/8
 * Time: 16:57
 */
class AppBusiness
{

    protected $_sDealerNo = "";

    /**
     * AppBusiness constructor.
     * @param string $_sDealerNo
     */
    public function __construct($_sDealerNo)
    {
        $this->_sDealerNo = $_sDealerNo;
    }

    static function create($_sDealerNo)
    {
        return new self ($_sDealerNo);
    }

    function getDb()
    {
        return Database::create();
    }
}