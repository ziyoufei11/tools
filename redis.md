### redis自用 全命令

+ 接入
  > connect($host,$port = 6379,$timeout = 0.0,$reserved = null,$retryInterval = 0, $readTimeout = 0.0)
  > - string host 链接地址
  > - int port 链接端口
  > - float timeout 写入超时时间
  > - null reserved
  > - null retryInterval
  > - float readTimeout 读取超时时间
  >
  > 链接redis,脚本结束后释放资源

  > pconnect  
  > 参数同上  
  > 链接redis,脚本结束后不会释放,只有在php进程结束后才会释放

  > auth($password)
  > - password 链接redis密码(如有)
  >
  > 设置redis链接密码

  > close()  
  > 参数同上  
  > 断开链接,如需关闭持久链接需要phpredis>=4.2

+ 通用命令
  > isConnected()  
  > 返回值:bool  
  > 判断redis是否链接

  > getHost()  
  > 返回值:string | flase  
  > 获取当前链接host地址

  > getPort()  
  > 返回值:int | flase  
  > 获取当前链接端口地址

  > getDbNum()  
  > 返回值:int | flase  
  > 获取当前链接选择db库

  > select($dbIndex)
  > - dbIndex 切换db库,int
  >
  > 返回值:bool  
  > 切换redis db库

  > move($key, $dbIndex)
  > - key 键名,string
  > - dbIndex 切换db库,int
  >
  > 返回值:bool  
  > 将一个键切换至其他db库

  > rename($srcKey, $dstKey)
  > - srcKey 键名,string
  > - dstKey 键名,string
  >
  > 返回值:bool  
  > 将一个键改名,会覆盖原dstKey

  > renameNx($srcKey, $dstKey)
  > - srcKey 键名,string
  > - dstKey 键名,string
  >
  > 返回值:bool  
  > 在dstKey不存在时将键改名

  > expire($key, $ttl)
  > - key 键名,string
  > - ttl 生存时间,单位(秒),int
  >
  > 返回值:bool  
  > 以当前时间为一个键设置生存时间

  > pExpire($key, $ttl)
  > - key 键名,string
  > - ttl 生存时间,单位(毫秒),int
  >
  > 返回值:bool  
  > 以当前时间为一个键设置生存时间

  > expireAt($key, $timestamp)
  > - key 键名,string
  > - timestamp 时间戳(毫秒),int
  >
  > 返回值:bool  
  > 以结束时间戳为一个键设置生存时间

  > keys($pattern)
  > - pattern 键名,string
  >
  > 返回值:array  
  > 通配符匹配所有符合条件的键

  > dbSize()
  >
  > 返回值:int  
  > 获取当前db库的键总量

  > getTimeout()  
  > 返回值:float | flase  
  > 获取当前redis写入响应时间

  > getReadTimeout()  
  > 返回值:float | flase  
  > 获取当前redis读取响应时间

  > getPersistentID()  
  > 返回值:string | null(不使用时返回) | bool  
  > 获取phpredis使用的永久性ID

  > getAuth()  
  > 返回值:string | null(不使用时返回) | bool  
  > 获取链接密码

  > flushDB()  
  > 返回值:true  
  > 删除当前选择数据库的所有key

  > flushAll()  
  > 返回值:true  
  > 删除所有数据库的key

  > swapdb($db1,$db2)
  > - db1 第一个db库
  > - db2 第二个db库
  >
  > 返回值:bool  
  > 交换两个db库

  > setOption($option, $value)
  > - option
  > - value
  >
  > 返回值:bool  
  > 设置客户选项

  > getOption($option)
  > - option
  >
  > 返回值:mixed | null  
  > 获取客户选项

  > config($operation, $key, $value)
  > - operation GET,SET,string
  > - key 需要获取/设置的参数,string
  > - value 设置时配置,string | mixed
  >
  > 返回值:array  
  > 获取/设置配置

  > ping($message=null)
  > - message
  >
  > 返回值:bool | string(传入message)  
  > 检查当前连接状态

  > echo($message)
  > - message
  >
  > 返回值:string(传入message)  
  > 输出传入信息

  > eval($script, $args = array(), $numKeys = 0)
  > - script  lua脚本,string
  > - args  传递的参数变量,string
  > - numKeys  参数变量的分隔数量,string
  >```
  >$lua  = <<<lua
  >local count=KEYS[1]
  >for i=1,count,3 do
  >redis.call("Hset",ARGV[i],ARGV[i+1],ARGV[i+2])
  >redis.call("EXPIRE",ARGV[i],172800)
  >end
  >return
  >lua;
  >return $this->redis->eval($lua, $data, 1);//1代表$data数组中第一个元素为KEYS,其他为ARGV(也从1开始)
  >```
  > 返回值:mixed 根据lua脚本返回  
  > 运行lua脚本命令

  > evalSha($scriptSha, $args = array(), $numKeys = 0)
  > - scriptSha  缓存的lua脚本hash名,string
  > - args  传递的参数变量,string
  > - numKeys  参数变量的分隔数量,string
  > 
  > 返回值:mixed 根据lua脚本返回  
  > 运行服务器缓存的lua脚本命令

  > script($command, $script)
  > - command  命令,string
  > - script  lua脚本,string
  >```
  >$redis-> script（'load'，$ script）;//缓存lua脚本.返回缓存hash
  >$redis-> script（'flush'）;//刷新服务器脚本缓存
  >$redis-> script（'kill'）;//杀死服务器还未执行过写入的lua脚本
  >$redis-> script（'exists'，$ script1，[$ script2，$ script3，...]）;//查询lua脚本是否存在
  >```
  > 返回值:mixed  
  > 对lua脚本进行处理

  > getLastError()
  >
  > 返回值:string|null  
  > 返回最后一条错误信息

  > clearLastError()
  >
  > 返回值:bool  
  > 清除最后一条错误信息

  > client($command, $value = '')
  > - command 操作类型,string
  > ```
  > list 返回所有连接redis客户端
  > getname 获取当前客户端别名
  > setname 设置当前客户端别名
  > kill  杀死指定ip:端口的链接,如果杀死的是本机,会自动重连,实际是会生效
  > ```
  > - value 操作内容,string
  >
  > 返回值:bool  
  > 清除最后一条错误信息

  > _prefix($value)
  > - value  需要加前缀的值
  >
  > 返回值:string  
  > 为传入的值增加配置中的前缀(如果有)

  > _serialize($value)
  > - value  需要被序列化的值,mixed
  >
  > 返回值:string  
  > 将传入的值进行序列化
  > ```
  > 需要先设置类型,不然序列化只会返回简单的类型
  > $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
  > ```

  > _unserialize($value)
  > - value  需要解序列化的值,mixed
  >
  > 返回值:string  
  > 将传入的值进行解除序列化
  > ```
  > 需要先设置类型,不然序列化只会返回传入的string
  > $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
  > ```

  > dump($key)
  > - key  键名,string
  >
  > 返回值:string  
  > 返回当前键redis编码的键值

  > restore($key, $ttl, $value)
  > - key  键名,string
  > - ttl  新键生存时间,int
  > - value  dump出的值,string
  >
  > 返回值:bool  
  > 将dump出的值存入新键

  > bgrewriteaof()
  >
  > 返回值:简单字符串回复，指示成功执行调用后，重写已开始或即将开始。
  > 当配置中appendonly yes设置成功时.redis所有的更改数据指令将被收集进入AOF.  
  > 当redis意外停止丢失数据时,可以用此命令重建所有key

  > slaveof($host = '127.0.0.1', $port = 6379)
  > - host  ip地址,string
  > - port 接口,int
  >
  > 返回值:bool  
  > 将当前服务器设置为host服务器的从属服务器,同步所有主服务器的数据
  > ```
  > 1.如果之前已为其他服务器从属服务器,会抛弃掉旧数据
  > 2.如果直接调用slaveof(),会由从属变为master
  > **未测试,机制待完善**
  > ```

  > slowLog(string $operation, int $length = null)
  > - operation 命令类型
  >> get 获取日志内容
  > > reset 重置日志
  > > len 查询日志长度
  > - length 获取日志条数,int
  >
  > 返回值:mixed  
  > slowlog相关操作

  > object($string = '', $key = '')
  > - string 命令类型
  > ```
  > refcount 返回与指定键关联的值的引用数
  > encoding 返回用于存储与键关联的值的内部表示形式的类型
  > idletime 返回自存储在指定键处的对象处于空闲状态以来的秒数（读或写操作未请求）
  > ```
  > - key 对象key
  >
  > 返回值:string|int|false  
  > 检查键的内部结构,调试用

  > save()
  >
  > 返回值:string 如果同时有其他在备份,返回false  
  > 保存一个发起时间时所有rendis实例的数据快照  
  > **此命令会阻止其他所有客户端接入,尽量使用bgsave不要使用save**

  > bgsave()
  >
  > 返回值:string 如果同时有其他在备份,返回false  
  > 保存后台数据库

  > lastSave()
  >
  > 返回值:int timestamp  
  > 返回上次磁盘保存的时间戳

  > migrate($host, $port, $key, $db, $timeout, $copy = false, $replace = false)
  > - host 目标地址,string
  > - port 目标端口,int
  > - key 需要转移的key,string
  > - db 目标库,int
  > - timeout 超时时间,int
  > - copy 是否发送copy标志给redis,bool
  > - replace 是否发送replace标志给redis,bool
  >
  > 返回值:bool  
  > 将当前库某个键迁移至其他redis实例

  > wait($numSlaves, $timeout)
  > - numSlaves 写入从站数量,int
  > - timeout 毫秒,int
  >
  > 返回值:int 成功写入从站的数量  
  > 阻塞当前客户端,直到之前的写入命令成功过或超时

  > type($key)
  > -key 键值,string
  >
  > 返回值:int
  > ```
  > - string: Redis::REDIS_STRING
  > - set:   Redis::REDIS_SET
  > - list:  Redis::REDIS_LIST
  > - zset:  Redis::REDIS_ZSET
  > - hash:  Redis::REDIS_HASH
  > - other: Redis::REDIS_NOT_FOUND
  > ```
  > 返回当前键类型

  > time()
  >
  > ```
  >array(2) {
  >  [0] => string(10) "1342364352"//unix时间戳
  >  [1] => string(6) "253002"//微妙
  >}
  > ```
  > 返回值:array   
  > 返回当前服务器时间

  > ttl($key)
  > - key 键名,string
  >
  > 返回值:int | bool   
  > 以秒为单位,返回key剩余生存时间

  > pttl($key)
  > - key 键名,string
  >
  > 返回值:int | bool   
  > 以毫秒为单位,返回key剩余生存时间

  > persist($key)
  > - key 键名,string
  >
  > 返回值:bool   
  > 将键生存时间清除,已经是不过期或key不存在则返回false

  > pfAdd($key, array $elements)
  > - key 键名,string
  > - elements 键名数组,array
  >
  > 返回值:bool   
  > 将n个键信息储存在新键中,键名自动唯一

  > pfCount($key)
  > - key 键名,string
  >
  > 返回值:int   
  > 返回这个键中储存键信息数量

  > pfMerge($destKey, array $sourceKeys)
  > - key 储存键名,string
  > - sourceKeys 需要合并的键,array
  >
  > 返回值:bool   
  > 将几个pf键信息合并,储存至新键中

  > rawCommand($command, $arguments)
  > - command 命令名,string
  > - arguments 参数,mixed
  >
  > 返回值:mixed   
  > 使redis服务器执行任意命令

  > del($key, ...$otherkeys)
  > - key 删除的键名 int|string|array 可传入数组,效果同可变传参
  > - ...otherkeys 删除的其他键 可变传参
  >
  > 返回值:int 删除数量  
  > 删除指定键

  > randomKey()
  >
  > 返回值:string   
  > 获取一个随机键名,如果库为空返回空字符串

  > unlink($key, ...$otherkeys)
  > - key 删除的键名 int|string|array 可传入数组,效果同可变传参
  > - ...otherkeys 删除的其他键 可变传参
  >
  > 返回值:int 删除数量  
  > 异步删除指定键

  > multi($mode = Redis::MULTI)
  > - mode 默认MULTI
  >> - Redis::MULTI 保证原子性
  >> - Redis::PIPELINE 更快完成操作.不保证原子性
  >```
  >$res=$redis->multi()  
  >->set('abc', 'abc')  
  >->get('abc')  
  >->set('abc', '222')  
  >->get('abc')  
  >->exec();  
  >var_dump($res)  
  >array(4) { [0]=> bool(true) [1]=> string(3) "abc" [2]=> bool(true) [3]=> string(3) "222" }
  >```
  > 只有使用了exec之后才会执行  
  > 返回值:array 每一步完成结果  
  > 进入交易模式.原子性处理

  > pipeline()
  > - mode 默认MULTI
  >> - Redis::MULTI 保证原子性
  >> - Redis::PIPELINE 更快完成操作.不保证原子性
  >```
  >$res=$redis->pipeline()
  >->ping()
  >->multi()
  >->set('x', 42)
  >->incr('x')
  >->exec()
  >->ping()
  >->multi()
  >->get('x')
  >->del('x')
  >->exec()
  >->ping()
  >->exec();
  >var_dump($res)  
  >array(5) { [0]=> bool(true) [1]=> array(2) { [0]=> bool(true) [1]=> int(43) } [2]=> bool(true) [3]=> array(2) { [0]=> string(2) "43" [1]=> int(1) } [4]=> bool(true) }
  >```
  > 返回值:array 每一步完成结果  
  > 进入管道模式,更快的完成redis命令(一次请求) 每个命令都会返回redis实例,

  > getMode()
  >
  > 返回值:int Redis::ATOMIC, Redis::MULTI or Redis::PIPELINE
  > 返回当前redis模式

  > exec()
  >
  > 返回值 array 执行结果数组 使用WATCH时，如果中止执行，则EXEC可以返回Null答复。  
  > 执行命令链,并将连接恢复正常  
  > 使用WATCH时，只有在未修改监视键的情况下，EXEC才会执行命令，从而允许检查设置机制。

  > discard()
  >
  > 返回值 true  
  > 丢弃事务中所有先前排队的命令，并将连接状态恢复为正常。  
  > 如果使用了WATCH，DISCARD将取消监视的所有键(unwatch)。

  > watch($key)
  > - key 要监视的键,string | array
  >
  > 监听一个/多个key,在multi事务处理时如果监听键的值发生变化,事务将会失败

  > unwatch()
  >
  > 取消监听  
  > exists($key)
  > - key 键名,string | string[]
  >
  > 返回值:int | bool  
  > 返回key是否存在信息,传入多个key时返回存在的数量值

  > sort($key, $option = null)
  > - key 键名,string | string[]
  > - option 配置,array
  >
  > 返回值:array  
  > 对键进行排序  
  > @TODO:暂无测试数据

  > info($option = null)
  > - option 配置,array
  >
  > 返回值:string  
  > 返回redis相关信息  
  > @TODO:暂无测试数据

  > resetStat()
  >
  > 返回值:bool  
  > 重置info查询数据
  >> 命中次数  
  > 未命中次数  
  > 处理命令数  
  > 收到链接数  
  > 过期密钥数

+ 字符串类型

  > get($key)
  > - key 保存的键名
  >
  > 返回值:string | mixed | false
  > 获取保存的值

  > set($key, $value, $timeout = null)
  > - key 设置的键名
  > - value 设置的值
  > - timeout 过期时间,秒(可传递数组高级设置)
  >
  > exp: set('key','vaule',['NX','EX'=>60])
  > - 标识设置key为vlue,并且在key不存在时才设置过期时间
  > - EX 过期时间,秒
  > - PX 过期时间,毫秒
  > - NX 在key不存在时才设置 (存在返回flase,set失败)
  > - XX 在key存在时才设置 (会重置过期时间)
  >
  > 如果使用序列化设置可以储存数组,
  > 在不使用序列化情况下储存数组时会设置成功,设置value为Array5个字母  
  > 返回值:bool  
  > 保存的一个值

  > mset(array $array)
  > - array key-value数组,array
  >
  > 返回值:bool   
  > 批量设置多个键值对

  > msetnx(array $array)
  > - array key-value数组,array
  >
  > 返回值:bool   
  > 在所有key都不存在时设置多个键值对

  > mget(array $array)
  > - array key数组,array
  >
  > 返回值:array   
  > 批量获取多个键值对,不存在的key或非string类型返回flase

  > getSet($key,$value)
  > - key 保存的键名,string
  > - value 键值,string|mixed
  >
  > 返回值:string | mixed
  > 设置一个新值并返回之前的值,之前无此值则返回flase,不代表设置失败

  > append($key, $value)
  > - key 设置的键名
  > - value 设置的值
  >
  > 返回值:int 追加操作后字符串的长度  
  > 在字符串后追加value,如果key不存在则新建

  > getRange($key, $start, $end)
  > - key 键名,string
  > - start 起始位置,int
  > - end 结束位置,int
  >
  > 返回值:string 无返回空字符串
  > 返回主字符串匹配的子字符串
  > ```
  > $redis->set('key', 'string value');
  > $redis->getRange('key', 0, 5);   // 'string'
  > $redis->getRange('key', -5, -1); // 'value'
  > ```

  > setRange($key, $offset, $value)
  > - key 键名,string
  > - offset 起始位置,int
  > - value 替换内容,string
  >
  > 返回值:int 修改后的字符串长度
  > 将string从起始位置后开始替换为value内容
  > ```
  >$redis->set('test','hello world!');
  >var_dump($redis->setRange('test',3,'hahahah'));
  >var_dump($redis->get('test'));
  >string(12) "helhahahahd!"
  >
  >$redis->set('test','hello world!');
  >var_dump($redis->setRange('test',20,'hahahah'));
  >var_dump($redis->get('test'));
  >string(27) "hello world!hahahah"
  >注:如果长度大于原字符串长度.redis会用\x00(0x00,意为null)替代一个字符
  > ```

  > strlen($key)
  > - key 保存的键名
  >
  > 返回值:int  
  > 返回字符串长度

  > setex($key, $ttl, $value)
  > - key 保存的键名
  > - ttl 保存时间,秒
  > - value 保存值
  >
  > 返回值:bool  
  > 保存一个会过期的值(秒)

  > psetex($key, $ttl, $value)
  > - key 保存的键名
  > - ttl 保存时间,毫秒
  > - value 保存值
  >
  > 返回值:bool  
  > 保存一个会过期的值(毫秒)

  > setnx($key, $value)
  > - key 保存的键名
  > - value 保存值
  >
  > 返回值:bool  
  > 在键不存在时保存

  > incr($key)
  > - key 键名,string
  >
  > 返回值:int,当前最新数值  
  > 给键值增加1

  > incrByFloat($key,$increment)
  > - key 键名,string
  > - increment 增加值,float
  >
  > 返回值:float,当前最新数值
  >

  > incrBy($key,$value)
  > - key 键名,string
  > - value 增加值,int
  >
  > 返回值:int,当前最新数值  
  > 给键值增加指定整数值

  > decr($key)
  > - key 键名,string
  >
  > 返回值:int,当前最新数值  
  > 给键值减去1

  > decrBy($key,$value)
  > - key 键名,string
  > - value 增加值,int
  >
  > 返回值:int,当前最新数值  
  > 给键值减少指定整数值

+ bit类型

  > bitpos($key, $bit, $start = 0, $end = null)
  > - key 键名,string
  > - bit 需要定位的bit值,int
  > - start 开始的字节数,int
  > - end 结束字节数,int
  >
  > 返回值:int,当前定位满足条件位置,-1为未定位到  
  > 返回下一个满足条件的bit位置
  > **start和end控制的为字节数,8位为一字节**
  > ```
  >$redis->setBit('key',3,1);
  >$redis->setBit('key',4,1);
  >var_dump($redis->bitpos('key',1));//3
  >var_dump($redis->bitpos('key',1,1));//-1
  >$redis->setBit('key',10,1);
  >var_dump($redis->bitpos('key',1));//3
  >var_dump($redis->bitpos('key',1,1));//10
  > ```

  > getBit($key, $offset)
  > - key 键名,string
  > - offset 查询位置,int
  >
  > 返回值:int  
  > 获取指定bit位置的信息0 | 1

  > setBit($key, $offset, $value)
  > - key 键名,string
  > - offset 查询位置,int
  > - value 值,int for (0,1),bool
  >
  > 返回值:int 返回此位置之前的值信息  
  > 设置指定位置的bit信息

  > bitCount($key, $start, $end)
  > - key 键名,string
  > - start 查询起始位符,int
  > - end 查询结束位符,int
  >
  > 返回值:int  
  > 返回bit位为1的数量  
  > **start和end为可选参数,为字节数位置**

  > bitOp($operation, $retKey, $key1, ...$otherKeys)
  > - operation 运算类型,string
  >> AND 和运算,两个bit位都为1时为1  
  > > OR 或运算,两个bit位有一个为1时就为1  
  > > NOT 否运算,对**一个**bit进行01互换  
  > > XOR 异或运算,两个bit为不同时为1
  > - start 查询起始位符,int
  > - end 查询结束位符,int
  >
  > 返回值:int 新值的字节长度  
  > 将一个或多个key进行bit运算(按照参数传入的顺序挨个进行),储存至新的retkey中

+ hash类型

  > hSet($key, $hashKey, $value)
  > - key 键名,string
  > - hashKey hash名,string
  > - value hash值,string
  >
  > 返回值:int | bool  1:无此值新增 0:有此值替换
  > 储存一个hash值

  > hSetNx($key, $hashKey, $value)
  > - key 键名,string
  > - hashKey hash名,string
  > - value hash值,string
  >
  > 返回值:bool  
  > 在无此hash值时储存一个hash值

  > hGet($key, $hashKey)
  > - key 键名,string
  > - hashKey hash名,string
  >
  > 返回值:string|false  
  > 获取一个hash值

  > hLen($key)
  > - key 键名,string
  >
  > 返回值:int|false  
  > 获取一个hash队列元素数量

  > hDel($key,...$hashKey)
  > - key 键名,string
  > - ...hashKey hash名,string
  >
  > 返回值:int|  
  > 删除一个hash队列中n个hash值

  > hKeys($key)
  > - key 键名,string
  >
  > 返回值:array  
  > 获取一个hash队列中所有的hashkey

  > hVals($key)
  > - key 键名,string
  >
  > 返回值:array  
  > 获取一个hash队列中所有的value

  > hGetAll($key)
  > - key 键名,string
  >
  > 返回值:array  hashkey=>value
  > 获取一个hash队列中所有的值

  > hExists($key, $hashKey)
  > - key 键名,string
  > - hashKey hash名,string
  >
  > 返回值:bool
  > 判断一个hash队列中hashkey是否存在

  > hIncrBy($key, $hashKey, $value)
  > - key 键名,string
  > - hashKey hash名,string
  > - value 增量,int
  >
  > 返回值:int 最新值
  > 为一个hash队列中hashkey增加指定value

  > hIncrByFloat($key, $hashKey, $value)
  > - key 键名,string
  > - hashKey hash名,string
  > - value 增量,float
  >
  > 返回值:float 最新值
  > 为一个hash队列中hashkey增加指定value

  > hMSet($key, $hashKeys)
  > - key 键名,string
  > - hashKeys hash=>value数组,array
  >
  > 返回值:bool 
  > 为一个hash队列批量设置hashkey

  > hMGet($key, $hashKeys)
  > - key 键名,string
  > - hashKeys hashkey数组,array
  >
  > 返回值:array hashKey=>value
  > 批量获取一个hash队列中指定hashkey

  > hStrLen(string $key, string $field)
  > - key 键名,string
  > - field hashkey,string
  >
  > 返回值:int
  > 获取一个hash队列中指定hashkey值的长度  
  > **注意中文长度**


+ list类型

  > lPush($key, ...$value1)
  > - key 键名,string
  > - ...value 增加值,传入多个时根据参数顺序依次推入,string | mixed
  >
  > 返回值:int | false,成功为列表长度,失败为false  
  > 为列表开头推入数据

  > rPush($key, ...$value1)
  > - key 键名,string
  > - ...value 增加值,传入多个时根据参数顺序依次推入,string | mixed
  >
  > 返回值:int | false,成功为列表长度,失败为false  
  > 为列表结尾推入数据

  > lPushx($key,$value1)
  > - key 键名,string
  > - value 增加值,string | mixed
  >
  > 返回值:int | false,成功为列表长度,失败为false  
  > 在列表存在时为列表开头推入数据

  > rPushx($key,$value1)
  > - key 键名,string
  > - value 增加值,string | mixed
  >
  > 返回值:int | false,成功为列表长度,失败为false  
  > 在列表存在时为列表结尾推入数据

  > lPop($key)
  > - key 键名,string
  >
  > 返回值:mixed | bool,成功为获得值,空为false  
  > 返回并删除列表开头的一个值

  > rPop($key)
  > - key 键名,string
  >
  > 返回值:mixed | bool,成功为获得值,空为false  
  > 返回并删除列表结尾的一个值

  > blPop($keys, $timeout)
  > - key 键名,string | string[]
  > - timeout 阻塞时间,int
  >
  > 返回值:array ,成功为['listName', 'element'],失败为[]
  > ```
  > //监听单个list
  > $redis->blPop('test',10);
  > //监听多个list
  > $redis->blPop(['test1','test2'],10);
  > ```
  > 阻塞设置时间并返回并删除列表开头的一个值

  > brPop($keys, $timeout)
  > - key 键名,string | string[]
  > - timeout 阻塞时间,int
  >
  > 返回值:array ,成功为['listName', 'element'],失败为[]
  > 阻塞设置时间并返回并删除列表末尾的一个值

  > lLen($keys)
  > - key 键名,string
  >
  > 返回值:int|bool ,成功为列表长度,失败为false
  > 查询列表长度

  > lIndex($key, $index)
  > - key 键名,string
  > - index 获取位置,int
  >
  > 返回值:mixed|bool ,成功为此位置value,失败为false
  > 获取指定位置键值

  > lSet($key, $index, $value)
  > - key 键名,string
  > - index 位置,int
  > - value 键值,string
  >
  > 返回值:bool ,成功为true,索引超出范围等失败为false
  > 将指定索引键值更新

  > lRange($key, $start, $end)
  > - key 键名,string
  > - start 起始位置,int
  > - end 结束位置,int
  >
  > 返回值:array
  > 返回指定位置内的列表内容

  > lTrim($key, $start, $stop)
  > - key 键名,string
  > - start 起始位置,int
  > - stop 结束位置,int
  >```
  >  $redis->lPush('test',1);
  >  $redis->lPush('test',2);
  >  $redis->lPush('test',3);
  >  var_dump($redis->lRange('test',0,-1));
  >  var_dump($redis->lTrim('test',0,1));
  >  var_dump($redis->lRange('test',0,-1));
  >array(3) {
  >[3]=>string(1) "3"
  >[4]=>string(1) "2"
  >[5]=>string(1) "1"
  >}
  >bool(true)
  >array(1) {
  >[0]=>string(1) "3"
  >}
  > ```
  > 返回值:false
  > 修剪列表为指定位置内容

  > lRem($key, $value, $count)
  > - key 键名,string
  > - value 匹配值,int
  > - count 删除次数,int
  >
  > 返回值:int|bool
  > 删除列表和value匹配内容count次,count为0时删除所有匹配项

  > lInsert($key, $position, $pivot, $value)
  > - key 键名,string
  > - position 匹配类型,int
  >> Redis::BEFORE 匹配元素之前插入  
  > > Redis::AFTER 匹配元素之后插入
  > - pivot 匹配元素,string
  > - value 插入值,string|mixed
  >
  > 返回值:int 大于0:队列当前长度,0:key不存在,-1:没有搜索到匹配元素  
  > 为匹配到的第一个元素前/后插入指定值

  > rpoplpush($srcKey, $dstKey)
  > - srcKey 取出列表,string
  > - dstKey 推入列表,string
  >
  > 返回值:string|mixed|false  
  > 将列表1的队尾数据推送至列表2队首

  > rpoplpush($srcKey, $dstKey, $timeout)
  > - srcKey 取出列表,string
  > - dstKey 推入列表,string
  > - timeout 超时时间,string
  >
  > 返回值:string|mixed|false  
  > 阻塞式获取.将列表1的队尾数据推送至列表2队首


+ 集合,无序,键值唯一

  > sAdd($key, ...$value1)
  > - key 键名,string
  > - ...value 增加值,string | mixed
  >
  > 返回值:int | bool,成功为添加元素数量,失败为false  
  > 为集合添加元素

  > sRem($key, ...$member1)
  > - key 键名,string
  > - ...member1 删除值,string | mixed
  >
  > 返回值:int,删除元素数量  
  > 为集合删除元素

  > sMove($srcKey, $dstKey, $member)
  > - srcKey 被移除键名,string
  > - dstKey 添加键名,string
  > - member1 被转移值,string | mixed
  >
  > 返回值:bool,成功true,其他失败sfalse  
  > 将集合1元素移至集合2

  > sCard($key)
  > - key 键名,string
  >
  > 返回值:int  
  > 查询集合中有多少个元素

  > sPop($key, $count = 1)
  > - key 键名,string
  > - count 取出数量,int
  >
  > 返回值:string|mixed|array|bool  
  > 从集合中**随机**取出并**删除**$count个值,
  > count值为1返回string|bool,大于1多个返回array

  > sRandMember($key, $count = 1)
  > - key 键名,string
  > - count 取出数量,int
  >
  > 返回值:string|mixed|array|bool  
  > 从集合中**随机**取出$count个值,
  > count值为1返回string|bool,大于1多个返回array

  > sInter($key1, ...$otherKeys)
  > - key 键名,string
  > - ...otherKeys 其他对比键,string
  >
  > 返回值:array  
  > 对比集合,返回所有集合唯一交集

  > sInterStore($dstKey, $key1, ...$otherKeys)
  > - dstKey 要储存的键名,string
  > - key 键名,string
  > - ...otherKeys 其他对比键,string
  >
  > 返回值:int|false 新集合的数量,无为false  
  > 对比集合,将所有集合唯一交集存至dstKey

  > sUnion($key1, ...$otherKeys)
  > - key 键名,string
  > - ...otherKeys 其他对比键,string
  >
  > 返回值:array  
  > 对比集合,返回所有集合并集

  > sUnionStore($dstKey, $key1, ...$otherKeys)
  > - dstKey 要储存的键名,string
  > - key 键名,string
  > - ...otherKeys 其他对比键,string
  >
  > 返回值:int|false 新集合的数量,无为false  
  > 对比集合,将所有集合并集存至dstKey

  > sDiff($key1, ...$otherKeys)
  > - key 键名,string
  > - ...otherKeys 其他对比键,string
  >
  > ```
  >$redis->sAdd('test', 1, 2, 3, 4, 5, 6);
  >$redis->sAdd('test2', 4, 5, 6, 7, 8, 9);
  >$redis->sAdd('test3', 6, 9,10, 11, 12, 13);
  >\var_dump($redis->sDiff('test', 'test2', 'test3'));
  > array(3) { [0]=> string(1) "1" [1]=> string(1) "2" [2]=> string(1) "3" }
  >\var_dump($redis->sDiff('test3', 'test2', 'test'));
  > array(4) { [0]=> string(2) "10" [1]=> string(2) "11" [2]=> string(2) "12" [3]=> string(2) "13" }
  > ```
  > 返回值:array  
  > 以**第一个集合**为基准,返回所有集合差集

  > sDiffStore($dstKey, $key1, ...$otherKeys)
  > - dstKey 要储存的键名,string
  > - key 键名,string
  > - ...otherKeys 其他对比键,string
  >
  > 返回值:int|false 新集合的数量,无为false  
  > 以**第一个集合**为基准,将所有集合差集存至dstKey

  > sMembers($key)
  > - key 键名,string
  >
  > 返回值:array  
  > 返回所有集合元素,元素顺序为redis自身排序

+ 有序集合

  > zAdd(($key, $options, $score1, $value1 = null, $score2 = null, $value2 = null, $scoreN = null, $valueN = null)
  > - key 要储存的键名,string
  > - options 配置(如果没有可忽略),array
  > ```
  > XX 仅更新已存在的元素
  > NX 不更新已存在的元素 
  > LT 仅当新分数小于当前分数时才更新元素 >=6.2
  > GT 仅当新分数大于当前分数时才更新元素 >=6.2
  > CH 统计此次添加修改元素的数量
  > INCR 类似ZINCRBY,有此值时只能传入一个分值队
  > ```
  > - ...socre,value 对应的socre和value值

  > zScore($key, $member)
  > - key 键名,string
  > - member 要被获取的值,string
  >
  > 返回值:float|bool  
  > 获取指定value的分数值

  > zRank($key, $member)
  > - key 键名,string
  > - member 要被获取的值,string
  >
  > 返回值:int|false  
  > 获取指定value的redis正向排序值

  > zRevRank($key, $member)
  > - key 键名,string
  > - member 要被获取的值,string
  >
  > 返回值:int|false  
  > 获取指定value的redis逆向排序值

  > zRem($key, $member1, ...$otherMembers)
  > - key 键名,string
  > - ...member 要被删除的值,string
  >
  > 返回值:int  
  > 删除指定value的值,返回删除成功的数量

  > zRemRangeByScore($key, $start, $end)
  > - key 键名,string
  > - start 被删除起始值,string
  > - end 被删除结束值,string
  > ```
  > ( 不包含此值 exp:$redis->zRemRangeByScore('test','(1','2')
  > -inf 负无穷
  > +inf 正无穷
  > ```
  >
  > 返回值:int  
  > 删除指定分段的值,返回删除成功的数量

  > zRemRangeByRank($key, $start, $end)
  > - key 键名,string
  > - start 被删除起始值,string
  > - end 被删除结束值,string
  >
  > 返回值:int  
  > 根据redis的排序规则删除指定位置的元素,返回删除成功的数量

  > zRange($key, $start, $end, $withscores = null)
  > - key 键名,string
  > - start 开始分数,string
  > - end 结束分数,string
  > - withscores 返回value->score数组,bool
  >
  > 返回值:array  
  > 从队首开始(value排序)查询满足value条件的数组
  > ```
  >$redis->zAdd('test',2,1,2,2,2,3);
  >\var_dump($redis->zRange('test',2,3));
  >\array(1) { [0]=> string(1) "3" }
  >\var_dump($redis->zRange('test',2,3,true));
  >\array(1) { [3]=> float(2) }
  > ```
  > **redis中所有range排序已移至此命令中,但是代码暂时不兼容**

  > zRevRange($key, $start, $end, $withscores = null)
  > - key 键名,string
  > - start 开始分数,string
  > - end 结束分数,string
  > - withscores 返回value->score数组,bool
  >
  > 返回值:array  
  > 从队尾开始(value排序)查询满足value条件的数组

  > zRangeByScore($key, $start, $end, $options = array)
  > - key 键名,string
  > - start 开始分数,int
  >>1 >=1开始
  >>(1 >1开始
  > - end 结束分数,int
  >>2 <=2结束
  >>(2 <2结束
  > - $options 额外参数,array
  > ```
  > withscores true 返回value->score数组
  > limit =>[
  >   offset,  偏移量
  >   count,  取出数量   
  > ]
  > ```
  >
  > 返回值:array  
  > 从队首开始(socre排序)查询满足socre条件的数组

  > zRevRangeByScore($key, $start, $end, $options = array)
  > 从队尾开始(socre排序)查询满足socre条件的数组

  > zRangeByLex($key, $min, $max, $offset = null, $limit = null)
  > - key 键名,string
  > - min 起始位置,int
  > - max 结束位置,int
  > ```
  > 必须包含'(' '[' '-' '+' 其中一种
  > ( 不包含此值
  > [ 包含此值
  > - 负无穷
  > + 正无穷
  > ```
  > - offset 偏移位置,int
  > - limit 获取数量,int
  >
  > 返回值:array | bool 
  > 以value判断,根据字典值排序返回符合条件成员

  > zRevRangeByLex($key, $min, $max, $offset = null, $limit = null)
  > 以value判断,根据字典值逆序返回符合条件成员**min,max和正向相反**

  > zRevRangeByScore($key, $start, $end, $options = array)
  > 从队尾开始(socre排序)查询满足socre条件的数组

  > zCard($key)
  > - key 键名,string
  >
  > 返回值:int
  > 返回key所有元素数量

  > zCount($key, $start, $end)
  > - key 键名,string
  > - min 起始位置,int
  > - max 结束位置,int
  > ```
  > ( 不包含此值 exp:$redis->zCount('test','(1','2')
  > -inf 负无穷
  > +inf 正无穷
  > ```
  >
  > 返回值:int
  > 获取满足条件成员数量

  > zIncrBy($key, $value, $member)
  > - key 键名,string
  > - value 增加分数值,int
  > - member 成员,int
  >
  > 返回值:float 返回最新值
  > 为指定成员增加分数

  > zUnionStore($output, $zSetKeys, array $weights = null, $aggregateFunction = 'SUM')
  > - output 转存的键名,string
  > - zSetKeys 要处理的key集合,array
  > - weights 分值权重,对应zsetkey分值会乘以此值,默认1,array
  > - aggregateFunction 取分值模式,string
  > ```
  > SUM 取分值的和
  > MIN 取分值最低
  > MAX 取分值最高
  > ```
  >```
  >
  >$redis->zAdd('test1',1,'a');
  >$redis->zAdd('test1',3,'b');
  >$redis->zAdd('test2',3,'a');
  >$redis->zAdd('test2',4,'b');
  >$redis->zAdd('test2',4,'c');
  >var_dump($redis->zUnionStore('test',['test1','test2'],[2,1],'min'));//2
  >['a'=>2,'b'=>4,'c'=>4]
  >
  >```
  > 返回值:int 转存集合数量
  > 为指定keys取并集后转存新key

  > zUnionStore($output, $zSetKeys, array $weights = null, $aggregateFunction = 'SUM')
  > 同上
  > 为指定keys取交集后转存新key

  > zPopMax($key, $count = 1)
  > - key 键名,string
  > - count 取出数量,int
  >
  > 返回值:array member=\>score
  > 从分值最大开始取并删除n个值

  > bzPopMax($key1, $key2, $timeout)
  > - key1  指定获取键名,string | array
  > - key2  指定获取键名,string | array
  > - timeout  阻塞时间(秒),int
  >```
  >建议使用array
  >$redis->bzPopMax(['test1','test2'],10);
  >```
  > 返回值:array 
  > ```
  > [0=>获取的key名 1=>获取的member 2=>获取的socre]
  > ```
  > 阻塞,从分值最大开始取并删除n个值

  > zPopMin($key, $count = 1)
  > 
  > 从分值最小开始取并删除n个值

  > bzPopMin($key1, $key2, $timeout)
  >
  > 阻塞,从分值最小开始取并删除n个值

+ geo-经纬度队列

  > geoadd($key, $longitude, $latitude, $member,...)
  > - key 键名,string
  > - longitude 经度,float
  > - latitude 维度,float
  > - member 地理标识名,float
  > - ... 根据3个一组放入元素可继续储存新的元素
  >
  > 返回值:int 储存成功的数量  
  > 储存经纬度及标识信息

  > geohash($key,...$member)
  > - key 键名,string
  > - ...member 地理标识名,string
  >
  > 返回值:array  
  > ```
  > Output: array(2) {
  >   [0]=>
  >   string(11) "87z9pyek3y0"
  >   [1]=>
  >   string(11) "8e8y6d5jps0"
  > }
  > ```
  > 根据地理标识获取geohash字符串

  > geopos($key,...$member)
  > - key 键名,string
  > - ...member 地理标识名,string
  >
  > 返回值:array  
  > ```
  > array(2) {
  >  [0]=> array(2) {
  >      [0]=> string(22) "-157.85800248384475708"
  >      [1]=> string(19) "21.3060004581273077"
  >  }
  >  [1]=> array(2) {
  >      [0]=> string(22) "-156.33099943399429321"
  >      [1]=> string(20) "20.79799924753607598"
  >  }
  > }
  > ```
  > 根据地理标识获取geohash经纬度信息

  > geodist($key, $member1, $member2, $unit = null)
  > - key 键名,string
  > - member1 地理标识名1,string
  > - member2 地理标识名2,string
  > - unit 距离标识,默认米,string
  >```
  >m  米
  >km 公里
  >mi 英里
  >ft 英尺
  >```
  > 返回值:float  
  > 返回两个成员之间的距离

  > georadius($key, $longitude, $latitude, $radius, $unit, array $options = null)
  > - key 键名,string
  > - longitude 经度,string
  > - latitude 维度,string
  > - radius 半径,默认米,string
  > - unit 距离标识,string
  >```
  >m  米
  >km 公里
  >mi 英里
  >ft 英尺
  >```
  > - options 额外配置,string
  >```
  >COUNT 限制返回成员数量,int >0
  >WITHCOORD  返回匹配成员的经纬度
  >WITHDIST  返回匹配成员到中心的距离
  >WITHHASH   返回匹配成员的geohash编码
  >ASC  以最近为排序返回结果
  >DESC 以最远为排序返回结果
  >STORE  将获取的成员及经纬度存在新键中,和with系列互斥,返回写入成员数量
  >STOREDIST  将获取的成员和目标的距离储存在新键中,和with系列互斥,返回写入成员数量
  >```
  >```
  >
  >$options = [
  >'WITHCOORD',
  >'WITHDIST',
  >'WITHHASH',
  >'ASC',
  >];
  >var_dump($redis->geoRadius("hawaii", -157.858, 21.306, 300, 'km', $options));
  >
  >array(2) {
  >[0]=>
  >array(4) {
  >[0]=>
  >string(8) "Honolulu"
  >[1]=>
  >string(6) "0.0003"
  >[2]=>
  >int(2147483647)
  >[3]=>
  >array(2) {
  >[0]=>
  >string(22) "-157.85800248384475708"
  >[1]=>
  >string(19) "21.3060004581273077"
  >}
  >}
  >[1]=>
  >array(4) {
  >[0]=>
  >string(4) "Maui"
  >[1]=>
  >string(8) "168.2749"
  >[2]=>
  >int(2147483647)
  >[3]=>
  >array(2) {
  >[0]=>
  >string(22) "-156.33099943399429321"
  >[1]=>
  >string(20) "20.79799924753607598"
  >}
  >}
  >}
  >```
  > 返回值:mixed  
  > 以经纬度原点,radius为半径获取满足条件的成员信息

  > georadiusbymember($key, $member, $radius, $units, array $options = null)
  > 
  > 参考georadius  
  > 返回值:mixed  
  > 以其中一个成员原点,radius为半径获取满足条件的成员信息

+ 流队列**暂无更多,建议使用其他软件**

  > xAdd($key, $id, $messages, $maxLen = 0, $isApproximate = false)
  > - key 键名,string
  > - id 流名,传入*则系统自动生成,string
  > - message 储存的键值对信息,array
  > - maxlen 流中最大储存信息数量,超过了会移除旧的(测试php调用时并不会,命令台会),int
  > - isApproximate 效果未知,bool
  >
  > 返回值:string 当前流名
  > ```
  > $redis->xAdd('test', "*", ['333' => '333'], 5, true);
  > ```
  > 为流添加指定条目


+ 特殊命令-迭代扫描

  > scan(&$iterator, $pattern = null, $count = 0)
  > sScan|hScan|zScan($key,&$iterator, $pattern = null, $count = 0)
  > - key 键名,string
  > - iterator 游标位置,int
  > - pattern 匹配规则,string
  > - int 匹配数量,int
  >```
  >$redis->sAdd('test', '1a','2b','3c','4d','5e','6f','1b');
  >$iterator = null;
  >var_dump($redis->sScan('test', $iterator,'1*',1));
  >array(0) {}
  >var_dump($iterator);
  >int(2)
  >var_dump($redis->sScan('test', $iterator,'1*',1));
  >array(0) {}
  >var_dump($iterator);
  >int(6)
  >var_dump($redis->sScan('test', $iterator,'1*',1));
  >array(0) {}
  >var_dump($iterator);
  >int(5)
  >var_dump($redis->sScan('test', $iterator,'1*',1));
  >array(1) {[0]=>string(2) "1a"}
  >var_dump($iterator);
  >int(7)
  >var_dump($redis->sScan('test', $iterator,'1*',1));
  >array(1) {[0]=>string(2) "1b"}
  >var_dump($iterator);
  >int(0)
  >
  >官方exp:
  >
  > $iterator = null;
  > while(false !== ($keys = $redis->scan($iterator))) {
  >     foreach($keys as $key) {
  >         echo $key . PHP_EOL;
  >     }
  > }
  >
  > $iterator = null;
  > while ($members = $redis->sScan('set', $iterator)) {
  >     foreach ($members as $member) {
  >         echo $member . PHP_EOL;
  >     }
  > }
  >```
  > 返回值:array|false  
  > 扫描符合条件的所有键,并返回键名
  > ```
  > 特殊要点:
  > 1.iterator,首次扫描传入0代表新的迭代,每次调用时会返回一个标记值,为0时代表扫描结束
  > 2.pattern,匹配规则,全匹配为null|*,有匹配规则时且count较小时,redis可能会返回空数组,并不代表已扫描完毕,需要使用iterator来判断
  > 3.count,默认会返回10个,redis不保证每次迭代所返回的元素数量,大部分情况都有效,会返回>=count值的数量
  > 4.count,在编码匹配为一些特殊集合时,不会生效,会在第一次就返回所有元素,exp:整数集合
  > ```


+ 订阅/发布

  > sScan($key, &$iterator, $pattern = null, $count = 0)
  > - channels 订阅频道,array
  > - callback 函数名,string | array  
      > exp:$redis->subscribe(array('test'), [\App\Controller\WhatsApp\TestController::class, 'callback']);  
      > 其中callback为static函数
      > 成功后会进入长阻塞监听频道的发布信息  
      > static function callback($instance, $channelName, $message)  
      > --instance redis服务
      > --channelName 订阅的频道名称
      > -- message 订阅的频道发布的消息
  >
  > 订阅频道

  > psubscribe($patterns,$callback)
  > - $patterns 模式名称,array
  > - callback 函数名,string | array  
      > exp:$redis->psubscribe(array('test'), [\App\Controller\WhatsApp\TestController::class, 'callback']);  
      > 其中callback为static函数
      > 成功后会进入长阻塞监听频道的发布信息  
      > static function callback($instance, $pattern,$channelName, $message)  
      > --instance redis服务
      > --pattern 模式名称
      > --channelName 订阅的频道名称
      > -- message 订阅的频道发布的消息
  >
  > 按模式订阅频道

  > publish($channel,$message)
  > - $channel 推送的频道名称,string
  > - $message 推送的信息,string
  >
  > 推送频道订阅信息

  > pubsub($keyword,$argument)
  > - $keyword 关键词,string
  > - $argument 参数,string | array
  >```
  >$redis->pubsub('channels') 显示所有频道
  >$redis->pubsub('channels', '*pattern*') 显示父类下所有频道
  >$redis->pubsub('numsub', array('chan1', 'chan2')) 显示频道用户数量
  >$redis->pubsub('numpat') 显示频道用户数量
  >```
  >
  > 推送频道订阅信息

  > unsubscribe($channels = null)
  > - $channels 关键词,array
  >
  > 停止收听频道订阅

  > punsubscribe($channels = null)
  > - $channels 关键词,array
  >
  > 停止收听类型频道订阅


+ 其他非php服务器命令
  ### ACL指令 为redis配置服务器账户权限 版本最低可用:6.0.0
  > ACL LOAD  
  > 当Redis配置为使用ACL文件（带有aclfile配置选项）时，此命令将从文件中重新加载ACL，将所有当前ACL规则替换为文件中定义的规则。该命令确保行为为全有或全无，即：  
  > 如果文件中的每一行都有效，则将加载所有ACL。  
  > 如果文件中的一行或多行无效，则不会加载任何内容，并且继续使用服务器内存中定义的旧ACL规则。

  > ACL SAVE  
  > 当Redis配置为使用ACL文件（带有aclfile配置选项）时，此命令会将当前定义的ACL从服务器内存保存到ACL文件。

  > ACL LIST  
  > 查看acl用户配置列表。

  > ACL USERS  
  > 查看acl用户名列表。

  > ACL GETUSER username  
  > 查看指定用户权限。

  > ACL SETUSER username [rule [rule ...]]   
  > 设置指定用户权限。

  > ACL DELUSER username [username ...]  
  > 删除指定用户。

  > ACL ACL CAT [categoryname]  
  > 查看acl操作类别(查看此类别能运行哪些redis指令)。

  > ACL ACL GENPASS [bits]   
  > 生成一个可靠密码。

  > ACL WHOAMI   
  > 返回当前连接经过验证的用户名。

  > ACL LOG [count or RESET]   
  > 1.无法通过AUTH或HELLO验证其连接。  
  > 2.命令被拒绝，因为违反了当前的ACL规则。  
  > 3.命令被拒绝，因为访问当前ACL规则中不允许的键。  
  > 返回日志列表。

  > ACL HELP
  > 返回子命令及其说明的列表。  