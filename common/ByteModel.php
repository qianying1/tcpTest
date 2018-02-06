<?php
/**
 * Created by PhpStorm.
 * User: qianying
 * Date: 2018/1/31
 * Time: 21:33
 */

namespace app\index\common;


class ByteModel
{
    //长度
    private $length = 0;

    private $byte = '';
    //操作码
    private $code;

    public function setBytePrev($content)
    {
        $this->byte = $content . $this->byte;
    }

    public function getByte()
    {
        return $this->byte;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function writeChar($string)
    {
        $this->length += strlen($string);
        $str = array_map('ord', str_split($string));
        foreach ($str as $vo) {
            $this->byte .= pack('c', $vo);
        }
        $this->byte .= pack('c', '0');
        $this->length++;
    }

    public function writeBinary($data, $length = 2)
    {
        $this->length += $length;
        $this->byte .= pack("A" . $length, $data);
    }

    public function writeInt($str)
    {
        $this->length += 4;
        $this->byte .= pack('L', $str);
    }

    public function writeShortInt($interge)
    {
        $this->length += 2;
        $this->byte .= pack('v', $interge);
    }
}