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
  > 设置客户选项

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

  > del($key, ...$otherkeys)
  > - key 删除的键名 int|string|array 可传入数组,效果同可变传参
  > - ...otherkeys 删除的其他键 可变传参
  >
  > 返回值:int 删除数量  
  > 删除指定键

  > unlink($key, ...$otherkeys)
  > - key 删除的键名 int|string|array 可传入数组,效果同可变传参
  > - ...otherkeys 删除的其他键 可变传参
  >
  > 返回值:int 删除数量  
  > 异步删除指定键

  > multi($mode = Redis::MULTI)
  > - mode 默认MULTI  
  >  - Redis::MULTI 保证原子性
  >  - Redis::PIPELINE 更快完成操作.不保证原子性
  >
  > > $res=$redis->multi()  
  ->set('abc', 'abc')  
  ->get('abc')  
  ->set('abc', '222')  
  ->get('abc')  
  ->exec();  
  > > var_dump($res)  
  > > array(4) { [0]=> bool(true) [1]=> string(3) "abc" [2]=> bool(true) [3]=> string(3) "222" }
  >
  > 只有使用了exec之后才会执行  
  > 返回值:array 每一步完成结果  
  > 进入交易模式.原子性处理

  > pipeline()
  > - mode 默认MULTI
      >  - Redis::MULTI 保证原子性
  >  - Redis::PIPELINE 更快完成操作.不保证原子性
  >
  > >  $res=$redis->pipeline()
  ->ping()
  ->multi()
  ->set('x', 42)
  ->incr('x')
  ->exec()
  ->ping()
  ->multi()
  ->get('x')
  ->del('x')
  ->exec()
  ->ping()
  ->exec();
  > > var_dump($res)  
  > > array(5) { [0]=> bool(true) [1]=> array(2) { [0]=> bool(true) [1]=> int(43) } [2]=> bool(true) [3]=> array(2) { [0]=> string(2) "43" [1]=> int(1) } [4]=> bool(true) }
  >
  > 返回值:array 每一步完成结果  
  > 进入管道模式,更快的完成redis命令(一次请求) 每个命令都会返回redis实例,

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
  
+ 其他非php服务器命令
  ###ACL指令 为redis配置服务器账户权限 版本最低可用:6.0.0
  > ACL LOAD  
  > 当Redis配置为使用ACL文件（带有aclfile配置选项）时，此命令将从文件中重新加载ACL，将所有当前ACL规则替换为文件中定义的规则。该命令确保行为为全有或全无，即：  
  > 如果文件中的每一行都有效，则将加载所有ACL。  
  > 如果文件中的一行或多行无效，则不会加载任何内容，并且继续使用服务器内存中定义的旧ACL规则。  
  
  >  ACL SAVE  
  > 当Redis配置为使用ACL文件（带有aclfile配置选项）时，此命令会将当前定义的ACL从服务器内存保存到ACL文件。

  >  ACL LIST  
  > 查看acl用户配置列表。  
  
  >  ACL USERS  
  > 查看acl用户名列表。

  >  ACL GETUSER username  
  > 查看指定用户权限。

  > ACL SETUSER username [rule [rule ...]]   
  > 设置指定用户权限。

  > ACL DELUSER username [username ...]  
  > 删除指定用户。  