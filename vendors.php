<!-- vendors.php
     Practicing PHP and Database

     Revision History
        Jaden Ahn + Aubrey Delong, 2017.07.29: Created
        Jaden Ahn + Aubrey Delong, 2017.08.11: Added number_format to format vendor number
-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Assignment5: Group 3</title>
        <link rel="stylesheet" type="text/css" href="styles\assignment5.css">
        <script src="javaScript\validation.js"></script>
        <?php
            session_start();
            require("tableData.php");
            
            $errorMessage = array();
            $inputListRegEx = '/^\(?\d{3}\)?[\.\-\/\s]?\d{3}[\.\-\/\s]?\d{4}$/'; //RegEx for Phone Number and Fax
            $userInput = array("VendorNo", "VendorName", "Address1", "Address2", "City", "Prov", "PostCode", "Country", "Phone", "Fax");
            $inputListName = array("Vendor Number", "Vendor Name", "Address1", "Address2", "City", "Province", "Postal Code", "Country", "Phone", "Fax");
            $newUserInput = array();
            $numberOfErrors = 0;

            for ($i=0; $i < count($userInput); $i++)
            { 
                if (!isset($_POST[$userInput[$i]]))
                {
                    $errorMessage[] = "* Invalid Access.<br>";
                    $_SESSION["vendorsErrorMessage"] = $errorMessage;
                    header("Location: index.php");
                    exit;
                }
                else
                {
                    $newUserInput[$i] = trim(htmlspecialchars($_POST[$userInput[$i]]));
                    if($i == 0)
                    {
                        $newUserInput[$i] = number_format($newUserInput[$i], 1, '.', '');
                    }
                }
            }
            if(!validateVendorsData($newUserInput[0]))
            {
                $_SESSION["vendorsErrorMessage"] = $errorMessage;
                header("Location: index.php");
                exit;
            }

            function validateVendorsData($vendorNo)
            {
                global $userInput, $inputListName, $newUserInput, $errorMessage, $numberOfErrors, $inputListRegEx;
                $FORMAT_ERROR_MESSAGE = ": Format Doesn't match.<br>";

                if ($vendorNo == Null)
                {
                    $errorMessage[] = "* Vendor Number: This field must be filled out.<br>";
                    $numberOfErrors++;
                }
                else if(!is_numeric($vendorNo))
                {
                    $errorMessage[] = "* Vendor Number$FORMAT_ERROR_MESSAGE";
                    $numberOfErrors++;
                }
                else
                //Check for duplicate vendor number
                {
                    $existingVendorNo = getVendorNumber();
                    for ($i=0; $i < count($existingVendorNo[$i]); $i++)
                    {
                        if($vendorNo == $existingVendorNo[$i])
                        {
                            $errorMessage[] = "* Vendor Number: Duplicate number is not allowed.<br>";
                            $numberOfErrors++;
                        }
                    }
                }
                
                //Check RegEx for Phone and Fax
                for ($i=8; $i < count($newUserInput); $i++)
                {
                    if(!empty($newUserInput[$i]))
                    {
                        if(!preg_match($inputListRegEx, $newUserInput[$i]))
                        {
                            $errorMessage[] = "* $inputListName[$i]$FORMAT_ERROR_MESSAGE";
                            $numberOfErrors++;
                        }
                    }
                }
                
                //Return true only when there's no error
                if ($numberOfErrors > 0)
                {
                    $errorMessage[] = "[ Total Number of Errors: ".$numberOfErrors." ]<br>";
                    return false;
                }
                else
                {
                    return true;
                }                
            }
        ?>
    </head>
    <body>
        <header>
            <h1>Assignment5: Group 3</h1>
        </header>
        <main>
            <article>
                <h3><img style="vertical-align:middle" src="images\icon_database.png" width="45px" alt="Database icon"> Maintain Database</h3>
                <em>PHP and Database Practice</em><br><br>
                <fieldset>
                    <legend><h2>New Vendor Information</h2></legend>
                    <?php
                        for ($i=0; $i < count($newUserInput) ; $i++)
                        {
                            echo "$inputListName[$i] : $newUserInput[$i]<br>";
                        }
                        $dbName = 'Vendors';
                        addTable($dbName, $userInput, $newUserInput);
                    ?>
                    <form action="parameter.php" method="POST">
                        <button name="Table" value="Vendors" type="submit">View Vendors Data</button>
                        <a href="index.php" class="button">Go to Front Page</a>
                    </form>
                </fieldset>
            </article>
        </main>
        <footer>
            PROG1800-17S-Sec1-Programming Dynamic Websites | Designed &amp; Coded by Jaden + Aubrey
        </footer>
    </body>
</html>
