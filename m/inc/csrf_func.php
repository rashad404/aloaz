<?php 
    function makeToken()
    {
		$_SESSION['csrf_token'] = md5(uniqid(rand(), true));
        return $_SESSION['csrf_token'];
    }

	function isTokenValid()
    {
         return $_POST['csrf_token'] === $_SESSION['csrf_token'];
    }
	
	function updateToken()
	{
		$_SESSION['csrf_token'] =  md5(uniqid(rand(), true));
	}
	
	
	function getToken()
	{
		return $_SESSION['csrf_token'];
	}

?>