<?php
/**
 * Created by PhpStorm.
 * User: qianying
 * Date: 2018/1/31
 * Time: 21:39
 */

namespace app\index\common;

class SocketCommunicationTestUtils
{
    public static function testSocket()
    {
        $data[] = 'testzouhao';
        $data[] = 'a';
        $gameSocket = new SocketInitializationUtils();
        $gameSocket->code = 11;
        $gameSocket->write($data);
    }
}