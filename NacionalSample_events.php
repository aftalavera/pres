<?php
//BindEvents Method @1-69C32C2E
function BindEvents()
{
    global $GrafNacional;
    global $GrafNacionalProy;
    $GrafNacional->CCSEvents["BeforeShow"] = "GrafNacional_BeforeShow";
    $GrafNacionalProy->CCSEvents["BeforeShow"] = "GrafNacionalProy_BeforeShow";
}
//End BindEvents Method

//GrafNacional_BeforeShow @68-9D60C121
function GrafNacional_BeforeShow(& $sender)
{
    $GrafNacional_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $GrafNacional; //Compatibility
//End GrafNacional_BeforeShow

//Custom Code @69-2A29BDB7
// -------------------------
    $GrafNacional->SetValue("<iframe src='./graphs/nacionalsample.php?source=" . CCGetFromGet("source", NULL) ."' frameborder='0' width='300' height='250' scrolling='no'></iframe>" );// Write your own code here.
// -------------------------
//End Custom Code

//Close GrafNacional_BeforeShow @68-F61E944B
    return $GrafNacional_BeforeShow;
}
//End Close GrafNacional_BeforeShow

//GrafNacionalProy_BeforeShow @72-FA8F0B1C
function GrafNacionalProy_BeforeShow(& $sender)
{
    $GrafNacionalProy_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $GrafNacionalProy; //Compatibility
//End GrafNacionalProy_BeforeShow

//Custom Code @73-2A29BDB7
// -------------------------
    $GrafNacionalProy->SetValue("<iframe src='./graphs/nacionalproysample.php?source=" . CCGetFromGet("source", NULL) ."' frameborder='0' width='300' height='250' scrolling='no'></iframe>" );// Write your own code here.
// -------------------------
//End Custom Code

//Close GrafNacionalProy_BeforeShow @72-F529433F
    return $GrafNacionalProy_BeforeShow;
}
//End Close GrafNacionalProy_BeforeShow
?>
