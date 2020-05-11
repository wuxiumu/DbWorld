<?php
/**
 * 通过redis实现分布式锁
 */
class lockModel
{
    private $redis;//redis服务
    private $lockNames = [];

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    /**
     *加锁
     * @param $name 锁标识名
     * @param $timeout 循环加锁失败的超时时间
     * @param $expire 当前锁的最大生存时间（秒）
     * @params $sleepTime 循环请求加锁失败的休眠时间（毫秒）
     * @return bool
     **/
    public function lock($name, $timeout = 0, $expire = 15, $sleepTime = 1000000)
    {
        date_default_timezone_set('Asia/Shanghai');

        if($name == null) return false;

        //取得当前时间
        $now = time();
        //加锁失败等待超时时刻
        $timeoutAt = $now + $timeout;
        //当前锁过期时刻
        $expireAt = $now + $expire;

        $lockKey = "Lock:{$name}";

        while(true) {
            //存储当前锁的过期时刻
            $result = $this->redis->setnx($lockKey, $expireAt);
            if($result) {//加锁成功
                //设置当前所的过期时间
                $this->redis->expire($lockKey, $expireAt);
                //将锁标志存到数组中
                $this->lockNames[$name] = $expireAt;
                return true;
            }

            //返回key的剩余生存时间
            $ttl = $this->redis->ttl($lockKey);
            //key没有设置生存时间，将该锁纳为已用
            if($ttl < 0) {
                $this->redis->set($lockKey, $expireAt);
                $this->lockNames[$name] = $expireAt;
                return true;
            }

            //循环请求
            if($timeout <= 0 || $timeoutAt < microtime(true)) break;

            //休眠$sleepTime后，继续请求加锁
            usleep($sleepTime);
        }
        return false;
    }

    /**
     * 解锁
     * @params $name 锁标识
     * @return boolean
     **/
    public function unlock($name)
    {
        //判断该锁是否存在
        if($this->isLocking($name)) {
            //删除锁
            if($this->redis->del('Lock:'.$name)) {
                unset($this->lockNames[$name]);
                return true;
            }
        }
        return false;
    }

    /**
     * 释放当前所有获得的锁
     * @return boolean
     */
    public function unlockAll() {
        //此标志是用来标志是否释放所有锁成功
        $allSuccess = true;
        foreach ($this->lockedNames as $name => $expireAt) {
            if (false === $this->unlock($name)) {
                $allSuccess = false;
            }
        }
        return $allSuccess;
    }

    /**
     * 给当前锁增加指定生存时间
     * @param $name 锁标识名
     * @param expire 过期时间
     * @return boolean
     **/
    public function expire($name, $expire)
    {
        if($this->isLocking($name)) {
            $expire = max($expire, 1);
            if($this->redis->expire("Lock:{$name}", $expire))
                return true;
        }
        return false;
    }

    /**
     * 判断当前是否拥有指定名字的锁
     * @param $name 锁标识名
     * return boolean
     **/
    public function isLocking($name)
    {
        if(isset($this->lockNames[$name])) {
            return $this->redis->get("Lock:{$name}") == $this->lockNames[$name];
        }
        return false;
    }
}