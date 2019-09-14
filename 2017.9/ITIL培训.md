---
title: ITIL基础知识
id: 1334
categories: share
date: 2017.9.16
---

### 介绍
ITIL被誉为全球真正的IT服务管理最佳时间标准。ITIL是一套帮助企业对IT系统的规划、研发、实施和运营进行有效管理的方法，是一套方法论。
ITIL（IT Infrastructure Library，IT基础架构标准库），ITIL是CCTA（英国国家电脑局）于1980年开发的一套IT服务管理标准库。它把英国在IT管理方面的方法归纳起来，变成规范，为企业的IT部门提供一套从计划、研发、实施到运维的标准方法。

![](/images/2017/09/ITIL.jpg)

### 前言Foreword

以前的系统都是谁需求，谁建设。这个各个部门会建设自己的系统，形成信息孤岛，无法共享数据。
现阶段，需求由业务部门和IT共建。业务部门作为使用部门，使用信息化系统，IT作为服务，提供service。

### 什么是IT服务：接点
Service ！== Application
服务是通过促进客户想要实现的成果而不需要付出特别的成本和风险。服务是一种为客户提供价值的手段

IT service，IT服务
IT服务，由IT服务提供商提供的服务。IT服务由信息技术，个人

### 价值组成
Framing thie value of service
从客户的角度，价值由2个因素组成：**效用**+**保障**
保障是价值的正反2面，关系到价值的体现。

### ITSM与ITIL
百度百科关于ITSM定义：
[IT服务管理（ITSM）是一套帮助企业对IT系统的规划、研发、实施和运营进行有效管理的方法，是一套方法论。ITSM起源于ITIL（IT Infrastructure Library，IT基础架构标准库），ITIL是CCTA（英国国家电脑局）于1980年开发的一套IT服务管理标准库。它把英国在IT管理方面的方法归纳起来，变成规范，为企业的IT部门提供一套从计划、研发、实施到运维的标准方法。](https://baike.baidu.com/item/it%E6%9C%8D%E5%8A%A1%E7%AE%A1%E7%90%86/1380337?fr=aladdin&fromid=8516993&fromtitle=ITSM)

### 公共领域的良好实践
分3个版本
- Version1 1989-1999
- Version2 1999-2006
- Version3 2007

### ITIL V2
- 服务级别管理
- 突发事件管理
- 配置管理

![enter description here][1]

### ITIL V3
ITIL最新版本是V3.0，它包含5个生命周期：
- 战略阶段（Service Strategy)；
- 设计阶段（Service Design)；
- 转换阶段（Service Transition)；
- 运营阶段（Service Operation)；
- 改进阶段（Service Improvement)；

![enter description here][2]
IT服务管理体系，不是独立的，散乱的，应急的制度

![enter description here][3]

#### 持续改进
持续改进是所有其他生命周期的目的地
数据是改进的依据

### V2 和 V3 的关系

![enter description here][4]
ITIL3.0可以理解为ITIL2.0的继承和发展，特别是在学术层面上。我们认为可以从三个方面理解二者的不同。

  第一，在3.0版本里面引入了生命周期的概念。它通过PDCA模型，可以不断地循环改进，从而保持ITIL的生命活力。而ITIL2.0的核心是服务支持和服务交付，模块之间彼此相对孤立，没有太多联系，主要关注于业务流程实现。ITIL3.0通过服务战略、服务设计、服务转换、服务运营、服务持续改进等先后顺序来实施，IT服务的实施过程被有机整合为一个良性循环的整体。

  第二，ITIL3.0提供了丰富的管理方法和概念。在ITIL3.0中引入了很多行业的管理实施方案，借助这些丰富的资源，用户可以很方便地在企业中实施IT服务管理。ITIL3.0也提出并借鉴了很多管理学概念（例如项目管理、质量管理、运作管理、CMI等），但这也是很多企业面对ITIL3.0时不知所措、举步维艰的重要原因所在。对于刚开始起步进行IT治理的企业来说，会有一定难度，不如ITIL2.0清晰。

  第三,在ITIL3.0里面加入了和业界其他标准的接口。如软件开发标准CMMI、目前非常热门的COBIT（IT治理控制框架）和PMP项目管理方法等。但ITIL和这些标准之间可能会存在一些重叠，包括在实施相关项目的时候互有交*，所以在实施的过程中，企业要注意和业界标准的兼容和整合，如哪些条目需要保留，哪些可以整合，以及这些接口该怎样整合等。

### ITIL的理念
ITIL的理念：良好的实践结构和自我优化

### 资源和能力
Basic Concepts:Assets, resources and capabilities
There are tow types of asset used by both service providers and customers - resources and capabilities.

Basic concepts processes
管理过程固化了经验，管理过程有数据
数据的价值在于关联

Basic Concepts：knowledge management and the SKMS
知识和信息使人们能够执行流程活动

服务生命周期全过程

## ITIL持续服务改进 CSI
CSI，Continual Service IMprovement
从战略到设计，通过转换，然后进入生产运营。

CSI的一个首要目标就是从经验中学习，并应用这些学习来持续提高IT服务质量和优化成本。
CSI的目的是，识别业务需求的变化，并不断的调整信息化服务，以此，促使信息化服务与持续演变的业务相一致。

目标，关键成功因素，量化

当有人指出了问题，才进行改进，这不是CSI的正确做法

### 策略 Policy

- 必须定义和实施监测
- 坚持提供趋势报告
- 坚持进行服务回顾
- 信息化服务，必须有明确的级别和目标
- 信息化服务的管理过程，必须有关键成功因素和关键绩效指标
- 按计划的日期，每个月或每个季度进行回顾、报告、分析互动，而不是临时性的
- 大多数的组织是每个月一次
- 如果正在引入新的服务，建议比一个月更早的 监视、报告、审阅
- 可能需要每天复查新服务，作为早起生命支持的一部分，在一段时间内，在更改为每周和最后每月审批之前

### 知识管理Knowledge Management
- CSL知识管理是充分说明在ITIL服务过渡，但它在CSI中起到关键的作用
- 在每个服务生命周期阶段，数据应该被捕获，以便知识获得和正在发生的事情的理解，从而形成智慧
- 一个组织经常会捕获适当的数据，但不能把数据处理成信息，把信息合成为知识，然后将这些知识与其他知识结合以带来智慧
- 智慧会带来更好的决策。这一点即适用于IT服务，也适用于IT过程


### 持续改进 Continual service improvement processes
- Plan
	- Identify the strategy for improvement
	- Define what you will measure
- Do
	- Gather the data
	- process the data
- Check
	- Analyse the information and data
	- Present and use the information
- Act
	- Implement improvement

分析，使用数据才是关键

### 评估Assessments
- 评估是一个正式的机制，对流程效率的分析是为了识别潜在的缺点
- 评估用来回到“我们的现状”，了解现状以及与期望的差距，这是持续改进的开始
- 讨论评估时，需要研究业务过程、IT服务、IT系统和组件的关系

#### 案例 北大附院
 1. 信息系统的容量波动趋势
 2. 重点问题的核实和回顾
 3. 服务请求的统计分析
 4. 月度变更评审
 5. 月度变更评估

### 服务管理Service Measurement
测量服务是针对单个组件的性能进行测量已经不够，需要测量终端到终端的服务

### 变更管理 Change Manager
在战略上很久以后发生的事，现在就要思考

### 服务组合管理Service Protfolio Management
- **中心** 以客户为中心
- **跟踪** 跟踪投资在整个生命周期的服务，并与其他服务管理流程，以确保互动适合的回报。
- 确保服务被明确定义
- 服务组合管理的范围是服务提供者计划准备提供的所有服务，当前交付的和已退出的服务。

服务管道提供了未来可能业务的业务视图，是通常不向客户发布的服务组合的一部分

![][5]
- 客户组合
- 应用组合
- 供应商和合同管理信息系统
-客户协议组合
- 项目组合
- 配置管理数据库
*CMDB --Configuration Management Database 配置管理数据库
CMDB存储与管理企业IT架构中设备的各种配置信息，它与所有服务支持和服务交付流程都紧密相联，支持这些流程的运转、发挥配置信息的价值，同时依赖于相关流程保证数据的准确性。*
[百度百科 CMDB](https://baike.baidu.com/item/CMDB/5403317?fr=aladdin)

### 过程启动Process Initiation
务关系管理过程是对客户和用户的直接接口, 它很可能会收到许多不同类型的请求。这些范围从要求提供服务信息到培训请求、新服务请求以及对现有服务的更改。

![][6]

#### 定义
现有服务的步骤与新服务不同, 因此确定输入是否与新的或现有的服务有关是很重要的。

#### 分析
**价值分析** +**可行性分析**
一旦阐述了价值主张, 并记录了业务案例, 服务组合管理就可以与客户和业务主管一起决定服务是否可行。是否做此项目。

#### 变更建议

### 章程 charter	

  [1]: /images/2017/09/itil_v2.gif "v2"
  [2]: /images/2017/09/v3-5load_1.jpg "5个阶段"
  [3]: /images/2017/09/timg.jpg "生命周期 "
  [4]: /images/2017/09/itil_v3.gif "v3模块"
  [5]: /images/2017/09/1505549780082.jpg
  [6]: /images//2017/09/1505550805969.jpg