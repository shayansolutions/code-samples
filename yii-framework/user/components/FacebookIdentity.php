<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class FacebookIdentity extends CUserIdentity
{
	private $_id;
        private $_facebookId;
	const ERROR_EMAIL_INVALID=3;
	const ERROR_STATUS_NOTACTIV=4;
	const ERROR_STATUS_BAN=5;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
                $this->_facebookId = $this->password;
                $user=User::model()->notsafe()->findByAttributes(array('email'=>$this->username,'fb_userid'=>$this->password));

                if($user===null)
                {
			if (strpos($this->username, "@"))
                        {
                            $this->errorCode = self::ERROR_EMAIL_INVALID;
                        }
                }
		else if($user->status==-1)
			$this->errorCode=self::ERROR_STATUS_BAN;
		else {
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
    
    /**
    * @return integer the ID of the user record
    */
	public function getId()
	{
	return $this->_id;
	}
        
        public function getFacebook()
        {
            return $this->_facebookId;
        }
}