<?php
//BindEvents Method @1-D70321EC
function BindEvents()
{
    global $MunicipioResult;
    global $GrafRegional;
    $MunicipioResult->CCSEvents["BeforeShow"] = "MunicipioResult_BeforeShow";
    $GrafRegional->CCSEvents["BeforeShow"] = "GrafRegional_BeforeShow";
}
//End BindEvents Method

//MunicipioResult_BeforeShow @2-40027F83
function MunicipioResult_BeforeShow(& $sender)
{
    $MunicipioResult_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $MunicipioResult; //Compatibility
//End MunicipioResult_BeforeShow

//Custom Code @3-2A29BDB7
// -------------------------
    $MunicipioResult->SetValue("<iframe name='result' src='./Municipio.php?source=" . CCGetFromGet("source", NULL) ."&municipio=1' frameborder='0' width='950' height='400' scrolling='no'></iframe>" );// Write your own code here.
// -------------------------
//End Custom Code

//Close MunicipioResult_BeforeShow @2-4891C437
    return $MunicipioResult_BeforeShow;
}
//End Close MunicipioResult_BeforeShow

//GrafRegional_BeforeShow @6-BACF70EB
function GrafRegional_BeforeShow(& $sender)
{
    $GrafRegional_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $GrafRegional; //Compatibility
//End GrafRegional_BeforeShow

//Custom Code @7-2A29BDB7
// -------------------------
    $GrafRegional->SetValue("<iframe name='graph' src='./graphs/municipio.php?mun=1'"." frameborder='0' width='400' height='375' scrolling='no'></iframe>" );// Write your own code here.
// -------------------------
//End Custom Code

//Close GrafRegional_BeforeShow @6-2D746DD1
    return $GrafRegional_BeforeShow;
}
//End Close GrafRegional_BeforeShow

//DEL  // -------------------------
//DEL      $ProvinciaResult->SetValue("<iframe name='result' src='./Provincia.php?source=" . CCGetFromGet("source", NULL) ."&provincia=1' frameborder='0' width='950' height='550' scrolling='no'></iframe>" );// Write your own code here.
//DEL  // -------------------------

?>
