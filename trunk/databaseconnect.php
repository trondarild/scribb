<?php

	//
	//Database connection class
	//
	class DatabaseConnection
	{
		//constructor
		function DatabaseConnection()
		{
			//initialize variables

			$this->mHost = "localhost";
			$this->mDatabaseName = "scribb_com";
			$this->mUserName = "scribb_com";
			$this->mPassword = "KFHknDE3";

			$this->mIsConnected = false;

		}

		//get

		//get datbase connection
		function getConnection()
		{
			if($this->mIsConnected==false)
				$this->connect();

			return $this->mConnection;
		}


		//set

		//is

		//other

		//connect to datbase
		function connect()
		{
			$this->mConnection = mysql_connect($this->mHost, $this->mUserName, $this->mPassword);
			mysql_select_db($this->mDatabaseName, $this->mConnection);
			$this->mIsConnected = true;

		}

		//member variables
		var $mHost;
		var $mDatabaseName;
		var $mUserName;
		var $mPassword;
		var $mConnection;
		var $mIsConnected;

}


?>
