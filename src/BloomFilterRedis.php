<?php

namespace Bloom;

class BloomFilterRedis
{
    protected $key;

    protected $hash;

    protected $hash_func;

    protected $redis;

    public function __construct($key, array $hash_func, \Redis $redis)
    {
        if (empty($key) || empty($hash_func)) {
            throw new \Exception('需要定义使用的key和hash函数', 1);
        }

        $this->key = $key;
        $this->hash = new BloomFilterHash();
        $this->hash_func = $hash_func;
        $this->redis = $redis;
    }

    /**
     * @desc 添加
     *
     * @param $string
     * @return bool
     */
    public function add($string)
    {
        foreach ($this->hash_func as $func) {
            $offset = $this->hash->$func($string);
            $this->redis->setBit($this->key, $offset, 1);
        }

        return true;
    }

    /**
     * @desc 判断是否存在
     *
     * @param $string
     * @return bool
     */
    public function exists($string)
    {
        $pipe = $this->redis->multi();
        $len = strlen($string);
        foreach ($this->hash_func as $func) {
            $offset = $this->hash->$func($string, $len);
            $pipe = $pipe->getBit($this->key, $offset);
        }

        $res = $pipe->exec();
        foreach ($res as $val) {
            if ($val == 0) {
                return false;
            }
        }

        return true;
    }
}