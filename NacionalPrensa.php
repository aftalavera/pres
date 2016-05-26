<?php
//Include Common Files @1-F112A363
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "NacionalPrensa.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridGrid2 { //Grid2 class @76-C37AF6B1

//Variables @76-6E51DF5A

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
//End Variables

//Class_Initialize Event @76-C80C57AE
    function clsGridGrid2($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Grid2";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Grid2";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsGrid2DataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->reportadas = new clsControl(ccsLabel, "reportadas", "reportadas", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("reportadas", ccsGet, NULL), $this);
        $this->total = new clsControl(ccsLabel, "total", "total", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("total", ccsGet, NULL), $this);
        $this->porciento = new clsControl(ccsLabel, "porciento", "porciento", ccsSingle, array(False, 2, Null, "", False, "", "%", 100, True, ""), CCGetRequestParam("porciento", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @76-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @76-7F08C9CD
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);
        $this->DataSource->Parameters["urlsource"] = CCGetFromGet("source", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->reportadas->SetValue($this->DataSource->reportadas->GetValue());
        $this->total->SetValue($this->DataSource->total->GetValue());
        $this->porciento->SetValue($this->DataSource->porciento->GetValue());
        $this->reportadas->Show();
        $this->total->Show();
        $this->porciento->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @76-580C33D7
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End Grid2 Class @76-FCB6E20C

class clsGrid2DataSource extends clsDBsql {  //Grid2DataSource Class @76-1E9D67AF

//DataSource Variables @76-2B306749
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;


    // Datasource fields
    public $reportadas;
    public $total;
    public $porciento;
//End DataSource Variables

//DataSourceClass_Initialize Event @76-F7A890D5
    function clsGrid2DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid2";
        $this->Initialize();
        $this->reportadas = new clsField("reportadas", ccsSingle, "");
        
        $this->total = new clsField("total", ccsSingle, "");
        
        $this->porciento = new clsField("porciento", ccsSingle, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @76-BF7F5B01
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @76-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @76-358C47D4
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->cp["source"] = new clsSQLParameter("urlsource", ccsInteger, "", "", CCGetFromGet("source", NULL), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spNacionalTally " . $this->ToSQL($this->cp["source"]->GetDBValue(), $this->cp["source"]->DataType) . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @76-3036AC6F
    function SetValues()
    {
        $this->reportadas->SetDBValue(trim($this->f("reportadas")));
        $this->total->SetDBValue(trim($this->f("total")));
        $this->porciento->SetDBValue(trim($this->f("porciento")));
    }
//End SetValues Method

} //End Grid2DataSource Class @76-FCB6E20C

//Include Page implementation @82-2DE2724B
include_once(RelativePath . "/HeaderPrensa.php");
//End Include Page implementation

//Include Page implementation @83-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

class clsGridGrid1 { //Grid1 class @2-E857A572

//Variables @2-6E51DF5A

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
//End Variables

//Class_Initialize Event @2-D4F25283
    function clsGridGrid1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Grid1";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Grid1";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsGrid1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->foto = new clsControl(ccsImage, "foto", "foto", ccsText, "", CCGetRequestParam("foto", ccsGet, NULL), $this);
        $this->partido = new clsControl(ccsLabel, "partido", "partido", ccsText, "", CCGetRequestParam("partido", ccsGet, NULL), $this);
        $this->nombre = new clsControl(ccsLabel, "nombre", "nombre", ccsText, "", CCGetRequestParam("nombre", ccsGet, NULL), $this);
        $this->porciento = new clsControl(ccsLabel, "porciento", "porciento", ccsSingle, array(False, 2, Null, "", False, "", "%", 100, True, ""), CCGetRequestParam("porciento", ccsGet, NULL), $this);
        $this->votos = new clsControl(ccsLabel, "votos", "votos", ccsInteger, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("votos", ccsGet, NULL), $this);
        $this->participacion = new clsControl(ccsLabel, "participacion", "participacion", ccsSingle, array(False, 2, Null, "", False, "", "%", 100, True, ""), CCGetRequestParam("participacion", ccsGet, NULL), $this);
        $this->total_inscritos = new clsControl(ccsLabel, "total_inscritos", "total_inscritos", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("total_inscritos", ccsGet, NULL), $this);
        $this->total_votos = new clsControl(ccsLabel, "total_votos", "total_votos", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("total_votos", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-CD27DA49
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);
        $this->DataSource->Parameters["urlsource"] = CCGetFromGet("source", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["foto"] = $this->foto->Visible;
            $this->ControlsVisible["partido"] = $this->partido->Visible;
            $this->ControlsVisible["nombre"] = $this->nombre->Visible;
            $this->ControlsVisible["porciento"] = $this->porciento->Visible;
            $this->ControlsVisible["votos"] = $this->votos->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->foto->SetValue($this->DataSource->foto->GetValue());
                $this->partido->SetValue($this->DataSource->partido->GetValue());
                $this->nombre->SetValue($this->DataSource->nombre->GetValue());
                $this->porciento->SetValue($this->DataSource->porciento->GetValue());
                $this->votos->SetValue($this->DataSource->votos->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->foto->Show();
                $this->partido->Show();
                $this->nombre->Show();
                $this->porciento->Show();
                $this->votos->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->participacion->SetValue($this->DataSource->participacion->GetValue());
        $this->total_inscritos->SetValue($this->DataSource->total_inscritos->GetValue());
        $this->total_votos->SetValue($this->DataSource->total_votos->GetValue());
        $this->participacion->Show();
        $this->total_inscritos->Show();
        $this->total_votos->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-BB597E42
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->foto->Errors->ToString());
        $errors = ComposeStrings($errors, $this->partido->Errors->ToString());
        $errors = ComposeStrings($errors, $this->nombre->Errors->ToString());
        $errors = ComposeStrings($errors, $this->porciento->Errors->ToString());
        $errors = ComposeStrings($errors, $this->votos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End Grid1 Class @2-FCB6E20C

class clsGrid1DataSource extends clsDBsql {  //Grid1DataSource Class @2-AD094A6C

//DataSource Variables @2-D5AD4E22
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;


    // Datasource fields
    public $foto;
    public $partido;
    public $nombre;
    public $porciento;
    public $votos;
    public $participacion;
    public $total_inscritos;
    public $total_votos;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-58E184F0
    function clsGrid1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid1";
        $this->Initialize();
        $this->foto = new clsField("foto", ccsText, "");
        
        $this->partido = new clsField("partido", ccsText, "");
        
        $this->nombre = new clsField("nombre", ccsText, "");
        
        $this->porciento = new clsField("porciento", ccsSingle, "");
        
        $this->votos = new clsField("votos", ccsInteger, "");
        
        $this->participacion = new clsField("participacion", ccsSingle, "");
        
        $this->total_inscritos = new clsField("total_inscritos", ccsSingle, "");
        
        $this->total_votos = new clsField("total_votos", ccsSingle, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-BF7F5B01
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @2-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @2-E94BBA16
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->cp["source"] = new clsSQLParameter("urlsource", ccsInteger, "", "", CCGetFromGet("source", NULL), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spNacionalParcial " . $this->ToSQL($this->cp["source"]->GetDBValue(), $this->cp["source"]->DataType) . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-CA6A7E83
    function SetValues()
    {
        $this->foto->SetDBValue($this->f("foto"));
        $this->partido->SetDBValue($this->f("partido"));
        $this->nombre->SetDBValue($this->f("nombre"));
        $this->porciento->SetDBValue(trim($this->f("porciento")));
        $this->votos->SetDBValue(trim($this->f("votos")));
        $this->participacion->SetDBValue(trim($this->f("participacion")));
        $this->total_inscritos->SetDBValue(trim($this->f("total_inscritos")));
        $this->total_votos->SetDBValue(trim($this->f("total_votos")));
    }
//End SetValues Method

} //End Grid1DataSource Class @2-FCB6E20C

//Initialize Page @1-7D301A1F
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "NacionalPrensa.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-8BC07C52
include_once("./NacionalPrensa_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-AC327CCE
$DBsql = new clsDBsql();
$MainPage->Connections["sql"] = & $DBsql;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$GrafNacional = new clsControl(ccsLabel, "GrafNacional", "GrafNacional", ccsText, "", CCGetRequestParam("GrafNacional", ccsGet, NULL), $MainPage);
$GrafNacional->HTML = true;
$Grid2 = new clsGridGrid2("", $MainPage);
$Header = new clsHeaderPrensa("", "Header", $MainPage);
$Header->Initialize();
$Footer = new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$Grid1 = new clsGridGrid1("", $MainPage);
$MainPage->GrafNacional = & $GrafNacional;
$MainPage->Grid2 = & $Grid2;
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$MainPage->Grid1 = & $Grid1;
$Grid2->Initialize();
$Grid1->Initialize();

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-E710DB26
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-351F985C
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-AC1346FF
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBsql->close();
    header("Location: " . $Redirect);
    unset($Grid2);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Grid1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-25565321
$Grid2->Show();
$Header->Show();
$Footer->Show();
$Grid1->Show();
$GrafNacional->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-ED1E008B
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBsql->close();
unset($Grid2);
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Grid1);
unset($Tpl);
//End Unload Page


?>
