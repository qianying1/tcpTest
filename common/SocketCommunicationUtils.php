<?php
/**
 * Created by PhpStorm.
 * User: qianying
 * Date: 2018/1/31
 * Time: 21:38
 */

namespace app\index\common;


class SocketCommunicationUtils
{
    private $socket;
    private $port = 56200;
    private $host = '39.108.219.90';
    private $byte;
    private $code;

    const CODE_LENGTH = 2;
    const FLAG_LENGTH = 4;

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __construct($host = '39.108.219.90', $port = 56200)
    {
        $this->host = $host;
        $this->port = $port;
        $this->setSocket(socket_create(AF_INET, SOCK_STREAM, SOL_TCP));
        if (!$this->socket) {
            exit('创建socket失败');
        }
        $result = socket_connect($this->socket, $this->host, $this->port);
        if (!$result) {
            exit('连接不上目标主机' . $this->host);
        }
        $this->byte = new ByteModel();
    }

    public function write($data)
    {
        if (is_string($data) || is_int($data) || is_float($data)) {
            $data[] = $data;
        }
        if (is_array($data)) {
            foreach ($data as $vo) {
                $this->byte->writeShortInt(strlen($vo));
                $this->byte->writeChar($vo);
            }
        }
        $this->setPrev();
        return $this->send();
    }

    public function writeBinaryData($data, $length)
    {
        if (is_string($data) || is_int($data) || is_float($data)) {
            $data[] = $data;
            $length[] = $length;
        }
        if (is_array($data) && is_array($length)) {
            $i = 0;
            foreach ($data as $vo) {
                $this->byte->writeBinary($vo, $length[$i++]);
            }
        }
        $this->setPrev();
        return $this->send();
    }

    /*
     *设置表头部分
     *表头=length+code+flag
     *length是总长度(4字节)  code操作标志(2字节)  flag暂时无用(4字节)
     */
    private function getHeader()
    {
        $length = $this->byte->getLength();
        $length = intval($length) + self::CODE_LENGTH + self::FLAG_LENGTH;
        return pack('L', $length);
    }

    private function getCode()
    {
        return pack('v', $this->code);
    }

    private function getFlag()
    {
        return pack('L', 24);
    }

    private function setPrev()
    {
        $this->byte->setBytePrev($this->getHeader() . $this->getCode() . $this->getFlag());
    }

    private function send()
    {
        $result = socket_write($this->socket, $this->byte->getByte());
        return $result;
    }

    public function readData($socket)
    {
        var_dump($socket);
        while ($buffer = socket_read($socket, 1024, PHP_NORMAL_READ)) {
            if (preg_match("/not connect/", $buffer)) {
                echo "don`t connect\n";
                break;
            } else {
                //服务端传来的信息
                /*echo "Buffer Data: " . $buffer . "\n";
                echo "Writing to Socket\n";*/
                //服务器端收到信息后，客户端接收服务端传给客户端的回应信息。
                while ($buffer = socket_read($this->socket, 1024, PHP_NORMAL_READ)) {
                    var_dump("response from server was:" . $buffer . "\n");
                }
            }
        }
    }

    /**
     * @return resource
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * @param resource $socket
     */
    public function setSocket($socket)
    {
        $this->socket = $socket;
    }

    public function __desctruct()
    {
        socket_close($this->socket);
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}