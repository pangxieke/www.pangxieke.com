---
title: GO序列化和反序列化研究
id: go-serialize
category: go
date: 2019.8.9 20:00:00
---

## 序列化和反序列化的概念

把对象转换为字节序列的过程称为对象的序列化**；**把字节序列恢复为对象的过程称为对象的反序列化。 

对象的序列化主要有两种用途：

1） 把对象的字节序列永久地保存到硬盘上，通常存放在一个文件中；

2） 在网络上传送对象的字节序列。

在很多应用中，需要对某些对象进行序列化，让它们离开内存空间，入住物理硬盘，以便长期保存。比如最常见的是Web服务器中的Session对象，当有 10万用户并发访问，就有可能出现10万个Session对象，内存可能吃不消，于是Web容器就会把一些seesion先序列化到硬盘中，等要用了，再把保存在硬盘中的对象还原到内存中。

当两个进程在进行远程通信时，彼此可以发送各种类型的数据。无论是何种类型的数据，都会以二进制序列的形式在网络上传送

## Json序列化

### Struct 序列化为Json

```
type Student struct {
	Name    string
	Age     uint8
	Address string
}
```

```
s1 := Student{"张三", 18, "江苏省"}
	//开始json序列化
	data, err := json.Marshal(s1)
	if err != nil {
		fmt.Printf("json.marshal failed,err:", err)
		return
	}
	fmt.Printf("%s\n", string(data))
	
	//{"Name":"张三","Age":18,"Address":"江苏省"}
```

### Map序列化为Json

```
	var s2 = make(map[string]interface{})
	s2["Name"] = "李四"
	s2["Age"] = 0
	data, _ = json.Marshal(s2)
	fmt.Printf("%s \n", data)
	
	//{"Age":0,"Name":"李四"} 
```

## Json反序列化

### 反序列化为Struct

```
	var s3 string = `{"Name":"张三", "Age":0, "Address":"江苏省"}`
	var stu Student
	err = json.Unmarshal([]byte(s3), &stu)
	if err != nil {
		fmt.Printf("json.Unmarshal failed,err:", err)
		return
	}
	fmt.Printf("%+v \n", stu)
	
	//{Name:张三 Age:0 Address:江苏省} 
```

### 反序列化为Map

```
	//解析为map
	var stuMap = make(map[string]interface{})
	json.Unmarshal([]byte(s3), &stuMap)

	fmt.Printf("%+v \n", stuMap)
	
	//map[Address:江苏省 Age:0 Name:张三] 
```

反序列化为Map有一个好处是，可以区分字段是否存在，还是字段为0，反序列化为struct时，字段不存在，默认为0或"", 无法区分。

有时，我们做接口开发时，需要判断前端是否传递了该字段，就需要用到这种方式。

```
	//0值与字段不存在区分
	if _, ok := stuMap["Age"]; ok {
		fmt.Println("Age is ", stuMap["Age"])
	}else{
		fmt.Println("Field of Age is not exist")
	}
```

## Protobuff序列化与反序列化

proto.go文件

```
type Body struct {
	Weight float32 `protobuf:"fixed32,1,opt,name=weight,proto3" json:"weight,omitempty"`
	Height float32 `protobuf:"fixed32,2,opt,name=height,json=height,proto3" json:"height,omitempty"`
}

func (m *Body) Reset()         { *m = Body{} }
func (m *Body) String() string { return proto.CompactTextString(m) }
func (*Body) ProtoMessage()    {}
```

### 序列化与反序列

```
	var body = Body{Weight: 60, Height: 170}
	data, err := proto.Marshal(&body)
	if err != nil {
		fmt.Println("proto.Marshal err=%s", err)
	}
	fmt.Println(data)

	var b Body
	proto.Unmarshal(data, &b)
	fmt.Println(&b)
```

输出

```
[13 0 0 112 66 21 0 0 42 67]
weight:60 height:170 
```

## Binary

### Binary 序列化 

```
	s1 := Student{"张三", 18, "江苏省"}
	buf := new(bytes.Buffer)
	if err := binary.Write(buf, binary.LittleEndian, s1); err != nil {
		fmt.Println("binary.Write err=%s", err)
	}
	fmt.Println(buf)
```

返回错误

```
invalid type main.Student
```

原因:**如果字段中有不确定大小的类型，如 int，slice，string 等，则会报错**

解决办法：

- int 换成 int32 等固定大小的类型
- slice 换成类似 [8]byte 这种固定大小
- 选择其他序列化方式

正确写法

```
  type StudentScore struct{
    Math int32
    English int32
  }

	s1 := StudentScore{80, 90}
	buf := new(bytes.Buffer)
	if err := binary.Write(buf, binary.LittleEndian, s1); err != nil {
		fmt.Println("binary.Write err=%s", err)
	}
	fmt.Println(buf)
	
	//PZ
```

### Binary反序列化

```
	//反序列化
	var s2 StudentScore
	if err := binary.Read(buf, binary.LittleEndian, &s2); err != nil {
		fmt.Println("binary.Read err=%s", err)
	}
	fmt.Printf("%+v", s2)
	
  //{Math:80 English:90}
```

## Gob

针对 binary 不能直接使用 string 和 slice 问题，可以使用 gob

### Gob序列化

```
	s1 := Student{"张三", 18, "江苏省"}

	//序列化
	var buffer bytes.Buffer
	encoder := gob.NewEncoder(&buffer) //创建编码器
	err1 := encoder.Encode(&s1)        //编码
	if err1 != nil {
		log.Panic(err1)
	}
	fmt.Printf("序列化后：%s", buffer)
```

返回

```
{2��Student��Name
                           AgeAddress
                                     ��张三     江苏省 %!s(int=0) %!s(bytes.readOp=0)}反序列化后：{Name:张三 Age:18 Address:江苏省} 
```
### Gob反序列化
```
	//反序列化
	byteEn := buffer.Bytes()

	decoder := gob.NewDecoder(bytes.NewReader(byteEn)) //创建解密器
	var s2 Student
	err2 := decoder.Decode(&s2) //解密
	if err2 != nil {
		log.Panic(err2)
	}
	fmt.Printf("反序列化后：%+v \n", s2)
	
	//{Name:张三 Age:18 Address:江苏省} 
```

## 比较

方式	优点	缺点
binary	性能高	不支持不确定大小类型 int、slice、string
gob	支持多种类型	性能低
json	支持多种类型	性能低于 binary 和 protobuf

| 方式     | 优点                 | 缺点                                                   |
| -------- | -------------------- | ------------------------------------------------------ |                                      |
| binary   | 性能高               | 不支持不确定大小类型 int、slice、string                |                                              |
| gob      | 支持多种类型         | 性能低                                                 |                                                |
| json     | 支持多种类型         | 性能低于 binary 和 protobuf                            |                                           |
| protobuf | 支持多种类型，性能高 | 需要单独存放结构，如果结构变动需要重新生成 .pb.go 文件 |

​	