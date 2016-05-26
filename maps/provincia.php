<?php
	
	include("../Includes/DBConn.php");
		
	$link = connectToDB();
	
	parse_str(parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY));
    $query = 'rd2012.dbo.spProvinciaMapa ' . $source ;
	$result= mssql_query($query, $link);
	
	
	print("<map showCanvasBorder='1' exposeHoverEvent='1' registerWithJS='1' canvasBorderColor='f1f1f1' showLabels='0' canvasBorderThickness='2' borderColor='00324A' fillColor='FEE99A' hoverColor='FCCA03' showExportDataMenuItem='1'>");
				
	print("<data>");
		
	while ($row = mssql_fetch_array($result)) {
		print("<entity id='".$row['map_cod_prov']."' color='".$row['color']."' link='j-updater-".$row['cod_prov']."'/>");
	}
	
	mssql_close($link);

	print("</data>");
	print("</map>");
?>

