<?php

namespace sr1871\ftptransfer; 

/** 
 * ftptransfer module definition class 
 */ 
class FtpTransfer extends \yii\base\Module { 
    public $ip;
    public $user;
    public $password;
    public $port;
    public $server;
    public $_conn;
    public $pasive = false;
    
    /** 
     * @inheritdoc 
     */ 
    
    /** 
     * @inheritdoc 
     */ 
    public function init() { 
        parent::init(); 

        $this->_conn = ftp_connect($this->server) or die("Couldn't connect to $ftp_server");         // custom initialization code goes here 
        if (@ftp_login($this->_conn, $this->user, $this->password)) {
            @ftp_pasv($this->_conn, $this->pasive);
        } 

       
    } 
        
    public function _close() {
        @ftp_close($this->_conn);
    }

    public function getFile($remotePath, $localPath) {
        $result = @ftp_nb_get($this->_conn, $localPath, $remotePath , FTP_BINARY);
        ftp_close($this->_conn);
        return $result;

    }

    public function put($remotePath, $localPath) {
        $result = @ftp_nb_put($this->_conn, $remotePath, $localPath, FTP_BINARY);
        ftp_close($this->_conn);
        return $result;
    }

    public function mkdir($directory, $permissions=777) {
        $result = @ftp_mkdir($this->_conn, $directory);
        if ($result != 0) {
            $this->changePermissions($permissions, $directory);
        }
        ftp_close($this->_conn);
        return $result;
    }

    public function delete($remotePath) {
        $result = @ftp_delete($this->_conn, $remotePath);
        ftp_close($this->_conn);
        return $result;
    }

    private function chmod($permissions, $file) {
        $result = @ftp_chmod($this->_conn, $permissions, $file);
        ftp_close($this->_conn);
        return $result;
    }

    public function rmdir($directory) {
        $result = @ftp_rmdir($this->_conn, $directory);
        ftp_close($this->_conn);
        return $result;
    }

    public function changePermissions($permissions, $file) {
        $this->chmod($permissions,$file);
    }
} 