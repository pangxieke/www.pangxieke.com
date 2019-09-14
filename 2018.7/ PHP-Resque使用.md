---
title: PHP-Resque使用
date: 2018.7.23 19:30:00
id: php-resque
category: php
---

PHP-Resque是现成的框架, 十分方便实现消息队列。

## php-resque的设计
### 三种角色
在Resque中，一个后台任务被抽象为由三种角色共同完成：

Job | 任务 ： 一个Job就是一个需要在后台完成的任务，比如本文举例的发送邮件，就可以抽象为一个Job。在Resque中一个Job就是一个Class。

Queue | 队列 ： 也就是上文的消息队列，在Resque中，队列则是由Redis实现的。Resque还提供了一个简单的队列管理器，可以实现将Job插入/取出队列等功能。

Worker | 执行者 ： 负责从队列中取出Job并执行，可以以守护进程的方式运行在后台。

那么基于这个划分，一个后台任务在Resque下的基本流程是这样的：
在Resque中，有一个很重要的设计：一个Worker，可以处理一个队列，也可以处理很多个队列，并且可以通过增加Worker的进程/线程数来加快队列的执行速度。

### 流程
如下：

将一个后台任务编写为一个独立的Class，这个Class就是一个Job。
在需要使用后台程序的地方，系统将Job Class的名称以及所需参数放入队列。
以命令行方式开启一个Worker，并通过参数指定Worker所需要处理的队列。
Worker作为守护进程运行，并且定时检查队列。
当队列中有Job时，Worker取出Job并运行，即实例化Job Class并执行Class中的方法。

## 环境依赖
1. 需要Redis server
2. 需要php支持PCNTL函数

## 使用
官方demo中已经有比较完整的例子了。这里我们仿照实现一个简单的功能。

使用composer引用项目
```
composer require chrisboulton/php-resque
```

`Queue.php`
```
<?php
require_once './vendor/autoload.php';

class Queue{
    public function send($queueName = 'default', $jobName, $args){

        Resque::setBackend('127.0.0.1:6379');

        $jobId = Resque::enqueue($queueName, $jobName, $args, true);

        echo "Queued job ".$jobId."\n\n";
    }
}

date_default_timezone_set('Asia/Shanghai');

$args = array(
    'time' => time(),
    'user_id' => 1,
    'message' => 'this is message',
);

$queue = new Queue();
$queue->send('default', 'job', $args);

```

### Job
Worker抛出队列服务的时候，会自动根据服务的名称去执行这个类。
注意需要在worker里面自动加载这个类哦。

`Job.php`
```
<?php
class Job{
    public function perform(){
        $userId = $this->args['user_id'];
        $message = $this->args['message'];
        $time = $this->args['time'];

        fwrite(STDOUT, date('Y-m-d H:i:s', $time));
        fwrite(STDOUT, $userId);
        fwrite(STDOUT, $message);
    }
}
```

### Resque
Worker常驻内存程序`Resque.php`
```
#!/usr/bin/env php
<?php
require_once './vendor/autoload.php';
require_once 'Job.php';

date_default_timezone_set('Asia/Shanghai');

$QUEUE = getenv('QUEUE');
if(empty($QUEUE)) {
    die("Set QUEUE env var containing the list of queues to work.\n");
}

Resque::setBackend('localhost:6379');

$queues = explode(',', $QUEUE);
$worker = new Resque_Worker($queues);


$interval = 5;

$worker->work($interval);
```

## 测试
终端1
```
QUEUE=* php Resque.php &
```
可以`ps -ef|grep Resque` 查看进程是否开启成功
如果想让进程常驻，可以
```
nohup -- QUEUE=* nohup php Resque.php &
```

终端2
```
php Queue.php
```
返回`Queued job 9d1c57a1dd04f53d770c206c1030f194`

此时终端1返回
```
2018-07-23 17:48:421this is message
```

可以通过redis-cli，查看key
```
resis-cli
keys *
```
获取key
```
"resque:job:52f5abf5344094efc417e7ea8f1aa083:status"
"resque:workers"
"resque:queues"
```
可以查看类型
```
type resque:workers
```
