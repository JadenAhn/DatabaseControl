<!-- parameter.php
     Practicing PHP and Database

     Revision History
        Jaden Ahn + Aubrey Delong, 2017.07.29: Created
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
    <body>
        <header>
            <h1>Assignment5: Group 3</h1>
        </header>
        <main>
            <article>
                <h3><img style="vertical-align:middle" src="images\icon_database.png" width="45px" alt="Database icon"> Maintain Database</h3>
                <em>PHP and Database Practice</em><br><br>
                <form action="parameter.php" method="POST">
                    <select name="Table" id="Table">
                        <option value="Parts" id="Parts">Parts</option>
                        <option value="Vendors" id="Vendors">Vendors</option>
                    </select>
                        <input class="button" type="submit" value="View Data">
                        <a href="index.php" class="button">Go to Front Page</a>
                </form><br><br>
                
                    <?php
                        if (!isset($_POST['Table']))
                        {
                            $table = 'Parts';
                        }
                        else
                        {
                            $table = $_POST['Table'];
                        }

                        if (!isset($_POST['Fields']))
                        {
                            $fields = '';
                        }
                        else
                        {
                            $fields = $_POST['Fields'];
                        }

                        if (!isset($_POST['Query']))
                        {
                            $query = '';
                        }
                        else
                        {
                            $query = $_POST['Query'];
                        }

                        if (!isset($_POST['Operator']))
                        {
                            $operator = '';
                        }

                        else
                        {
                            $operator = $_POST['Operator'];
                        }
                        
                        fillTable($table, $fields, $operator, $query);
                    ?>
            </article>
        </main>
        <footer>
            PROG1800-17S-Sec1-Programming Dynamic Websites | Designed &amp; Coded by Jaden + Aubrey
        </footer>
    </body>
</html>
