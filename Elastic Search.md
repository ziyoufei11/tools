##### Elastic Search php

```
使用插件版本 "elasticsearch/elasticsearch": "^7.16"
```

1. 创建client

   ```php
       $hosts = [
           'http://172.168.0.1:9200',
       ];
       // HTTP 基本认证'http://user2:pass2@other-host.com:9200' // 不同主机上的不同凭证];
   
       $client = ClientBuilder::create()->setHosts($hosts)->build();
   ```

2. 搜索

   ```php
   ##为非必须参数
   $params = [
       'index' => 'xxx',//索引 ##搜索所有
       'body' => []//条件列
   ];
   #body
   #query //搜索条件 ##无条件
   'query' => [
       'match' => [//匹配一个字段
           //字段为文档时为包含 数字时为匹配
           'keywords'=>'a b'//空格间隔,则为包含a or b的数据
       ]
       'match_phrase' => [//严格匹配一个字段
           'keywords'=>'a b c d',
           //必须包含a b c d 的数据,且字段的顺序必须是 a>b>c>d 中间不能匹配到其他字段
       ]
       'multi_match' => [//匹配多个字段
           'query' => 'a b',
           'fields'=>['first_name','last_name']
       ]
       'term'	=>	[//精准查找
           'id' => 1
       ]
       'terms' => [//精准查找多个
           'age' => [44,55,66]
       ]
       'range' => [//范围查找
           'id' => [
               'gte' => 19323,//大于等于
               'lte' => 19325,//小于等于
           ]
       ]
       'exists' => [//存在此属性的文档
           'field' => 'age'
       ]
   
   
   #bool
       'bool'=>[
           'must'=>[
               //多个匹配规则 只会以最后一个匹配规则为准
               'match'=>[
                   'image'=>'0b9bf66ea871f6a9c0e532386179e9dfdb17c7f5'
               ]
           ],
           'must_not'=>[//不能存在的条件 多个规则只会以最后一个为准
               'match'=>[
                   'weight'=>1.363
               ]
           ],
           'filter' => [//结果过滤
               'range' => [//指定顺序
                   'id' => [//指定字段
                       'gte' => 19323,//大于等于
                       'lte' => 19325,//小于等于
                   ]
               ]
           ]
   ]
   #end bool
   #end query
   #aggs 聚合分组查询 ##无分组
   'aggs' => [
       'g' => [//自定义名称
           'terms' => [//指定分组字段
               'field'=>'unit.keyword'//text需要这样子写才能支持
               'order'=>[//分组后排序
                   'xx'=>'asc'
               ],
           ],
           'aggs' => [//新的聚合
               'average_weight' => [//自定义名称
                   'avg' => [//平均值
                       'field' => 'weight'//平均值字段
                   ]
               ]
           ]
   	],
   ]
   #end aggs
       'analyzer' => 'ik_max_word', //设置分词器,对中文更友好 默认 standard(以空格拆分)
       'size'=>0,//返回只有聚合结果，无具体数据。
       'sort'=>[//搜索排序 ##无排序
           'weight'=>'desc',
           'id'=>'asc'//排序基本不允许使用string,如果保存float需要注意别存成string
       ],
       'from'=>20,//定位limit ##0
       'size'=>2,//每页数据 ##10条
   #end body
   /**
   *文档字段匹配特殊规则
   *特殊符号不计入匹配原则(空格 标点符号)
   **/
   $res = $client->search($params);
   ```

3. 返回数据

   ```json
   #为非必须返回参数
   {
       "took": 1,//查询花费时长（毫秒）
       "timed_out": false,//请求是否超时
       "_shards": {//搜索了多少分片，成功、失败或者跳过了多个分片（明细）
           "total": 7,
           "successful": 7,
           "skipped": 0,
           "failed": 0
       },
       "hits": {
           "total": {
               "value": 10000,//找到的文档总数
               "relation": "gte"//结果的匹配方式
           },
           "max_score": 1,//最相关的文档分数 or null
           "hits": [//返回的命中数据
               {
                   "_index": "goods",//分组
                   "_type": "_doc",
                   "_id": "-bZsTnsBk0MyxCYp_hbL",//唯一id
                   "_score": 1,//文档的相关性算分 无搜索条件时为null
                   "_source": {},//文档信息
                   "sort": [//排序信息 #有排序时返回数据
                       1.361,
                       6747
                   ]
               }
           ]
       },
       "aggregations": {//分组信息 #分组查询时返回数据
           "group_by_state": {
               "doc_count_error_upper_bound": 0,
               "sum_other_doc_count": 4,
               "buckets": [
                   {
                       "key": 63,//分组结果
                       "doc_count": 54189//此组数量
                   },
                   {
                       "key": 84,
                       "doc_count": 140
                   }
               ]
           }
       }
   }
   ```

