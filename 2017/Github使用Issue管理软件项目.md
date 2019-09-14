---
title: Github使用Issue管理软件项目
date: 2017.9.11
id: 1324
category: php
tags: 
	- issue
---

原文http://www.ruanyifeng.com/blog/2017/08/issue.html

### 一、Issue是什么？
Issue是指一项待完成的工作。通常可以访问为“问题”、“事物”
例如
- 软件的bug
- 一项功能建议
- 待完成的任务

每个Issue应该包含所有历史信息，方便后来人查看，了解整个历史过程。

Issue起源于客户部门。用户反馈问题后，客服部门创建一个工单。以后每次与该用户的交流，都需要更新工单，记录全部信息。这样其他的客户人员接待此客户时，知道以前给该客户做过什么，该继续做什么。

[![客服](/images/2017/09/bg2017082418.png)](/images/2017/09/bg2017082418.png)

### 二、Issue管理系统
Issue管理系统，需要具有这些功能
#### 项目管理
- Issue优先级
- Issue阶段
- Issue处理人员
- 日程安排
- 统计、监控

#### 团队合作
- 讨论
- 通知

### 三、Github Issues

#### 3.1 基本用法
每个Github仓库，都有一个Issues面板

[![](/images/2017/09/bg2017082405.png)](/images/2017/09/bg2017082405.png)
进入面板后，可以创建新Issue

[![](/images/2017/09/bg2017082427.png)](/images/2017/09/bg2017082427.png)
创建Issue时，左侧填入 Issue 的标题和内容，右侧是四个配置项
- Assignees：人员
- Labels：标签
- Projects：项目
- Milestone：里程碑

#### 3.2 Assignee
Assignee 选择框用于从当前仓库的所有成员之中，指派某个 Issue 的处理人员。

[![](/images/2017/09/bg2017082406.png)](/images/2017/09/bg2017082406.png)

#### 3.3 Labels
Issue 可以贴上标签，这样有利于分类管理和过滤查看
对于大型项目， 每个 Issue 至少应该有两个 Label ，一个表示性质，另一个表示优先级。

[![](/images/2017/09/bg2017082402.png)](/images/2017/09/bg2017082402.png)

#### 3.4 Milestone
Milestone 叫做"里程碑"，用作 Issue 的容器，相关 Issue 可以放在一个 Milestone 里面。常见的例子是不同的版本（version）和迭代（sprint），都可以做成 Milestone。

新建 Milestone，要在 Issues 面板的首页，点击 Milestones 按钮

[![](/images/2017/09/bg2017082410.png)](/images/2017/09/bg2017082410.png)

可以指定到期时间

[![](/images/2017/09/bg2017082429.png)](/images/2017/09/bg2017082429.png)
#### 3.5 全局视图
全局视图，让用户查看和操作所有与自己相关的 Issue
[![](/images/2017/09/bg2017082430.png)](/images/2017/09/bg2017082430.png)

### 四、看板
看板（kanban）是敏捷开发的重要手段，主要用于项目的进度管理。所有需要完成的任务，都做成卡片，贴在一块白板上面，这就是看板。

[![](/images/2017/09/bg2017082411.png)](/images/2017/09/bg2017082411.png)

#### 4.2 Github 的看板功能
首先，在仓库首页进入 Projects 面板
然后，点击 New Project 按钮，新建一个 Project
[![](/images/2017/09/bg2017082415.png)](/images/2017/09/bg2017082415.png)
接着，点击 Add column 按钮，为该项目新建若干列

[![](/images/2017/09/bg2017082416.png)](/images/2017/09/bg2017082416.png)

最后，将 Issue 分配到对应的列，就新建成功了一个看板视图。

[![](/images/2017/09/20170907205357.png)](/images/2017/09/20170907205357.png)

Issue 可以从一列拖到另一列，表示从一个阶段进入另一个阶段。
许多第三方工具可以增强 Github 的看板功能，最著名的是 Zenhub

[![](/images/2017/09/bg2017082417.png)](/images/2017/09/bg2017082417.png)
