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

//GrafNacional_BeforeShow @67-9D60C121
function GrafNacional_BeforeShow(& $sender)
{
    $GrafNacional_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $GrafNacional; //Compatibility
//End GrafNacional_BeforeShow

//Custom Code @68-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
//End Custom Code

//Close GrafNacional_BeforeShow @67-F61E944B
    return $GrafNacional_BeforeShow;
}
//End Close GrafNacional_BeforeShow

//GrafNacionalProy_BeforeShow @69-FA8F0B1C
function GrafNacionalProy_BeforeShow(& $sender)
{
    $GrafNacionalProy_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $GrafNacionalProy; //Compatibility
//End GrafNacionalProy_BeforeShow

//Custom Code @70-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
//End Custom Code

//Close GrafNacionalProy_BeforeShow @69-F529433F
    return $GrafNacionalProy_BeforeShow;
}
//End Close GrafNacionalProy_BeforeShow


?>
