<?php
//BindEvents Method @1-B90E19BC
function BindEvents()
{
    global $GrafRegional;
    global $ProvinciaResult;
    $GrafRegional->CCSEvents["BeforeShow"] = "GrafRegional_BeforeShow";
    $ProvinciaResult->CCSEvents["BeforeShow"] = "ProvinciaResult_BeforeShow";
}
//End BindEvents Method

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
    $GrafRegional->SetValue("<iframe name='graph' src='./graphs/provincia.php?prov=1'"." frameborder='0' width='400' height='400' scrolling='no'></iframe>" );// Write your own code here.
// -------------------------
//End Custom Code

//Close GrafRegional_BeforeShow @6-2D746DD1
    return $GrafRegional_BeforeShow;
}
//End Close GrafRegional_BeforeShow

//ProvinciaResult_BeforeShow @2-5E6EAC3E
function ProvinciaResult_BeforeShow(& $sender)
{
    $ProvinciaResult_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $ProvinciaResult; //Compatibility
//End ProvinciaResult_BeforeShow

//Custom Code @3-2A29BDB7
// -------------------------
    $ProvinciaResult->SetValue("<iframe name='result' src='./provinciaprensa.php?source=" . CCGetFromGet("source", NULL) ."&provincia=1' frameborder='0' width='950' height='400' scrolling='no'></iframe>" );// Write your own code here.
// -------------------------
//End Custom Code

//Close ProvinciaResult_BeforeShow @2-227F987F
    return $ProvinciaResult_BeforeShow;
}
//End Close ProvinciaResult_BeforeShow


?>
