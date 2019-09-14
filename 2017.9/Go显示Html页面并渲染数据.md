---
title: Go实现HTML页面及数据渲染
date: 2017.10.11 18:20:00
categories: go
id: 1342
description: 学习使用Go实现Html页面展示，同时渲染变量数据
---

学习使用Go实现Html页面展示，同时渲染变量数据

go代码
```
package main

import (
    "fmt"
    "html/template"
    "log"
    "net/http"
    "os"
    "path/filepath"
)

type Package struct{
    Name    string
    NumFuncs int
    NumVars int
}

func main() {
    wd, err := os.Getwd()
    if err != nil {
        log.Fatalf("Getwd: %v", err)
    }
    log.Print("Word directory:", wd)

    http.HandleFunc("/", func(w http.ResponseWriter, r * http.Request){
        pkg := &Package{
            Name: "go-web",
            NumFuncs:12,
            NumVars:1200,
        }

        tmpl, err := template.New("main_v1.tmpl").Funcs(template.FuncMap{
            "NumFuncs": func() int {
                return pkg.NumFuncs
            },
            "Str2html": func(str string) template.HTML {
                return template.HTML(str)
            },
            "Divide": func(num int) int {
                return num/2
            },
            "Add": func(num int) int{
                return num + 100
            },
        }).ParseFiles(filepath.Join(wd, "main_v1.tmpl"))

        if err != nil {
            fmt.Fprintf(w, "ParseFiles: %v", err)
            return
        }

        err = tmpl.Execute(w, map[string]interface{}{
            "Name": pkg.Name,
            "NumFuncs": pkg.NumFuncs,
            "NumVars": pkg.NumVars,
            "NumVarsHTML": `<li>Number of functions:1200</li>`,
            "Maps": map[string]map[string]string{
                "Level1": map[string]string{
                    "Name": "go-web",
                },
            },
            "Nums": []int{1, 2, 3, 4, 5, 6, 7},
        })
        if err != nil {
            fmt.Fprintf(w, "Execute: %v", err)
            return
        }
    })

    log.Print("Starting server ...")
    log.Fatal(http.ListenAndServe(":5000", nil))
}
```
模板`main_v1.tmpl`
```
<html>
    <head>
        <title>Go Web</title>
    </head>
    <body>
        <p>Package Info:</p>
        <ul>
            <li>Package name: {{.Name}}</li>
            <li>Number of functions: {{.NumFuncs}}/{{NumFuncs}}</li>
            {{Str2html .NumVarsHTML}}
        </ul>

        <p>Logic Comparison:</p>
        <ul>
            <li>and: {{and true true}}</li>
            <li>and: {{and true false}}</li>
            <li>or: {{or true true}}</li>
            <li>or: {{or true false}}</li>
            <li>or: {{or false false}}</li>
            <li>not: {{not true}}</li>
            <li>not: {{not false}}</li>
            <li>and + or: {{and (and true true) (or true false)}}</li>
            <li>and + not: {{and (and true true) (not false)}}</li>

            <li>NumFuncs > 10: {{gt .NumFuncs 10}}</li>
            <li>NumFuncs > 10 and NumVars > 1000: {{and (gt .NumFuncs 10) (gt .NumVars 1000)}}</li>
            <li>NumFuncs > 10 and NumVars < 1000: {{and (gt .NumFuncs 10) (lt .NumVars 1000)}}</li>
            <li>{{if and (gt .NumFuncs 10) (gt .NumVars 1000)}}NumFuncs > 10 and NumVars > 1000{{end}}</li>
            <li>{{if not (and (gt .NumFuncs 10) (gt .NumVars 1000))}}NumFuncs > 10 and NumVars > 1000{{end}}</li>
        </ul>

        <p>Pipelines:</p>
        <ul>
            <li>{{.NumVars | Divide | Divide | Add | Divide}}</li>
        </ul>

        <p>Template Vairbale:</p>
        <ul>
            <li>{{ $result := .NumVars | Divide | Divide}}</li>
            <li>{{ $result | Add | Divide}}</li>
        </ul>

        <p>Index:</p>
        <ul>
            <li>{{index . "NumFuncs"}}</li>
            <li>{{index . "NumVars"}}</li>
            <li>{{index .Maps "Level1" "Name"}}</li>
            <li>{{index . "Maps" "Level1" "Name"}}</li>
            <li>{{index .Nums 1}}</li>
            <li>{{index . "Nums" 1}}</li>
        </ul>
    </body>
</html>
```