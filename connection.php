<!-- connection.php
     Practicing PHP and Database

     Revision History
        Jaden Ahn + Aubrey Delong, 2017.07.29: Created
		Jaden Ahn + Aubrey Delong, 2017.08.01: Added $_SERVER["DOCUMENT_ROOT"] to $dbName to make the file path relative
-->
<?php

	function connectToDatabase()
	{
		$dbName = $_SERVER["DOCUMENT_ROOT"] . "\\database\\assignment5.mdb";
		//Check file exist before we proceed
		if (!file_exists($dbName))
		{
			die("Access database file not found !");
		}

		//create a new PDO object
		$connection = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName;");
		$connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $connection;
	}

?>



