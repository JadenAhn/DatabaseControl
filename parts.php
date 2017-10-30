<!-- parts.php
     Practicing PHP and Database

     Revision History
        Jaden Ahn + Aubrey Delong, 2017.07.29: Created
        Jaden Ahn + Aubrey Delong, 2017.08.03: Changed the format of numeric value using number_format
        Jaden Ahn + Aubrey Delong, 2017.08.11: Added number_format to format numberic values
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
            $userInput = array("VendorNo", "Description", "OnHand", "OnOrder", "Cost", "ListPrice");
            $inputListName = array("Vendor Number", "Description", "On Hand", "On Order", "Cost", "List Price");
            $newUserInput = array();
            $numberOfErrors = 0;

            for ($i=0; $i < count($userInput); $i++)
            { 
                if (!isset($_POST[$userInput[$i]]))
                {
                    $errorMessage[] = "* Invalid Access.<br>";
                    $_SESSION["partsErrorMessage"] = $errorMessage;
                    header("Location: index.php");
                    exit;
                }
                else
                {
                    $newUserInput[$i] = trim(htmlspecialchars($_POST[$userInput[$i]]));
                }
            }

            if(!validatePartsData())
            {
                $_SESSION["partsErrorMessage"] = $errorMessage;
                header("Location: index.php");
                exit;
            }

            function validatePartsData()
            {
                global $userInput, $inputListName, $newUserInput, $errorMessage, $numberOfErrors;
                $NOT_NUMERIC_ERROR_MESSAGE = ": Format doesn't match. Enter Numbers Only<br>";
                if (empty($_POST['VendorNo']))
                {
                    $errorMessage[] = "* Vendor Number: This field must be filled out.<br>";
                    $numberOfErrors++;
                }
                else if(!is_numeric($_POST['VendorNo']))
                {
                    $errorMessage[] = "* Vendor Number$NOT_NUMERIC_ERROR_MESSAGE";                    
                    $numberOfErrors++;
                }

                //Check for numeric values from OnHand to ListPrice
                for ($i=2; $i < count($newUserInput); $i++)
                {
                    if($newUserInput[$i] != Null)
                    {
                        if(!is_numeric($newUserInput[$i]))
                        {
                            $errorMessage[] = "* $inputListName[$i]$NOT_NUMERIC_ERROR_MESSAGE";
                            $numberOfErrors++;
                        }
                        else
                        {
                            $newUserInput[$i] = number_format($newUserInput[$i], 1, '.', '');
                        }
                    }
                }

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
                    <legend><h2>New Parts Information</h2></legend>
                    <?php
                        for ($i=0; $i < count($newUserInput) ; $i++)
                        {
                            echo "$inputListName[$i] : $newUserInput[$i]<br>";
                        }
                        $dbName = 'Parts';
                        addTable($dbName, $userInput, $newUserInput);
                    ?>
                    <form action="parameter.php" method="POST">
                        <button name="Table" value="Parts" type="submit">View Parts Data</button>
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
