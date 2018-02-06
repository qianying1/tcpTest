<?php
/**
 * socket字节流通信 total_length 4b code 2b flag 4b data
 * Created by PhpStorm.
 * User: qianying
 * Date: 2018/2/6
 * Time: 22:14
 */

$host = '39.108.219.90';
$port = 56200;
$byte = '';
$dataByte = '';

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket) {
    exit('创建socket失败');
}
$conn = socket_connect($socket, $host, $port);
if (!$conn) {
    exit('连接不上目标主机' . $host);
}
$dataByte .= pack("a2", "@");
$dataByte .= pack("a2", "11");
$dataByte .= pack("a2", 12);
$dataByte .= pack("a6", "admin");
$dataByte .= pack("a6", "111111");
$dataLength = 18;

//前缀
$totalLength = 4 + intval($dataLength) + 2 + 4;
echo "total length: " . $totalLength;
$total_length = pack('L', $totalLength);
$code = pack('v', 11);
$flag = pack('L', 24);
/*
 * $length = $this->byte->getLength();
        $length = intval($length) + self::CODE_LENGTH + self::FLAG_LENGTH;
        return pack('L', $length);
 */
$tcpData = $total_length . $code . $flag . $dataByte;
//数据部分
$result = socket_write($socket, $tcpData);
echo "tcp datas: " . $tcpData;
echo "write result: " . $result;
if (!$result) {
    exit('远程通讯失败');
} else {
    while ($buffer = @socket_read($socket, 1024, PHP_NORMAL_READ)) {

        //服务端传来的信息
        /*echo "Buffer Data: " . $buffer . "\n";
        echo "Writing to Socket\n";*/
        //服务器端收到信息后，客户端接收服务端传给客户端的回应信息。
        while ($buffer = socket_read($socket, 1024, PHP_NORMAL_READ)) {
            echo "response from server was:" . $buffer . "\n";
        }
    }
}
socket_close($conn);