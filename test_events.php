<?php
//BindEvents Method @1-781A231F
function BindEvents()
{
    global $Map;
    $Map->CCSEvents["BeforeShow"] = "Map_BeforeShow";
}
//End BindEvents Method

//Map_BeforeShow @2-945422EB
function Map_BeforeShow(& $sender)
{
    $Map_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Map; //Compatibility
//End Map_BeforeShow

//Custom Code @3-2A29BDB7
// -------------------------
   $Map->SetValue("<embed name='mapa' src='./svg/circunscripciones" . CCGetFromGet("source", NULL) . ".svgz' width='690px' height='463px'></embed>");
// -------------------------
//End Custom Code

//Close Map_BeforeShow @2-6FBA2542
    return $Map_BeforeShow;
}
//End Close Map_BeforeShow


?>
