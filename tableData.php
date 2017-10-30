<!-- tableData.php
     Practicing PHP and Database

     Revision History
        Jaden Ahn + Aubrey Delong, 2017.07.29: Created
		Jaden Ahn + Aubrey Delong, 2017.08.03: Fixed bug about data which has single quote
											   In fillTable(), $params is added to execute() for security purpose
		Jaden Ahn + Aubrey Delong, 2017.08.08: Added $num_count to count total number of records after querying
		Jaden Ahn + Aubrey Delong, 2017.08.11: Applied bindParam to addTable()
-->
<?php

	include("connection.php");

	function getVendorNumber()
	{
		$vendorNo = array();
		$connection = connectToDatabase();
		$querySelect = "SELECT VendorNo FROM Vendors";
		$preparedQuerySelect = $connection -> prepare($querySelect);
		$preparedQuerySelect -> execute();
		while ($row = $preparedQuerySelect -> fetch())
		{
			$vendorNo[] = $row['VendorNo'];
		}
		return $vendorNo;
	}

	function fillTable($table, $fields, $operator, $query)
	{
		$query = trim(htmlspecialchars($query));
		$tableBodyText = "";
		$isNumeric = true;
		$connection = connectToDatabase();
		if ($fields == Null || $operator == Null || $query == Null)
		{
			$queryCondition = '';
		}
		else
		{
			if ($operator == "LIKE")
			{
				$query = "%$query%";
				$isNumeric = false;
			}
			else if ($operator == "NOT")
			{
				$operator = "<>";
				$query = "$query";
				$isNumeric = false;
			}
			else if ($operator == "SAME")
			{
				$operator = "=";
				$query = "$query";
				$isNumeric = false;
			}

			if ($isNumeric)
			//When $operator is about number, check if $query is numeric or not
			{
				if (!is_numeric($query))
				{
                    $errorMessage[] = "* Query Format doesn't match.<br>";
                    $_SESSION["parameterErrorMessage"] = $errorMessage;
                    header("Location: index.php");
                    exit;
				}
			}

			$queryCondition = "WHERE $fields $operator ?";
		}
		
		$querySelect = "SELECT * FROM $table $queryCondition";
		$preparedQuerySelect = $connection -> prepare($querySelect);
		
		//Query parameter value(s)
		$params = array($query);
		$preparedQuerySelect -> execute($params);
		$num_count = 0;
		$tableBodyText .= "<fieldset>";

		if ($table == 'Parts')
		{
			$tableBodyText .= "<legend><h2>Parts Information</h2></legend>";
			$tableBodyText .= "<table class='list'>";
			$tableBodyText .= "<tr id='tableHeader'>";
			$tableBodyText .= "<th>PartID</th>";
			$tableBodyText .= "<th>VendorNo</th>";
			$tableBodyText .= "<th>Description</th>";
			$tableBodyText .= "<th>OnHand</th>";
			$tableBodyText .= "<th>OnOrder</th>";
			$tableBodyText .= "<th>Cost</th>";
			$tableBodyText .= "<th>ListPrice</th>";
			$tableBodyText .= "</tr>";
			while ($row = $preparedQuerySelect -> fetch())
			{
				$partID = $row['PartID'];
				$vendorNo = $row['VendorNo'];
				$description = $row['Description'];
				$onHand = $row['OnHand'];
				$onOrder = $row['OnOrder'];
				$cost = $row['Cost'];
				$listPrice = $row['ListPrice'];

				$tableBodyText .= "<tr>";
				$tableBodyText .= "<td>$partID</td>";
				$tableBodyText .= "<td>$vendorNo</td>";
				$tableBodyText .= "<td>$description</td>";
				$tableBodyText .= "<td>$onHand</td>";
				$tableBodyText .= "<td>$onOrder</td>";
				$tableBodyText .= "<td>$cost</td>";
				$tableBodyText .= "<td>$listPrice</td>";
				$tableBodyText .= "</tr>";
				
				$num_count++;
			}
		}
		else
		{
			$tableBodyText .= "<legend><h2>Vendors Information</h2></legend>";
			$tableBodyText .= "<table class='list'>";
			$tableBodyText .= "<tr id='tableHeader'>";
			$tableBodyText .= "<th>Vendor Number</th>";
			$tableBodyText .= "<th>Vendor Name</th>";
			$tableBodyText .= "<th>Address1</th>";
			$tableBodyText .= "<th>Address2</th>";
			$tableBodyText .= "<th>City</th>";
			$tableBodyText .= "<th>Province</th>";
			$tableBodyText .= "<th>Postal Code</th>";
			$tableBodyText .= "<th>Country</th>";
			$tableBodyText .= "<th>Phone</th>";
			$tableBodyText .= "<th>Fax</th>";
			$tableBodyText .= "</tr>";
			while ($row = $preparedQuerySelect -> fetch())
			{
				$vendorNo = $row['VendorNo'];
				$vendorName = $row['VendorName'];
				$addressOne = $row['Address1'];
				$addressTwo = $row['Address2'];
				$city = $row['City'];
				$prov = $row['Prov'];
				$postcode = $row['PostCode'];
				$country = $row['Country'];
				$phone = $row['Phone'];
				$fax = $row['Fax'];

				$tableBodyText .= "<tr>";
				$tableBodyText .= "<td>$vendorNo</td>";
				$tableBodyText .= "<td class='text'>$vendorName</td>";
				$tableBodyText .= "<td>$addressOne</td>";
				$tableBodyText .= "<td>$addressTwo</td>";
				$tableBodyText .= "<td>$city</td>";
				$tableBodyText .= "<td>$prov</td>";
				$tableBodyText .= "<td>$postcode</td>";
				$tableBodyText .= "<td>$country</td>";
				$tableBodyText .= "<td>$phone</td>";
				$tableBodyText .= "<td>$fax</td>";
				$tableBodyText .= "</tr>";

				$num_count++;
			}
		}
		$tableBodyText .= "</table>";
		$tableBodyText .= "</fieldset><br>";
		
		if($num_count > 0)
		{
			echo "<i>Number of record(s) found: $num_count</i>";
			echo $tableBodyText;
		}
		else
		{
			echo "<i>No records found. Try different search criteria.</i>";
		}
		
		
	}

	function addTable($dbName, $userInput, $newUserInput)
	{
		$fieldName = array();
		$inputValue = array();

		for ($i=0; $i < count($newUserInput) ; $i++)
		//When the value is not empty, put relevant field name and value into new arrays
		{
			if (!empty($newUserInput[$i]))
			{
				$newUserInput[$i] = str_replace("'", "''", $newUserInput[$i]);
				//To prevent error when there is a single quote in the value
				$fieldName[] = $userInput[$i];
				$inputValue[] = $newUserInput[$i];
			}
		}

		$connection = connectToDatabase();
		$fields = implode(", ", $fieldName); //Convert array to comma separated string
		$values = implode(", :", $fieldName);

		$parameterizedQuery = "INSERT INTO $dbName ($fields) VALUES (:$values)";
		$preparedStatement = $connection -> prepare($parameterizedQuery);
		
		for ($i=0; $i < count($fieldName); $i++)
		{ 
			$preparedStatement -> bindParam($fieldName[$i], $inputValue[$i]);
		}
		
		$preparedStatement -> execute($inputValue);
		echo "New record created successfully";
	}
?>