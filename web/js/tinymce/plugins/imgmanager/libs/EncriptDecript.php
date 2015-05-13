<?php

/**
 * @name EncriptDecript
 * @copyright Darius Matulionis
 * @author Darius Matulionis <darius@matulionis.lt>
 * @since : 2012-03-02, 15.13.27
 */
class EncriptDecript{
    protected $salt = "098j*#YDJ_)#)@D)(FFM)JFN@*(_(h8(#@DFJ@_kjd20-f92fy23039djm";
 
    /**
     * Get Encripted string
     * @param String $string
     * @return String
     */
    public function encript($string){
        return  base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->salt), $string, MCRYPT_MODE_CBC, md5(md5($this->salt))));
    }
    
    /**
     * Get Decripted string
     * @param String $encrypted Encripted string
     * @return String 
     */
    public function decript($encrypted){
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->salt), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($this->salt))), "\0");
    }
}
