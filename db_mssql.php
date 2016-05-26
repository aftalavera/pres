<?php

//DB MSSQLServer Class @0-01E626D6
/*
 * Database Management for PHP
 *
 * (C) Copyright 1998 Cameron Taggart  (cameront@wolfenet.com)
 *        Modified by Guarneri carmelo (carmelo@melting-soft.com)
 *        Modified by Cameron Just     (C.Just@its.uq.edu.au)
 *        Modified by Vitaliy Radchuk  (vitaliy.radchuk@yessoftware.com)
 *
 * db_mssql.php
 */ 
# echo "<BR>This is using the MSSQL class<BR>";

class DB_MSSQL {
  public $DBHost     = "";
  public $DBDatabase = "";
  public $DBUser     = "";
  public $DBPassword = "";
  public $Persistent = false;

  public $Binds = array();

  public $Link_ID  = 0;
  public $Query_ID = 0;
  public $Record   = array();
  public $Row      = 0;
  
  public $Errno    = 0;
  public $Error    = "";

  public $Auto_Free = 1;     ## set this to 1 to automatically free results
  public $Connected = false;
  
  
  /* public: constructor */
  function DB_Sql($query = "") {
      $this->query($query);
  }

  function try_connect() 
  {
    $this->Query_ID  = 0;
    if($this->Persistent)
      $this->Link_ID = @mssql_pconnect($this->DBHost, $this->DBUser, $this->DBPassword);
    else
      $this->Link_ID = @mssql_connect($this->DBHost, $this->DBUser, $this->DBPassword);

    $this->Connected = $this->Link_ID ? true : false;
    return $this->Connected;
  }

  function connect() {
    if (!$this->Connected) {
      $this->Query_ID  = 0;
      if($this->Persistent)
        $this->Link_ID=mssql_pconnect($this->DBHost, $this->DBUser, $this->DBPassword);
      else
        $this->Link_ID=mssql_connect($this->DBHost, $this->DBUser, $this->DBPassword);
      if (!$this->Link_ID) {
        $this->Halt("Cannot connect to mssql Database");
        return ;
      }
      if( !mssql_select_db($this->DBDatabase, $this->Link_ID)){
        $this->Halt("Cannot select Database ".$this->DBDatabase);
        return ;
      }
      $this->Connected = true;
    }
  }

  function free_result() {
    if (is_resource($this->Query_ID)) {
      @mssql_free_result($this->Query_ID);
    }
    $this->Query_ID = 0;
  }

  function bind($parameter_name, $parameter_value, $parameter_length = 0, $parameter_type = 0, $is_output = false)
  {
    $this->Binds[$parameter_name] = array($parameter_value, $parameter_length, $parameter_type, $is_output);
  }

  function unBindAll()
  {
    $this->Binds = array();
  }

  function execute($Procedure) 
  {
    if ($Procedure == "")
      return "";
    if (!$this->Link_ID)
      $this->connect();
    
#   printf("<br>Debug: SP = %s<br>\n", $Procedure);

    if($this->Query_ID = mssql_init($Procedure, $this->Link_ID)) 
    {
      foreach ($this->Binds as $parameter_name => $parameter_values) 
      {
        if (!mssql_bind($this->Query_ID, '@' . $parameter_name, $this->Binds[$parameter_name][0], $this->Binds[$parameter_name][2],  $this->Binds[$parameter_name][3], "", $this->Binds[$parameter_name][1])) {
          $this->Errno = 1;
          $this->Error = "Error in Bind";
          $this->Errors->addError("Database error: " . $this->Error);
          return false;
        }
      }
      if($result = mssql_execute($this->Query_ID)) 
      {
        if (is_resource($result)) {
          $this->Query_ID = $result;
          mssql_next_result($this->Query_ID);
        } else {
          $this->Query_ID = "";
        }
        $bi=0;
        foreach ($this->Binds as $parameter_name => $parameter_values) {
          if ($this->Binds[$parameter_name][3]) {
            $this->Record[strtolower($parameter_name)] = $this->Binds[$parameter_name][0];
            $this->Record[$bi++] = $this->Record[$parameter_name];
          }
        }
        return;
      }
    
 
    } 
    if(!$this->Query_ID)
    {
      $this->Errno = 1;
      $this->Error = "SP $Procedure executing failed";
      $this->Errors->addError("Database error: " . $this->Error);

    }

  }

  
  function query($Query_String) 
  {
    
    /* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DB_Sql_Subclass;'
       */
      return 0;

    if (!$this->Link_ID)
      $this->connect();
    
#   printf("<br>Debug: query = %s<br>\n", $Query_String);
    
    $this->Query_ID = mssql_query($Query_String, $this->Link_ID);
    $this->Row = 0;
    if (!$this->Query_ID) {
      $this->Errno = 1;
      $this->Error = "General Error (The MSSQL interface cannot return detailed error messages).";
      $this->Errors->addError("Database error: " . $this->Error);
    }
    return $this->Query_ID;
  }
  
  function next_record() {

    
    if (is_resource($this->Query_ID) && ($this->Record = mssql_fetch_row($this->Query_ID))) {
      // add to Record[<key>]
      $count = mssql_num_fields($this->Query_ID);
      for ($i=0; $i<$count; $i++){
        $fieldinfo = mssql_fetch_field($this->Query_ID,$i);
        $this->Record[strtolower($fieldinfo->name)] = $this->Record[$i];
      }
      $this->Row += 1;
      $stat = 1;
    } else {
      if ($this->Auto_Free) {
        $this->free_result();
      }
      $stat = 0;
    }
    return $stat;
  }
  
  function seek($pos) {
    $status = @mssql_data_seek($this->Query_ID,$pos);
    if ($status) $this->Row = $pos;
    return true;
  }

  function metadata($table) {
    $count = 0;
    $id    = 0;
    $res   = array();

    $this->connect();
    $id = mssql_query("select top 1 * from $table", $this->Link_ID);
    if (!$id) {
      $this->Errno = 1;
      $this->Error = "General Error (The MSSQL interface cannot return detailed error messages).";
      $this->Errors->addError("Metadata query failed: " . $this->Error);
      return 0;
    }
    $count = mssql_num_fields($id);
    
    for ($i=0; $i<$count; $i++) {
      $info = mssql_fetch_field($id, $i);
      $res[$i]["table"] = $table;
      $res[$i]["name"]  = $info["name"];
      $res[$i]["len"]   = $info["max_length"];
      $res[$i]["flags"] = $info["numeric"];
    }
    $this->free_result();
    return $res;
  }
  
  function affected_rows() {
    return mssql_affected_rows($this->Query_ID);
  }
    
  function num_rows() {
    return is_resource($this->Query_ID) ? mssql_num_rows($this->Query_ID) : "";
  }
  
  function num_fields() {
    return mssql_num_fields($this->Query_ID);
  }

  function nf() {
    return $this->num_rows();
  }
  
  function np() {
    print $this->num_rows();
  }
  
  function f($Field_Name) {
    return $this->Record && array_key_exists(strtolower($Field_Name), $this->Record) ? $this->Record[strtolower($Field_Name)] : "";
  }
  
  function p($Field_Name) {
    print $this->f($Field_Name);
  }

  function close()
  {
    if ($this->Query_ID) {
      $this->free_result();
    }
    if ($this->Connected && !$this->Persistent) {
      mssql_close($this->Link_ID);
      $this->Connected = false;
    }
  }  

  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MSSQL Error</b><br>\n");
    die("Session halted.");
  }
      
  function esc($value) {
    return str_replace("'", "''", $value);   
  }

}

//End DB MSSQLServer Class


?>
