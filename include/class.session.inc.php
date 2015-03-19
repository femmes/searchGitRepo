<?php

class Session{
	private $pdo;

	public function __construct(){
		$this->pdo = new Database;
		// Set handler to overide SESSION
		session_set_save_handler(
			array($this, "_open"),
			array($this, "_close"),
			array($this, "_read"),
			array($this, "_write"),
			array($this, "_destroy"),
			array($this, "_gc")
		);
		
		session_start();
	}

	public function _open(){
		if($this->pdo){
			$info['status']=1;
			$info['personalMsg']="Db connection open";
			$info['success']=true;
			return $info;
		}else {
			$info['status']=0;
			$info['personalMsg']="Db connection was not openned";
			$info['success']=false;
			return $info;
		}
	}

	public function _close(){
		if($this->pdo->close()){
			$info['status']=1;
			$info['personalMsg']="Db connection was closed";
			$info['success']=true;
			return $info;
		}else{
			$info['status']=0;
			$info['personalMsg']="Db connection open";
			$info['success']=false;
			return $info;
		}
	}

	public function _read($sessionid){
		$stmt = $this->pdo->dbh->prepare("SELECT `data` FROM `sessions` WHERE `id`=:sessionid");
		$stmt->bindParam(':sessionid',$sessionid,PDO::PARAM_STR);
		try{
			$stmt->execute();
			$count=$stmt->rowCount();
			if($count===0 || $count===NULL){
				$info['status']=1;
				$info['personalMsg']="Session Does Not Exist";
				$info['count']=$count;
				return $info;
			}else{
				$result=$stmt->fetch(PDO::FETCH_ASSOC);
				$data=$result['data'];
				$info['status']=1;
				$info['personalMsg']="Session Exist";
				$info['parameter']=$sessionid;
				$info['data']=$data;
				return $info;
			}
		}catch(PDOException $e){
			$info['status']=0;
			$info['code']=$e->getCode();
			$info['msg']=$e->getMessage();
			$info['personalMsg']="Could Not Read Session Data";
			return $info;
		}
	}

	public function _write($sessionid, $data){
		$access = time();
		$stmt = $this->pdo->dbh->prepare("REPLACE INTO `sessions` VALUES (:sessionid, :access, :data)");
		$stmt->bindParam(':sessionid',$sessionid,PDO::PARAM_STR);
		$stmt->bindParam(':access',$access,PDO::PARAM_STR);
		$stmt->bindParam(':data',$data,PDO::PARAM_STR);
		try{
			$stmt->execute();
			$info['status']=1;
			$info['personalMsg']="Session written to db";
			$info['success']=true;
			return $info;
		}catch(PDOException $e){
			$info['status']=0;
			$info['code']=$e->getCode();
			$info['msg']=$e->getMessage();
			$info['personalMsg']="Session was not written to db";
			$info['success']=false;
			return $info;
		}
	}

	public function _destroy($sessionid){
		$stmt = $this->pdo->dbh->prepare("DELETE FROM `sessions` WHERE `id` = :sessionid");
		$stmt->bindParam(':sessionid',$sessionid,PDO::PARAM_STR);
		try{
			$stmt->execute();
			if($stmt->rowCount()===1){
				$info['status']=1;
				$info['personalMsg']="Session deleted from db";
				$info['success']=true;
				$info['sessionid']=$sessionid;
				$info['affectedRows']=$stmt->rowCount();
				return $info;
			}else{
				$info['status']=1;
				$info['personalMsg']="Session was not deleted";
				$info['success']=false;
				$info['sessionid']=$sessionid;
				$info['affectedRows']=$stmt->rowCount();
				return $info;
			}
		}catch(PDOException $e){
			$info['status']=0;
			$info['code']=$e->getCode();
			$info['msg']=$e->getMessage();
			$info['personalMsg']="Session was not deleted from db";
			$info['success']=false;
			echo json_encode($info);
			return $info;
		}
	}

	public function _gc($max){
		// Calculate what is to be deemed old
		$old = time() - $max;
		$stmt = $this->pdo->dbh->prepare("DELETE * FROM sessions WHERE access < :old");
		$stmt->bindParam(':old',$old,PDO::PARAM_STR);
		try{
			$stmt->execute();
			$info['status']=1;
			$info['personalMsg']="Old sessions deleted from db";
			$info['success']=true;
			return $info;
		}catch(PDOException $e){
			$info['status']=0;
			$info['code']=$e->getCode();
			$info['msg']=$e->getMessage();
			$info['personalMsg']="Old sessions were not deleted from db";
			$info['success']=false;
			return $info;
		}
	}
}

?>