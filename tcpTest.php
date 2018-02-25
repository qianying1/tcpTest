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

$socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
if (!$socket) {
    exit('创建socket失败');
}
$conn = socket_connect($socket, $host, $port);
if (!$conn) {
    exit('连接不上目标主机' . $host);
}
//@0100
/*$dataByte .= pack("a2", "@");
$dataByte .= pack("a2", "01");
$dataByte .= pack("a2", 0);
$dataByte .= pack("a12", "000000");
$dataByte .= pack("a12", "000000");*/
$dataByte .= pack("a", "@");
$dataByte .= pack("n", "11");
$dataByte .= pack("n", 24);
$dataByte .= pack("a12", "Super");
$dataByte .= pack("a12", "123456");
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
//$total_length . $code . $flag .
$tcpData1 = $dataByte;
echo '<br/>';
$tcpData1=bin2hex($tcpData1);
$tcpData1=str_split(str_replace(' ','',$tcpData1),2);// 将16进制数据转换成两个一组的数组
for ($j = 0; $j < count($tcpData1); $j++) {
    echo 'tcpData'.$j.' '.$tcpData1[$j].'<br/>';
    socket_write($socket, chr(hexdec($tcpData1[$j])));  // 逐组数据发送
}
echo '<br/>';
for ($j = 0; $j < count($tcpData1); $j++) {
    echo $tcpData1[$j].' ';
}
echo '<br/>';
for ($j = 0; $j < count($tcpData1); $j++) {
    echo hex2bin($tcpData1[$j]).' ';
}

//数据部分
//$tcpData = bin2hex($tcpData);
//$tcpData="40 31 31 30 00 30 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00";
$tcpData="40 00 0B 00 18 53 75 70 65 72 00 00 00 00 00 00 00 31 32 33 34 35 36 00 00 00 00 00 00";
$tcpData=str_split(str_replace(' ','',$tcpData),2);// 将16进制数据转换成两个一组的数组
//$result = socket_write($socket, bin2hex($tcpData));
echo '<br/>';
for ($j = 0; $j < count($tcpData); $j++) {
    echo $tcpData[$j].' ';
}
echo '<br/>';
for ($j = 0; $j < count($tcpData); $j++) {
    echo $j.' '.$tcpData[$j].'<br/>';
//    socket_write($socket, chr(hexdec($tcpData[$j])));  // 逐组数据发送
}
echo '<br/>';
for ($j = 0; $j < count($tcpData); $j++) {
    echo hex2bin($tcpData[$j]).' ';
}
echo "<br/>";
/*$resultData="40 00 0B 00 01 00 00 00";
echo '<br/>';
for ($j = 0; $j < count($resultData); $j++) {
    echo hex2bin($resultData[$j]).' ';
}*/
//echo "tcp datas: " . $tcpData;
//echo "write result: " . $result;
/*if (!$result) {
    echo '远程通讯失败';
} else {*/
    while ($buffer = @socket_read($socket, 1024, PHP_BINARY_READ)) {

        //服务端传来的信息
        /*echo "Buffer Data: " . $buffer . "\n";
        echo "Writing to Socket\n";*/
        //服务器端收到信息后，客户端接收服务端传给客户端的回应信息。
        echo "response from server was:" . $buffer . "\n";
    }
//}
socket_close($conn);