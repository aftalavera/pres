<?php
//Page_BeforeInitialize @1-057C4D88
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $NewPage1; //Compatibility
//End Page_BeforeInitialize

//FlashChart1 Initialization @14-A4BCA257
    if ('FlashChart1' == CCGetParam('callbackControl')) {
        $Service = new Service();
        $formatter = new TemplateFormatter();
        $formatter->SetTemplate(file_get_contents(RelativePath . "/" . "FlashChart1.xml"));
        $Service->SetFormatter($formatter);
//End FlashChart1 Initialization

//FlashChart1 DataSource @14-82A7AAAD
        $FlashChart1->DSType = dsSQL;
        $Service->DataSource = new clsDBsql();
        $Service->ds = & $Service->DataSource;
        list($FlashChart1->BoundColumn, $FlashChart1->TextColumn, $FlashChart1->DBFormat) = array("", "", "");
        $Service->DataSource->SQL = "select 'PLD' partido,10 mesas,.3 porciento\n" .
        "union\n" .
        "select 'PRD',50,.3\n" .
        "union\n" .
        "select 'prsc',23,.3";
        $Service->DataSource->Order = "";
        $Service->DataSource->PageSize = 25;
        $Service->SetDataSourceQuery($Service->DataSource->OptimizeSQL(CCBuildSQL($Service->DataSource->SQL, $Service->DataSource->Where, $Service->DataSource->Order)));
//End FlashChart1 DataSource

//FlashChart1 Execution @14-35925D24
        $Service->AddDataSetValue("Title", "Mesas Reportadas");
        $Service->AddHttpHeader("Content-type", "text/xml");
        $Service->DisplayHeaders();
        echo $Service->Execute();
//End FlashChart1 Execution

//FlashChart1 Tail @14-27890EF8
        exit;
    }
//End FlashChart1 Tail

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
