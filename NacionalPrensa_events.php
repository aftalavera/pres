<?php
//BindEvents Method @1-2E1B1A1F
function BindEvents()
{
    global $GrafNacional;
    $GrafNacional->CCSEvents["BeforeShow"] = "GrafNacional_BeforeShow";
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
    $GrafNacional->SetValue("<iframe src='./graphs/nacional.php?source=" . CCGetFromGet("source", NULL) ."' frameborder='0' width='300' height='250' scrolling='no'></iframe>" );// Write your own code here.
// -------------------------
//End Custom Code

//Close GrafNacional_BeforeShow @68-F61E944B
    return $GrafNacional_BeforeShow;
}
//End Close GrafNacional_BeforeShow

//DEL  // -------------------------
//DEL      $GrafNacionalProy->SetValue("<iframe src='./graphs/nacionalproy.php?source=" . CCGetFromGet("source", NULL) ."' frameborder='0' width='300' height='250' scrolling='no'></iframe>" );// Write your own code here.
//DEL  // -------------------------

?>
