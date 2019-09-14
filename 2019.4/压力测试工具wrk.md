---
title: 压力测试工具wrk
category: linux
id: http-benchmark-tool-wrk
date: 2019-4-13 19:40:00
---

wrk是一个一个简单的 http benchmark 工具, 能做很多基本的 http 性能测试。
wrk 的一个很好的特性就是能用很少的线程压出很大的并发量。
wrk支持大多数类UNIX系统，不支持windows。

## 安装
### Mac安装
```
brew install wrk
```
注意: mac 本身连接数有限制，不要做太大的测试
	
	
### Linux 安装
```
git clone https://github.com/wg/wrk.git
cd wrk
make
```
成功以后在目录下有一个 wrk 文件

### 编译错误
可能出现`fatalerror: openssl/ssl.h: Nosuchfileor directory`错误
是因为系统中没有安装openssl的库
```
sudo apt-get install libssl-dev
# or run
sudo yum install openssl-devel
```

### 使用参数
```
使用方法: wrk <选项> <被测HTTP服务的URL>                            
  Options:                                            
    -c, --connections <N>  跟服务器建立并保持的TCP连接数量  
    -d, --duration    <T>  压测时间           
    -t, --threads     <N>  使用多少个线程进行压测   
                                                      
    -s, --script      <S>  指定Lua脚本路径       
    -H, --header      <H>  为每一个HTTP请求添加HTTP头      
        --latency          在压测结束后，打印延迟统计信息   
        --timeout     <T>  超时时间     
    -v, --version          打印正在使用的wrk的详细版本信息
                                                      
  <N>代表数字参数，支持国际单位 (1k, 1M, 1G)
  <T>代表时间参数，支持时间单位 (2s, 2m, 2h)
  ```
  
## 使用
```
wrk -t12 -c100 -d30s http://192.168.31.107
```
-t12 为模拟12个用户线程 -c100模拟100个连接
响应
```
  12 threads and 100 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency     1.25s   453.24ms   1.97s    65.31%
    Req/Sec    13.11     12.15    60.00     62.57%
  1020 requests in 30.09s, 5.04MB read
  Socket errors: connect 0, read 0, write 0, timeout 971
Requests/sec:     33.90
Transfer/sec:    171.43KB

```
### 统计分析
| 项目	|名称	|说明|
| --- | --- | ---  |
| Avg|	平均值|	每次测试的平均值|
|Stdev	|标准偏差	|结果的离散程度，越高说明越不稳定|
|Max|最大值|	最大的一次结果|
|+/- Stdev|	正负一个标准差占比|	结果的离散程度，越大越不稳定|

### 读写分析
- 总共完成请求数
- 读取数据量
- 错误统计
```
1020 requests in 30.09s, 5.04MB read
  Socket errors: connect 0, read 0, write 0, timeout 971
Requests/sec:     33.90
Transfer/sec:    171.43KB
```

示例
```
wrk -t8 -c200 -d30s --latency  "http://www.bing.com"
```
```
Running 30s test @ http://www.bing.com （压测时间30s）
  8 threads and 200 connections （共8个测试线程，200个连接）
  Thread Stats   Avg      Stdev     Max   +/- Stdev
              （平均值） （标准差）（最大值）（正负一个标准差所占比例）
    Latency    46.67ms  215.38ms   1.67s    95.59%
    （延迟）
    Req/Sec     7.91k     1.15k   10.26k    70.77%
    （处理中的请求数）
  Latency Distribution （延迟分布）
     50%    2.93ms
     75%    3.78ms
     90%    4.73ms
     99%    1.35s （99分位的延迟）
  1790465 requests in 30.01s, 684.08MB read （30.01秒内共处理完成了1790465个请求，读取了684.08MB数据）
Requests/sec:  59658.29 （平均每秒处理完成59658.29个请求）
Transfer/sec:     22.79MB （平均每秒读取数据22.79MB）
```