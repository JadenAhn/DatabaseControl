/* validation.js
     Practicing PHP

     Revision History
        Jaden Ahn + Aubrey Delong, 2017.07.28: Created
        Jaden Ahn + Aubrey Delong, 2017.08.03: Added fillFieldName() in onPageLoad()
*/

var partsInputList = new Array("PartID", "VendorNoPart", "Description", "OnHand", "OnOrder", "Cost", "ListPrice");
var partsInputListName = new Array("Part ID", "Vendor Number", "Description", "On Hand", "On Order", "Cost", "List Price");
var vendorsInputList = new Array("VendorNo", "VendorName", "Address1", "Address2", "City", "Prov", "PostCode", "Country", "Phone", "Fax");
var vendorsInputListName = new Array("Vendor Number", "Vendor Name", "Address1", "Address2", "City", "Province", "Postal Code", "Country", "Phone", "Fax");
var NOT_NUMERIC_ERROR_MESSAGE = ": Format Doesn't match. Enter Numbers Only<br>";
var numericOperatorList = new Array(">", "<", "=");
var stringOperatorList = new Array("SAME", "NOT", "LIKE");

function onPageLoad()
{
    document.getElementById('VendorNo').className = "required";
    var table = document.getElementById('Table').value;
    fillFieldName(table);
}

function validatePartsData()
{
    var errorMessage = new Array();
    var numberOfErrors = 0;
    var errorMessageID = 'partsErrorMessage';
        
    document.getElementById(errorMessageID).innerHTML = "";
    
    //Just in case value of VendorNo is empty
    if (document.getElementById('VendorNoPart').value == '')
    {
        document.getElementById('VendorNoPart').focus();
        errorMessage.push("* Vendor Number: This field must be filled out.<br>");
        document.getElementById('VendorNoPart').className = "required"
        numberOfErrors++;
    }
    // Check for required value
    for (var index = 3; index < partsInputList.length; index++)
    {
        var inputValue = document.getElementById(partsInputList[index]).value;
        if(inputValue != '')
        {        
            if(isNaN(inputValue))
            {
                document.getElementById(partsInputList[index]).focus();
                errorMessage.push("* " + partsInputListName[index] + NOT_NUMERIC_ERROR_MESSAGE);
                document.getElementById(partsInputList[index]).className = "required";
                numberOfErrors++;
            }
            else
            {
                document.getElementById(partsInputList[index]).className = "valid"
            }
        }
    }

    //return true only when there's no error
    if (numberOfErrors > 0)
    {
        //Show compound error messages that includes all of the error messages
        for (var index = 0; index < errorMessage.length; index++)
        {
            document.getElementById(errorMessageID).innerHTML += errorMessage[index];
        }
        document.getElementById(errorMessageID).innerHTML += "[ Total Number of Errors: " + numberOfErrors + " ]<br>";
        return false;
    }
    else
    {
        return true;
    }
}

function validateVendorsData(existingVendorNo)
{
    var errorMessage = new Array();
    var inputListRegEx = /^\(?\d{3}\)?[\.\-\/\s]?\d{3}[\.\-\/\s]?\d{4}$/; //RegEx for Phone or Fax
    var numberOfErrors = 0;
    var errorMessageID = 'vendorsErrorMessage';
    
    document.getElementById(errorMessageID).innerHTML = "";

    //Just in case value of VendorNo is empty
    if (document.getElementById('VendorNo').value == '')
    {
        document.getElementById('VendorNo').focus();
        errorMessage.push("* Vendor Number: This field must be filled out.<br>");
        document.getElementById('VendorNo').className = "required"
        numberOfErrors++;
    }
    //In case value of VendorNo is not number
    else if(isNaN(document.getElementById('VendorNo').value))
    {
        document.getElementById('VendorNo').focus();
        errorMessage.push("* Vendor Number" + NOT_NUMERIC_ERROR_MESSAGE);
        document.getElementById('VendorNo').className = "required"
        numberOfErrors++;
    }
    else
    //Check for duplicate vendor number
    {
        for (var index = 0; index < existingVendorNo.length; index++) {
            if (document.getElementById('VendorNo').value == existingVendorNo[index])
            {
                document.getElementById('VendorNo').focus();
                errorMessage.push("* Vendor Number: Duplicate number is not allowed.<br>");
                document.getElementById('VendorNo').className = "required"
                numberOfErrors++;
            }
        }
    }

    //Check RegEx for Phone and Fax
     for (var index = 8; index < vendorsInputList.length; index++)
    {
        var inputValue = document.getElementById(vendorsInputList[index]).value;
        if(inputValue != '')
        {
            if (inputValue.match(inputListRegEx) == null)
            {
                document.getElementById(vendorsInputList[index]).focus();
                errorMessage.push("* " + vendorsInputListName[index] + ": Format Doesn't match.<br>");
                document.getElementById(vendorsInputList[index]).className = "required"
                numberOfErrors++;
            }
            else
            {
                document.getElementById(vendorsInputList[index]).value = inputValue.match(inputListRegEx)
                document.getElementById(vendorsInputList[index]).className = "valid"
            }            
        }
    }

    //return true only when there's no error
    if (numberOfErrors > 0)
    {
        //Show compound error messages that includes all of the error messages
        for (var index = 0; index < errorMessage.length; index++)
        {
            document.getElementById(errorMessageID).innerHTML += errorMessage[index];
        }
        document.getElementById(errorMessageID).innerHTML += "[ Total Number of Errors: " + numberOfErrors + " ]<br>";
        return false;
    }
    else
    {
        return true;
    }
}

function validateQueryData()
{
    var errorMessage = new Array();
    var numberOfErrors = 0;
    var errorMessageID = 'parameterErrorMessage';
    var operator = document.getElementById('Operator').value;
    var query = document.getElementById('Query').value;

    document.getElementById(errorMessageID).innerHTML = "";
    
    for (var index = 0; index < numericOperatorList.length; index++)
    //When operator is about number, check if query is numeric or not
    {
        if(operator == numericOperatorList[index] && isNaN(query))
        {
            numberOfErrors++;
            errorMessage.push("* Query Format doesn't match.<br>");
            break;
        }
    }

    //return true only when there's no error
    if (numberOfErrors > 0)
    {
        //Show compound error messages that includes all of the error messages
        for (var index = 0; index < errorMessage.length; index++)
        {
            document.getElementById(errorMessageID).innerHTML += errorMessage[index];
        }
        document.getElementById(errorMessageID).innerHTML += "[ Total Number of Errors: " + numberOfErrors + " ]<br>";
        return false;
    }
    else
    {
        return true;
    }
}

function capitalizeFirstLetter(input)
{
    var letter = input.split(" ");
    var newWord = "";

    for (var i = 0; i < letter.length; i++)
    {
        var space = "";
        if(i != 0)
        {
            space = " "
        }
        newWord += space + letter[i].charAt(0).toUpperCase() + letter[i].slice(1);
    }
    
    return newWord.trim();
}

function capitalizeWord(input)
{
    return input.toUpperCase().trim();
}

function fillFieldName(input)
{
    var fieldName = 'FieldNames';
    document.getElementById(fieldName).innerHTML = "";
    document.getElementById(fieldName).innerHTML += "<option value=''></option>";
    switch (input) {
        case 'Parts':
            for (var index = 0; index < partsInputList.length; index++)
            {
                if(index == 1)
                {
                    document.getElementById(fieldName).innerHTML += "<option value='VendorNo'>Vendor Number</option>";
                }
                else
                {
                    document.getElementById(fieldName).innerHTML += "<option value='" + partsInputList[index] + "'>" + partsInputListName[index] + "</option>";
                }
            }
            break;
        case 'Vendors':
            for (var index = 0; index < partsInputList.length; index++)
            {
                document.getElementById(fieldName).innerHTML += "<option value='" + vendorsInputList[index] + "'>" + vendorsInputListName[index] + "</option>";
            }
            break;
        default:
            break;
    }
}

function fillOperator(input)
{
    var operator = 'Operator';

    document.getElementById(operator).innerHTML = "";
    document.getElementById(operator).innerHTML += "<option value=''></option>";
    switch (input) {
        case 'PartID':
        case 'VendorNoPart':
        case 'VendorNo':
        case 'OnHand':
        case 'OnOrder':
        case 'Cost':
        case 'ListPrice':
        case 'OnHand':
            for (var index = 0; index < numericOperatorList.length; index++)
            {
                document.getElementById(operator).innerHTML += "<option value='" + numericOperatorList[index] + "'>" + numericOperatorList[index] + "</option>";
            }
            break;

        default:
            for (var index = 0; index < stringOperatorList.length; index++)
            {
                document.getElementById(operator).innerHTML += "<option value='" + stringOperatorList[index] + "'>" + stringOperatorList[index] + "</option>";
            }
            break;
    }
}