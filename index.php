<!-- index.php
     Practicing PHP and Database

     Revision History
        Jaden Ahn + Aubrey Delong, 2017.07.29: Created
        Jaden Ahn + Aubrey Delong, 2017.08.01: Added dynamic combo button for query
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
        ?>
    </head>
    <body onLoad="onPageLoad()">
        <header>
            <h1>Assignment5: Group 3</h1>
        </header>
        <main>
            <article>
                <h3><img style="vertical-align:middle" src="images\icon_database.png" width="45px" alt="Database icon"> Maintain Database</h3>
                <em>PHP and Database Practice</em><br><br>
                <p>* Enter information below and submit to add a new data into the relevant database.</p>
                <noscript>
                    <p style="color:#0056AF">## Some functions may not work properly without JavaScript ##</p>
                </noscript>
                <fieldset>
                    <legend><h2>Parts Information</h2></legend>
                    <form action="parts.php" method="POST" onsubmit="return validatePartsData()">
                        <table class="userInfo">
                            <tr>
                                <th>Vendor Number:</th>
                                <td>
                                    <select name="VendorNo" id="VendorNoPart" style="width: 160px">
                                        <?php
                                            $vendorNo = getVendorNumber();
                                            foreach ($vendorNo as $value)
                                            {
                                                echo "<option value='$value'>$value</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                                <th>Description:</th>
                                <td><input type="text" name="Description" id="Description" maxlength="30"></td>
                            </tr>
                            <tr>
                                <th>On Hand:</th>
                                <td><input type="text" name="OnHand" id="OnHand"></td>
                            </tr>
                            <tr>
                                <th>On Order:</th>
                                <td><input type="text" name="OnOrder" id="OnOrder"></td>
                            </tr>
                            <tr>
                                <th>Cost:</th>
                                <td><input type="text" name="Cost" id="Cost" ></td>
                            </tr>                            
                            <tr>
                                <th>List Price:</th>
                                <td><input type="text" name="ListPrice" id="ListPrice"></td>
                            </tr>
                        </table>
                        <input class="button" type="submit" value="Submit">
                        <input class="button" type="reset" value="Reset">
                    </form>
                </fieldset>

                <p class="notice" id="partsErrorMessage">
                    <?php
                        if (isset($_SESSION["partsErrorMessage"]))
                        {
                            for ($i=0; $i < count($_SESSION["partsErrorMessage"]); $i++)
                            { 
                                echo $_SESSION["partsErrorMessage"][$i];
                            }
                            unset($_SESSION["partsErrorMessage"]);
                            session_destroy();
                        }
                    ?>
                </p>                

                <fieldset>
                    <legend><h2>Vendors Information</h2></legend>
                    <script type="text/javascript">
                        var vendorNo = new Array(<?php echo implode(', ', $vendorNo); ?>);
                    </script>
                    <form action="vendors.php" method="POST" onsubmit="return validateVendorsData(vendorNo)">
                        <table class="userInfo">
                             <tr>
                                <th>Vendor Number:</th>
                                <td><input type="text" name="VendorNo" id="VendorNo"></td>
                            </tr>
                            <tr>
                                <th>Vendor Name:</th>
                                <td><input type="text" name="VendorName" id="VendorName" maxlength="30" onfocusout="this.value = capitalizeFirstLetter(this.value)"></td>
                            </tr>
                            <tr>
                                <th>Address 1</th>
                                <td><input type="text" name="Address1" id="Address1" maxlength="30" onfocusout="this.value = capitalizeFirstLetter(this.value)"></td>
                            </tr>
                            <tr>
                                <th>Address 2</th>
                                <td><input type="text" name="Address2" id="Address2" maxlength="30" onfocusout="this.value = capitalizeFirstLetter(this.value)"></td>
                            </tr>
                            <tr>
                                <th>City:</th>
                                <td><input type="text" name="City" id="City" maxlength="20" onfocusout="this.value = capitalizeFirstLetter(this.value)"></td>
                            </tr>
                            <tr>
                                <th>Province:</th>
                                <td><input type="text" name="Prov" id="Prov" maxlength="2" onfocusout="this.value = capitalizeWord(this.value)"></td>
                                <td class="example">Ex. ON</td>
                            </tr>
                            <tr>
                                <th>Postal Code:</th>
                                <td><input type="text" name="PostCode" id="PostCode" maxlength="6" onfocusout="this.value = capitalizeWord(this.value)"></td>
                            </tr>
                            <tr>
                                <th>Country:</th>
                                <td><input type="text" name="Country" id="Country" maxlength="2" onfocusout="this.value = capitalizeWord(this.value)"></td>
                                <td class="example">Ex. CA</td>
                            </tr>                                                        
                            <tr>
                                <th>Phone Number:</th>
                                <td><input type="text" name="Phone" id="Phone" maxlength="12"></td>
                                <td class="example">Ex. (123) 456-7890</td>
                            </tr>
                            <tr>
                                <th>Fax:</th>
                                <td><input type="text" name="Fax" id="Fax" maxlength="12"></td>
                                <td class="example">Ex. (123) 456-7890</td>
                            </tr>
                        </table>
                        <input class="button" type="submit" value="Submit">
                        <input class="button" type="reset" value="Reset">
                    </form>
                </fieldset>

                <p class="notice" id="vendorsErrorMessage">
                    <?php
                        if (isset($_SESSION["vendorsErrorMessage"]))
                        {
                            for ($i=0; $i < count($_SESSION["vendorsErrorMessage"]); $i++)
                            { 
                                echo $_SESSION["vendorsErrorMessage"][$i];
                            }
                            unset($_SESSION["vendorsErrorMessage"]);
                            session_destroy();
                        }
                    ?>
                </p><br>

                <p>* Enter information below to search data from database.</p>
                <noscript>
                    <p style="color:#0056AF">## Some functions may not work properly without JavaScript ##</p>
                </noscript>
                <p class="example"><b>[Query Instruction]</b></p>
                <p class="example">- When searching for numeric records, you can use >, <, or =</p>
                <p class="example">- When searching for non-numeric records, you can use SAME, NOT, or LIKE(including the keywords)</p>
                <p class="example">- If you do not input fields, operator, and key, all data will show. </p>
                <fieldset>
                    <legend><h2>Data Query</h2></legend>
                    <form action="parameter.php" method="POST" onsubmit="return validateQueryData()">
                        <label for="Table">Table:</label>
                        <select name="Table" id="Table" onchange="fillFieldName(this.value)">
                            <option value="Parts" id="Parts">Parts</option>
                            <option value="Vendors" id="Vendors">Vendors</option>
                        </select>
                        <label for="Fields">Fields:</label>
                        <select name="Fields" id="FieldNames"  onchange="fillOperator(this.value)">
                        </select><br>
                        <label for="Operator">Operator:</label>
                        <select name="Operator" id="Operator">
                        </select>
                        <label for="Query">Key:</label>
                        <input type="text" name="Query" id="Query" maxlength="30">
                        <input class="button" type="submit" value="Search">
                    </form>
                </fieldset>

                <p class="notice" id="parameterErrorMessage">
                    <?php
                        if (isset($_SESSION["parameterErrorMessage"]))
                        {
                            for ($i=0; $i < count($_SESSION["parameterErrorMessage"]); $i++) { 
                                echo $_SESSION["parameterErrorMessage"][$i];
                            }
                            unset($_SESSION["parameterErrorMessage"]);
                            session_destroy();
                        }
                    ?>
            </article>
        </main>
        <footer>
            PROG1800-17S-Sec1-Programming Dynamic Websites | Designed &amp; Coded by Jaden + Aubrey
        </footer>
    </body>
</html>
