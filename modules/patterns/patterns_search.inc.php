<?php
/*
* @version 0.1 (wizard)
*/
 global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $qry="1";
  // search filters
  //searching 'TITLE' (varchar)
  global $title;
  if ($title!='') {
   $qry.=" AND TITLE LIKE '%".DBSafe($title)."%'";
   $out['TITLE']=$title;
  }
  
  global $pattern_category_id;

  if ($pattern_category_id!='') {
   $qry.=' AND patterns.CATEGORY_ID=' . $pattern_category_id;
   $out['PATTERN_CATEGORY_ID']=$pattern_category_id;
  }
  if (IsSet($this->script_id)) {
   $script_id=$this->script_id;
   $qry.=" AND SCRIPT_ID='".$this->script_id."'";
  } else {
   global $script_id;
  }
  // QUERY READY
  global $save_qry;
  if ($save_qry) {
   $qry=$session->data['patterns_qry'];
  } else {
   $session->data['patterns_qry']=$qry;
  }
  if (!$qry) $qry="1";
  // FIELDS ORDER
  global $sortby_patterns;
  if (!$sortby_patterns) {
   $sortby_patterns=$session->data['patterns_sort'];
  } else {
   if ($session->data['patterns_sort']==$sortby_patterns) {
    if (Is_Integer(strpos($sortby_patterns, ' DESC'))) {
     $sortby_patterns=str_replace(' DESC', '', $sortby_patterns);
    } else {
     $sortby_patterns=$sortby_patterns." DESC";
    }
   }
   $session->data['patterns_sort']=$sortby_patterns;
  }
  $sortby_patterns="PRIORITY DESC, TITLE";
  // SEARCH RESULTS
  $res=SQLSelect("SELECT patterns.*,pattern_categories.TITLE AS CATEGORY_NAME,ROW_COLOR FROM patterns LEFT JOIN pattern_categories ON patterns.CATEGORY_ID=pattern_categories.ID WHERE $qry ORDER BY ".$sortby_patterns);
  if ($res[0]['ID']) {
   //colorizeArray($res);
   $total=count($res);
   for($i=0;$i<$total;$i++) {
    // some action for every record if required
   }
   $res=$this->buildTree_patterns($res);
   $out['RESULT']=$res;
  }
  $out['CATEGORIES']=SQLSelect('SELECT * FROM pattern_categories ORDER BY ID');

?>
