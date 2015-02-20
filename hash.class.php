<?php
error_reporting(0);

class hash
{
	var $level;
	var $check;
	var $charset;
    
    // uses sha512 by default
	function hashit($input)	{
		return hash('sha512', $input);
	}
    
	// generate 5 character salt/pepper
	function condiment() {
		// use default charset if left unset
		if (empty($this->charset))
			$this->charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()`~,./<>?;:\'"[]{}\\|-=_+';
		$csl = strlen($this->charset);
		for ($i = 1; $i <= 5; $i++)
			$return .= substr($this->charset, rand(0, $csl), 1);
		return $return;
	}
    
	// hash a password / check a password
	function hash($password, $salt, $pepper) {
		if (!empty($this->level)) {
			if (is_numeric($this->level) && $this->level <= 3 && $this->level >= 1) {
				switch((int) $this->level) {
					case 1:
						$hash = $this->hashit($password);
						return array($hash, null, null);
						break;
					case 2:
						if (!$this->check)
							$salt = $this->condiment();
						$hash = $this->hashit($this->hashit($password) . $this->hashit($salt));
						return array($hash, $salt, null);
						break;
					case 3:
						if (!$this->check) {
							$salt = $this->condiment();
							$pepper = $this->condiment();
						}
						$hash = $this->hashit($this->hashit($pepper) . $this->hashit($password) . $this->hashit($salt));
						return array($hash, $salt, $pepper);
						break;
					default:
						return 'Error: Level unknown.';
						break;
				}
			} else
				return 'Error: Level is invalid.';
		} else
			return 'Error: Level was left unset.';
	}
}
?>
