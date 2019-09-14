---
title: netbeans快捷键
id: 744
categories:
  - linux
date: 2015-07-07 20:23:56
tags:
---

netbeans快捷键大总结

没什么好介绍的，是netbeans的快捷键，比较全面。看到好多坛子里还在问eclipse下的这个快捷键怎么netbeans下没有呢。以前收集的，现在列在下面：

其实，在当前安装的netbeans的 帮助菜单下有快捷键列表 这个子菜单，这里有详细的针对当前版本的介绍。
这里，我觉得有个问题需要说明下：
默认下netbeans对代码的提示的快捷键是 ctrl+空格
在win下，我们知道，这个是用来切换输入法的，因此，在netbeans中需要手动调用代码完成时，会不起作用。

解决办法：有多个方法：
方法1： 我的办法：把输入法切换方式更改成 ctrl+shift+空格，毕竟输入法使用机会少，并且重装系统机会也要小。而netbeans换新版本的频率也比重装系统高。
方法2： 将netbeans的快捷键方式使用eclipse的 IDEA的 等等。如果你熟悉eclipse或IDEA的快捷键，那么这个是你的好选择。
方法3： 将冲突的改成别的可用的。 注意这行：
显示代码完成弹出式菜单 （替代快捷键） Ctrl+SPACE 其他
在首选项的 快捷键映射 选项卡里 找到这行， 在Ctrl+SPACE右边有个按钮可以编辑 重置这个快捷键功能，或者双击也可以编辑，这样就可以改成你想要的了。
在安装新版本时，如果你有旧版本存在，netbeans会提示你是否导入上一版本的设置，其中就包含你的个性化定制以及你安装的额外插件。

附：netbeans6.0快捷键列表，具体当前版本请参考IDE的帮助菜单。
查找、搜索和替换
Ctrl-F3 搜索位于插入点的词
F3/Shift-F3 在文件中查找下一个/上一个
Ctrl-F/H 在文件中查找/替换
Alt-F7 查找使用实例
Ctrl-Shift-P 在项目中查找
Alt-Shift-U 查找使用实例结果
Alt-Shift-H 关闭搜索结果突出显示
Alt-Shift-L 跳转列表中的下一个（所有文件）
Alt-Shift-K 跳转列表中的上一个（所有文件）
Ctrl-R 重新装入窗体
Alt-U-U 将选定内容转换为大写
Alt-U-L 将选定内容转换为小写
Alt-U-R 对选定内容切换大小写
在源代码中导航
Alt-Shift-O 转至类
Alt-Shift-E 转至 JUnit 测试
Alt-O 转至源代码
Alt-G 转至声明
Ctrl-B 转至超级实现
Alt-K/Alt-L 后退/前进
Ctrl-G 转至行
Ctrl-F2 切换添加/删除书签
F2/Shift-F2 下一个/上一个书签
F12/Shift-F12 下一个/上一个使用实例/编译错误
Ctrl-Shift-1/2/3 在“项目”/“文件”/“收藏夹”中选择
Ctrl-[ 将插入记号移至匹配的方括号
Ctrl-^ Ctrl-[（法语/比利时语键盘）
用Java编码
Ctrl-I 覆盖方法
Alt-Shift-F/I 修复全部/选定类的导
Alt-Shift-W 以 try-catch 块围绕
Ctrl-Shift-F 重新设置选定内容的
Ctrl-D/Ctrl-T 左移/右移一个制表符
Ctrl-Shift-T/D 添加/撤消注释行 ("//
Ctrl-L/K 插入下一个/上一个匹
Esc/Ctrl-空格键 关闭/打开代码完成
Ctrl-M 选择下一个参数
Shift-空格键 输入空格，不展开缩写
Alt-F1/Shift-F1 显示/搜索 Javadoc
Ctrl-Shift-M 提取方法
Alt-U-G 将 “get” 放置到标识符前面
Alt-U-S 将 “set” 放置到标识符前面
Alt-U-I 将 “is” 放置到标识符前面
Ctrl-Backspace/Del 删除上一个/当前词
Ctrl-E 删除当前行
Ctrl-J-S/E 开始/结束录制宏
Ctrl-Shift-J 插入国际化字符串
Ctrl-数字键盘上的 - 折叠（隐藏）代码块
Ctrl-数字键盘上的 + 展开已折叠的代码块
Ctrl-Shift-数字键盘上的 - 折叠所有代码块
Ctrl-Shift-数字键盘上的 + 展开所有代码块
Alt-Enter 显示建议/提示
打开和切换视图
Ctrl-Shift-0 显示“搜索结果”窗口
Ctrl-0 显示源代码编辑器
Ctrl-1 显示“项目”窗口
Ctrl-2 显示“文件”窗口
Ctrl-3 显示“收藏夹”窗口
Ctrl-4 显示“输出”窗口
Ctrl-5 显示“运行环境”窗口
Ctrl-6 显示“待做事项”窗口
Ctrl-7 显示“导航”窗口
Ctrl-Shift-7 显示“属性”对话框
Ctrl-Shift-8 显示组件面板
Ctrl-8 显示“版本控制”窗口
Ctrl-9 显示“VCS 输出”窗口
Shift-F4 显示“文档”对话框
Alt-向左方向键 移动到左侧窗口
Alt-向右方向键 移动到右侧窗口
Ctrl-Tab (Ctrl-`) 在打开的文档之间切换
Shift-Escape 最大化窗口（切换）
Ctrl-F4/Ctrl-W 关闭当前选定的窗口
Ctrl-Shift-F4 关闭所有窗口
Shift-F10 打开上下文菜单
编译、测试和运行
F9 编译选定的包或文件
F11 生成主项目
Shift-F11 清理并生成主项目
Ctrl-Q 设置请求参数
Ctrl-Shift-U 创建 JUnit 测试
Ctrl-F6/Alt-F6 为文件/项目运行JUnit测试
F6/Shift-F6 运行主项目/文件
调试
F5 开始调试主项目
Ctrl-Shift-F5 开始调试当前文件
Ctrl-Shift-F6 开始为文件调试测试 (JU
Shift-F5/Ctrl-F5 停止/继续调试会话
F4 运行到文件中的光标位置
F7/F8 步入/越过
Ctrl-F7 步出
Ctrl-Alt-向上方向键 转至被调用的方法
Ctrl-Alt-向下方向键 转至调用方法
Ctrl-F9 计算表达式的值
Ctrl-F8 切换断点
Ctrl-Shift-F8 新建断点
Ctrl-Shift-F7 新建监视
Ctrl-Shift-5 显示 HTTP 监视器
Ctrl-Shift-0 显示“搜索结果”窗口
Alt-Shift-1 显示“局部变量”窗口
Alt-Shift-2 显示“监视”窗口
Alt-Shift-3 显示“调用栈”窗口
Alt-Shift-4 显示“类”窗口
Alt-Shift-5 显示“断点”窗口
Alt-Shift-6 显示“会话”窗口
Ctrl-Shift-6 切换到“执行”窗口
Alt-Shift-7 切换到“线程”窗口
Alt-Shift-8 切换到“源”窗口

&nbsp;