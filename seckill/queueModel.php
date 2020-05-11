<?php
/**
 * 通过redis实现任务队列
 */
class queueModel
{
    private $redis;//redis服务
    private $lockModel;

    public function __construct($redis)
    {
        date_default_timezone_set('Asia/Shanghai');

        $this->redis = $redis;

        //载入分布式锁的模块
        require './lockModel.php';
        $this->lockModel = new lockModel($this->redis);
    }

    /**
     * 入队
     * @param $name 队列名称
     * @param $user_id 成员ID
     * @param $timeout 入队超时时间
     * @param $afterInterVal
     * @return boolean
    **/
    public function enqueue($name, $user_id, $timeout=10, $afterInterVal=0)
    {
        //合法性检测
        if(empty($name) || empty($user_id)) {
            return '缺少参数';
        }

        //加锁
        if(!$this->lockModel->lock("Queue:{$name}", $timeout)) {
            return '加锁失败';
        }

        //判断队列是否超过指定的集合元素,暂测试10个用户
        $count = $this->redis->zCard("Queue:{$name}");
        if($count >=  $this->redis->get("goods:{$name}:stock")) {
            $this->lockModel->unlock("Queue:$name");
            return '超过指定集合数量';
        }

        //入队时以当前时间戳作为score
        $score = microtime(true) + $afterInterVal;

        //入队
        //先判断下是否已经存在该id了
        if (false === $this->redis->zScore("Queue:$name", $user_id)) {
            $this->redis->zAdd("Queue:$name", $score, $user_id);
        }

        //解锁
        $this->lockModel->unlock("Queue:$name");

        return '入队成功';
    }

    /**
     * 出队一个Task，需要指定$user_id 和 $score
     * 如果$score 与队列中的匹配则出队，否则认为该Task已被重新入队过，当前操作按失败处理
     *
     * @param  [type]  $name    队列名称
     * @param  [type]  $user_id   成员ID
     * @param  [type]  $score   任务对应score，从队列中获取任务时会返回一个score，只有$score和队列中的值匹配时Task才会被出队
     * @param  integer $timeout 超时时间(秒)
     * @return [type]           Task是否成功，返回false可能是redis操作失败，也有可能是$score与队列中的值不匹配（这表示该Task自从获取到本地之后被其他线程入队过）
     */
    public function deQueue($name, $user_id, $score, $timeout = 10) {
        //合法性检测
        if (empty($name) || empty($user_id) || empty($score)) return false;

        //加锁
        if(!$this->lockModel->lock("Queue:{$name}", $timeout)) {
            return false;
        }

        //出队
        //先取出redis的score
        $serverScore = $this->redis->zScore("Queue:$name", $user_id);
        $result = false;
        //先判断传进来的score和redis的score是否是一样
        if ($serverScore == $score) {
            //删掉该$id
            $result = (float)$this->redis->zDelete("Queue:$name", $user_id);
            if ($result == false) {
                return false;
            }
        }
        //解锁
        $this->lockModel->unlock("Queue:$name");

        return $result;
    }

    /**
     * 获取队列顶部若干个Task 并将其出队
     * @param  [type]  $name    队列名称
     * @param  integer $count   数量
     * @param  integer $timeout 超时时间
     * @return [type]     返回数组
     */
    public function pop($name, $count = 1, $timeout = 10) {
        //合法性检测
        if (empty($name) || $count <= 0) return [];

        //加锁
        require './lockModel.php';
        $lockModel = new lockModel();
        if(!$lockModel->lock("Queue:{$name}", $timeout)) {
            return false;
        }

        //取出若干的Task
        $result = [];
        $array = $this->redis->zByScore("Queue:$name", false, microtime(true), true, false, [0, $count]);

        //将其放在$result数组里 并 删除掉redis对应的id
        foreach ($array as $id => $score) {
            $result[] = ['id' => $id, 'score' => $score];
                $this->redis->zDelete("Queue:$name", $id);
        }

        //解锁
        $lockModel->unlock("Queue:$name");

        return $count == 1 ? (empty($result) ? false : $result[0]) : $result;
    }

    /**
     * 获取队列顶部的若干个Task
     * @param  [type]  $name  队列名称
     * @param  integer $count 数量
     * @return [type]   返回数组
     */
    public function top($name, $count = 1) {
        //合法性检测
        if (empty($name) || $count < 1)  return [];

        //取出若干个Task
        $result = [];
        $array = $this->redis->getByScore("Queue:$name", false, microtime(true), true, false, [0, $count]);

        //将Task存放在数组里
        foreach ($array as $id => $score) {
            $result[] = ['id' => $id, 'score' => $score];
        }

        //返回数组
        return $count == 1 ? (empty($result) ? false : $result[0]) : $result;
    }
}