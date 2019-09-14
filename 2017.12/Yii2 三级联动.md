---
title: Yii2 三级联动
date: 2017.12.14 18:00:00
id: yii2-select-widget-kartik
category: php
tags: yii2
---

项目开发中，我们经常会遇到3级联动选择框。
在yii2项目开发中，可以利用 "kartik-v/yii2-widgets" 包，快速实现下拉选择 三级联动功能，十分方便。
[官方demo](http://demos.krajee.com/widget-details/depdrop)

## 安装包
```
composer require  "kartik-v/yii2-widgets"
```

## views
在views中，修改`_form.php`,
```
use kartik\widgets\DepDrop;
 
// Parent 
echo $form->field($model, 'cat')->dropDownList($catList, ['id'=>'cat-id']);
 
// Child # 1
echo $form->field($model, 'subcat')->widget(DepDrop::classname(), [
    'options'=>['id'=>'subcat-id'],
    'pluginOptions'=>[
        'depends'=>['cat-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/site/subcat'])
    ]
]);
 
// Child # 2
echo $form->field($model, 'prod')->widget(DepDrop::classname(), [
    'pluginOptions'=>[
        'depends'=>['cat-id', 'subcat-id'],
        'placeholder'=>'Select...',
        'url'=>Url::to(['/site/prod'])
    ]
]);
```
通过 depends关联

## controllers
```
public function actionSubcat() {
    $out = [];
    if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        if ($parents != null) {
            $cat_id = $parents[0];
            $out = self::getSubCatList($cat_id); 
            // the getSubCatList function will query the database based on the
            // cat_id and return an array like below:
            // [
            //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
            //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
            // ]
            echo Json::encode(['output'=>$out, 'selected'=>'']);
            return;
        }
    }
    echo Json::encode(['output'=>'', 'selected'=>'']);
}
 
public function actionProd() {
    $out = [];
    if (isset($_POST['depdrop_parents'])) {
        $ids = $_POST['depdrop_parents'];
        $cat_id = empty($ids[0]) ? null : $ids[0];
        $subcat_id = empty($ids[1]) ? null : $ids[1];
        if ($cat_id != null) {
           $data = self::getProdList($cat_id, $subcat_id);
            /**
             * the getProdList function will query the database based on the
             * cat_id and sub_cat_id and return an array like below:
             *  [
             *      'out'=>[
             *          ['id'=>'<prod-id-1>', 'name'=>'<prod-name1>'],
             *          ['id'=>'<prod_id_2>', 'name'=>'<prod-name2>']
             *       ],
             *       'selected'=>'<prod-id-1>'
             *  ]
             */
           
           echo Json::encode(['output'=>$data['out'], 'selected'=>$data['selected']]);
           return;
        }
    }
    echo Json::encode(['output'=>'', 'selected'=>'']);
}

如果`controller`中`behaviors`加入了访问控制，要开启以上2个`action`的访问
```