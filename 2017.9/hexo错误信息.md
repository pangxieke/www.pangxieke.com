---
title: Hexo错误 ERROR Deployer not found
date: 2017.9.18
id: 1335
category: share
---
把本地的hexo升级了新的版本。
 现在版本 3.3.9
 
![](/images/2017/09/hexo-version.png )
### 错误ERROR Deployer not found: git
执行
`hexo deploy`发布时，出现错误
```
 ERROR Deployer not found: git
 ```
 
 ### 解决
 此时需要
 ```
 npm install hexo-deployer-git –save
 ```
 ![](/images/2017/09/hexo-deployer.png)
 此时如果`npm ERR! addLocal Could not instal`
 
 使用cnpm
 ```
 npm install -g cnpm
 ```
 
 ### 无hexo server 
 hexo下无`hexo server` `hexo s`命令
 此时需要
 ```
  npm install hexo-server
```
 
 ### hexo generate 无文章生成
 ```
 $ hexo generate
(node:21460) [DEP0061] DeprecationWarning: fs.SyncWriteStream is deprecated.
INFO  Start processing
INFO  Files loaded in 772 ms
INFO  0 files generated in 21 ms
```
![](/images/2017/09/3445035219-57b1e0e50885d_articlex.png)
未找到原因，只能重新安装hexo

