---
title: VMware虚拟机错误提示：Intel VT-x 处于禁用状态
id: 642
categories:
  - linux
date: 2015-04-11 13:57:52
tags:
---

今天打算使用虚拟机，体验一下linux ，虚拟机使用的是VMware Workstation 10.0 ，并且首次在虚拟机体验centos64 位系统。

在新建好虚拟机，运行时候就出现了VMware Workstation 的提醒：此主机支持 Intel VT-x，但 Intel VT-x 处于禁用状态。
如图：[![虚拟机错误](/images/2015/04/虚拟机错误.png)](/images/2015/04/虚拟机错误.png)

图片原文如下：

```php
    已将该虚拟机配置为使用 64 位客户机操作系统。但是，无法执行 64 位操作。

    此主机支持 Intel VT-x，但 Intel VT-x 处于禁用状态。

    如果已在 BIOS/固件设置中禁用 Intel VT-x，或主机自更改此设置后从未重新启动，则 Intel VT-x 可能被禁用。

    (1) 确认 BIOS/固件设置中启用了 Intel VT-x 并禁用了“可信执行”。

    (2) 如果这两项 BIOS/固件设置有一项已更改，请重新启动主机。

    (3) 如果您在安装 VMware Workstation 之后从未重新启动主机，请重新启动。

    (4) 将主机的 BIOS/固件更新至最新版本。

    有关更多详细信息，请参见 http://vmware.com/info?id=152。
```

提醒信息已经说的很清楚了，要在 BIOS 中开启Intel VT-x（应该是英特尔虚拟化技术）。按照提示信息， 在自己电脑上的解决方法：

1、关机，开机，笔者使用联想小Y，按住F2，进入 BIOS 设置页面；

2、选择 configuration ，再选择intel virtual technology ，此时该选项应该是disabled（关闭）的；

3、将disabled（关闭）改为 enabled（开启）；

4、保存设置，重启即可。

[![6863954852218361267](/images/2015/04/6863954852218361267.jpg)](/images/2015/04/6863954852218361267.jpg)