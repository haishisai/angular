<?php
//header('content-type:text/html;charset=utf-8');
require_once 'db_config.php';
final class PdoMySQL{
	public static $config=array();	//设置连接参数，配置参数
	public static $link=null;	//保存连接标识符
	public static $pconnect=false;	//是否开启长连接
	public static $dbVersion=null;	//保存数据库版本
	public static $connected=false;	//判断是否连接成功
	public static $PDOStatement=null;	//保存PDOStatement对象
	public static $queryStr=null;	//保存最后执行的操作
	public static $error=null;	//保存错误信息
	// public static $lastInsertId=0;	//保存上一步插入操作的id号
	public static $numRows=0;	//上一步操作产生受影响的记录的条数

	public function __construct($dbConfig=''){
		if(!class_exists("PDO")){
			self::throw_exception('服务器不支持PDO，请先开启。');
			return false;
		}
		if(!is_array($dbConfig)){
			$dbConfig=array(
				'hostname'=>DB_HOST,
				'username'=>DB_USER,
				'password'=>DB_PWD,
				'database'=>DB_NAME,
				'hostport'=>DB_PORT,
				'webName'=>WEB_NAME,
				'dbms'=>DB_TYPE,
				'dsn'=>DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME
			);
		}
		if(empty($dbConfig['hostname'])){
			self::throw_exception('没有定义数据库配置。');
			return false;
		};
		self::$config=$dbConfig;
		if(empty(self::$config['params']))self::$config['params']=array();
		if(!isset(self::$link)){
			$configs=self::$config;
			if(self::$pconnect){
				//开启长连接，添加配置数组中
				$configs['params'][constant("PDO::ATTR_PERSISTENT")]=true;
			}
			try{
				self::$link=new PDO($configs['dsn'],$configs['username'],$configs['password'],$configs['params']);
			}catch(PDOException $e){
				self::throw_exception($e->getMessage());
				return false;
			}
			if(!self::$link){
				self::throw_exception('PDO连接错误');
				return false;
			}
			self::$link->exec('SET NAMES '.DB_CHARSET);
			self::$dbVersion=self::$link->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
			self::$connected=true;
			unset($configs);
		}
	}

	//得到所有记录
	public static function getAll($sql=null){
		if($sql!==null){
			self::query($sql);
		}
		$result=self::$PDOStatement->fetchAll(constant("PDO::FETCH_ASSOC"));
		return $result;
	}

	//得到一条记录
	public static function getOne($sql=null){
		if($sql!==null){
			self::query($sql);
		}
		$result=self::$PDOStatement->fetch(constant("PDO::FETCH_ASSOC"));
		return $result;
	}

	//执行增删改，返回受影响的记录的条数
	public static function execute($sql=null){
		$link=self::$link;
		if(!$link) return false;
		self::$queryStr=$sql;
		if(!empty(self::$PDOStatement))self::free();
		$result=$link->exec(self::$queryStr);
		self::haveErrorThrowException();
		if($result){
			// self::$lastInsertId=$link->lastInsertId();
			self::$numRows=$result;
			return self::$numRows;
		}else{
			return false;
		}
	}

	//返回最后一条sql语句
	public static function getLastSql(){
		$link=self::$link;
		if(!$link)return false;
		return self::$queryStr;
	}

	//释放结果集
	public static function free(){
		self::$PDOStatement=null;
	}

	public static function query($sql=''){
		$link=self::$link;
		if(!$link) return false;
		//判断之前是否有结果集，如果有的话，释放结果集
		if(!empty(self::$PDOStatement))self::free();
		self::$queryStr=$sql;
		self::$PDOStatement=$link->prepare(self::$queryStr);
		$res=self::$PDOStatement->execute();
		self::haveErrorThrowException();
		return $res;
	}

	public static function haveErrorThrowException(){
		$obj=empty(self::$PDOStatement)?self::$link: self::$PDOStatement;
		$arrError=$obj->errorInfo();
		//print_r($arrError);
		if($arrError[0]!=='00000'){
			self::$error='<br>错误 → '.$arrError[0].'<br/>反馈 → '.$arrError[2].'<br/> 语句 → '.self::$queryStr;
			self::throw_exception(self::$error);
			return false;
		}
		if(self::$queryStr==''){
			self::throw_exception('没有执行SQL语句');
			return false;
		}
	}

	//自定义错误处理
	public static function throw_exception($errorMsg){
		echo '<style>body{background:#dedede;}</style><div style="margin:40px auto;width:50%;padding:10px 20px;border:2px solid #e77171;color:#6b6b6b;line-height:30px;">站点 → '.WEB_NAME.$errorMsg.'</div>';
	}

	//销毁连接对象，关闭数据库
	public static function close(){
		self::$link=null;
	}
}