<?php

include("../Includes/FusionCharts.php");
include("../Includes/DBConn.php");

?>

<HTML>
<HEAD>
	<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/FusionCharts.js"></SCRIPT>
</HEAD>


<BODY>


<?php


    $link = connectToDB();

    $strXML = "<graph caption='' subCaption='' yAxisMinValue='0' yAxisMaxValue='100' decimalPrecision='1' showNames='1' numberSuffix='%25'  >";
	
	parse_str(parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY));
    $strQuery = "rd2012.dbo.spGrafNacionalProy " . $source;
	$result = mssql_query($strQuery);
    
    //Iterate through each factory
    if ($result) {
        while($ors = mssql_fetch_array($result)) {
           
	      
            $strXML .= "<set name='" . $ors['nombre'] . "' value='" . $ors['porciento'] . "' color='" . $ors['color'] . "'/>";
	    	           
        }
    }

    mssql_close($link);

    //Finally, close <graph> element
    $strXML .= "</graph>";

    print($strXML);

    echo renderChart("../FusionCharts/Charts/Column3D.swf", "",  $strXML, "FactorySum", 300, 250, false, false);

?>


</BODY>
</HTML>