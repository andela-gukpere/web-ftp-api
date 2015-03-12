<?php
	class FTPClient
	{
		private $connectionId;
		private $loginOk = false;
		private $messageArray = array();


		public function __construct() {	}

		private function logMessage($message, $clear=true) 
		{
			if ($clear) {$this->messageArray = array();}

			$this->messageArray[] = $message;
		}
		public function getMessages()
		{
			return $this->messageArray;
		}
	
		public function connect ($server, $ftpUser, $ftpPassword, $isPassive = false)
		{

			$this->connectionId = @ftp_connect($server);
			//ftp_set_option($this->connectionId,FTP_TIMEOUT_SEC,200);
			$loginResult = @ftp_login($this->connectionId, $ftpUser, $ftpPassword);
			@ftp_pasv($this->connectionId, $isPassive);
			if ((!$this->connectionId) || (!$loginResult)) {
				$this->logMessage('FTP connection has failed!');
				$this->logMessage('Attempted to connect to ' . $server . ' for user ' . $ftpUser, true);
				return false;
			} else {
				$this->logMessage('Connected to ' . $server . ', for user ' . $ftpUser);
				$this->loginOk = true;
				return true;
			}
		}

		public function makeDir($directory)
		{
			if (ftp_mkdir($this->connectionId, $directory)) {

				$this->logMessage('Directory "' . $directory . '" created successfully');
				return true;

			} else {
				$this->logMessage('Failed creating directory "' . $directory . '"');
				return false;
			}
		}
		public function uploadFile ($fileFrom, $fileTo)
		{
			$asciiArray = array('txt', 'csv');
			$extension = end(explode('.', $fileTo));
			if (in_array($extension, $asciiArray)) {
				$mode = FTP_ASCII;		
			} else {
				$mode = FTP_BINARY;
			}
			$upload = ftp_put($this->connectionId,$fileTo, $fileFrom, $mode);
			if (!$upload) {

					//$this->logMessage('FTP upload has failed!');
					return false;

				} else {
					//$this->logMessage('Uploaded "' . $fileFrom . '" as "' . $fileTo);
					return true;
				}
		}

		public function changeDir($directory)
		{
			if (@ftp_chdir($this->connectionId, $directory)) {
				$this->logMessage('Current directory is now: ' . ftp_pwd($this->connectionId));
				return true;
			} else { 
				$this->logMessage('Couldn\'t change directory');
				return false;
			}
		}
		public function getDirListing($directory = '.', $parameters = '-la')
		{			
			$idir=$directory;$isch=false;
			if(stripos($directory,"/..")+3==strlen($directory)||stripos($directory,"/../")+4==strlen($directory))
			{
				$directory = substr($directory,0,stripos($directory,"/.."));
				ftp_chdir($this->connectionId,$directory);
				if(@ftp_cdup($this->connectionId))
				{
					$isch=true;
					if(@ftp_cdup($this->connectionId))$directory=ftp_pwd($this->connectionId);
					else $directory=ftp_pwd($this->connectionId);
				}
				else $directory=ftp_pwd($this->connectionId);
				if(stripos($directory,"/")+1!=strlen($directory))$directory=$directory."/";
			}
			if(stripos($directory,"/.")+2==strlen($directory)||stripos($directory,"/./")+3==strlen($directory))
			{
				$directory = substr($directory,0,stripos($directory,"/."));
				ftp_chdir($this->connectionId,$directory);
				$isch==true;
				if(@ftp_cdup($this->connectionId))$directory=ftp_pwd($this->connectionId);	
				if(stripos($directory,"/")+1!=strlen($directory))$directory=$directory."/";
			}
			ftp_chdir($this->connectionId,$directory);
			$contentsArray = ftp_nlist($this->connectionId,$directory);
			$fr = array();
			foreach($contentsArray as $i)
			{
				$size=ftp_size($this->connectionId,$i);
				array_push($fr,array($i,$size));
			}
			
			if($isch && $idir==$directory)
			return false;
			else return array($directory,$fr);
		}
		public function go_up($dir)
		{
			ftp_chdir($this->connectionId,$dir);
			ftp_cdup($this->connectionId);	
		}
		public function delF($file)
		{
			$res=false;
			$size=ftp_size($this->connectionId,$file);
			if($size<0)
			$res=ftp_rmdir($this->connectionId,$file);
			else $res=ftp_delete($this->connectionId,$file);	
			return $res;
		}
		public function renm($file,$nfile)
		{
			$res = ftp_rename($this->connectionId,$file,$nfile);
			return $res;	
		}
		public function downloadFile ($fileFrom, $fileTo)
		{
			$asciiArray = array('txt', 'csv','js','json','php','xml','xlst','ini','sql','html','rb','pl','py','css','xhtml','shtml','htm','asp','aspx','c','java','jad','tpl','cs','vb');
			$extension = end(explode('.', $fileFrom));
			if (in_array($extension, $asciiArray)) {
				$mode = FTP_ASCII;		
			} else {
				$mode = FTP_BINARY;
			}

			// open some file to write to
			$handle = fopen($fileTo, 'w');

			if (@ftp_get($this->connectionId, $fileTo, $fileFrom, $mode, 0)) {

				return $handle;
				$this->logMessage(' file "' . $fileTo . '" successfully downloaded');
			} else {

				return false;
				$this->logMessage('There was an error downloading file "' . $fileFrom . '" to "' . $fileTo . '"');
			}

		}
	
		## --------------------------------------------------------
		
		# Step 11
		public function __deconstruct()
		{
			if ($this->connectionId) {
				ftp_close($this->connectionId);
			}
		}
		
		## --------------------------------------------------------
					
	}
	function retjson($obj,$type,$todo='',$status=200)
	{//todo:function()".'{'.$todo.'}'.",
		return('{"status":"'.$status.'","obj":'.$obj.',"do":"'.$type.'"}');	
	}
?>
