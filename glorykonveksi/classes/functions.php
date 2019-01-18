<?

class functions {

	function functions () {
	}

	/*==============================================================*
	*
	* fmtCase() :	Function to change assoc case, eg $row[id],
	*				$row[ID]. Especially for portability againts
	*				Oracle's Upper Case
	*
	*==============================================================*/

	function fmtCase($text) {
		global $assoc_case;

		if ($assoc_case == "lower") $newtext	= strtolower($text);
		else if ($assoc_case == "upper") $newtext	= strtoupper($text);
		return $newtext;

	}

	/*==============================================================*
	*
	* checkaccess() :	Function to check every access to php page
	*
	*==============================================================*/


	/*==============================================================*
	*
	* parseMenuList() : Function to show menu list on MenuMaker.php
	*					 menu list
	*
	*==============================================================*/

	function parseMenuList($level, $under) {

		global $db, $i, $data;

		$data .= "<ul>\n";

		++$i;
		$resultname = "result$i";

		$strSQL			= "SELECT * from tbl_menu WHERE ((lvl=$level) and (under=$under)) order by menuorder asc";
		$$resultname	= $db->Execute($strSQL);
		if (!$$resultname) print $db->ErrorMsg();

		// Display Query
		while ($row = $$resultname->FetchRow())
		{

			$id		= $row[$this->fmtCase('id')];
			$name	= $row[$this->fmtCase('name')];
			$level	= $row[$this->fmtCase('lvl')];
			$under	= $row[$this->fmtCase('under')];
			$order	= $row[$this->fmtCase('menuorder')];

			// Execute the Statement
			++$j;
			$resultname2 = "result2level$j";
			$checklevel = $level + 1;

			$strSQL			= "SELECT * from tbl_menu WHERE lvl=$checklevel and under=$id";
			$$resultname2	= $db->Execute($strSQL);
			if (!$$resultname2) print $db->ErrorMsg();


			$nextparse = "next parseOrganization($checklevel, $id)";

			if ($$resultname2->RecordCount() < 1) {
				$nextparse = "no organization under";
			}

			$data .= "<li><b>$name</b> ( order : $order )     [ <a href=?act=edit&id=$id>edit</a> ] [ <a href=?act=delete&id=$id>hapus</a> ]</li>\n";

			if ($$resultname2->RecordCount() > 0) {
				$this->parseMenuList($checklevel, $id);
			}

		}
		$data .= "</ul>\n";
	}


	/*==============================================================*
	*
	* parseMenu() :	Function to show menu on  left Frame
	*
	*==============================================================*/

	function parseMenu($level, $under) {

		global $db, $i, $data, $access, $departement, $lastClickMenuCookie;

		++$i;
		$resultname = "result$i";

		$strSQL			= "SELECT * from tbl_menu WHERE ((lvl=$level) and (under=$under)) order by menuorder asc";
		$$resultname	= $db->Execute($strSQL);
		if (!$$resultname) print $db->ErrorMsg();


		// Display Query
		while ($row = $$resultname->FetchRow())
		{

			$id					= $row[$this->fmtCase('id')];
			$name				= $row[$this->fmtCase('name')];
			$level				= $row[$this->fmtCase('lvl')];
			$under				= $row[$this->fmtCase('under')];
			$link				= $row[$this->fmtCase('link')];
			$target				= $row[$this->fmtCase('target')];
			$menu_departement	= $row[$this->fmtCase('departement')];
			$menu_access		= $row[$this->fmtCase('menuaccess')];

			//$data .= "<option value=\"$id\">";

			if ($this->checkMenuAccess($menu_access) == 1) {

				if ($under==0) unset($under);

				// Execute the Statement
				++$j;
				$resultname2 = "result2level$j";
				$checklevel = $level + 1;

				$strSQL			= "SELECT * from tbl_menu WHERE lvl=$checklevel and under=$id";
				$$resultname2	= $db->Execute($strSQL);
				if (!$$resultname2) print $db->ErrorMsg();

				$name = str_replace(" ", " ", $name);
				mt_srand((double)microtime()*1000000);
				$randomVal = mt_rand(1000,9999999999);

				if ($$resultname2->RecordCount() > 0) {

					$data .= "menu".$under.".addItem(\"".$name."\");\n";
					$data .= "var menu".$id." = null;\n";
					$data .= "menu".$id." = new MTMenu();\n";

				}

				else if ( !empty($link) ) {

					$data .= "menu".$under.".addItem(\"".$name."\", \"".$link."\", \"".$target."\");\n";

				}

				else {

					$data .= "menu".$under.".addItem(\"".$name."\");\n";

				}


				if (($$resultname2->RecordCount() > 0)) {
					$this->parseMenu($checklevel, $id);

					$data .= "menu".$under.".makeLastSubmenu(menu".$id.");\n";

				}

			}
		}
	}


	/*==============================================================*
	*
	* checkExpanded() : Function to check whether a menu has been
	*					 expanded or not
	*
	*==============================================================*/

	function checkExpanded($under) {

		global $expandedMenuCookie;
		$cookieData = explode("|",$expandedMenuCookie);

		for($i=0;$i<count($cookieData);$i++) {

			if ( $cookieData[$i] == $under ) $expandedChecked = 1;

		}

		return $expandedChecked;
	}

	/*==============================================================*
	*
	* checkMenuAccess() : Function to check accessible menu
	*
	*==============================================================*/

	function checkMenuAccess($menu_access) {
		global $login_access;

		if (!$menu_access) $menuAccessChecked = 1;

		$Data = explode("|",$menu_access);

		for($i=0;$i<count($Data);$i++) {
			if ( $Data[$i] == $login_access ) $menuAccessChecked = 1;
		}

		return $menuAccessChecked;
	}


	/*==============================================================*
	*
	* menuLadder() : Function to get menu's predecessors
	*				  eg. menu1 - menu2 - menu3 on menuMaker.php
	*
	*==============================================================*/

	function menuLadder($id) {

		global $db, $ladder, $parsetimes;

		$strSQL			= "SELECT * from tbl_menu WHERE id=$id";
		$result			= $db->Execute($strSQL);
		if (!$result)	print $db->ErrorMsg();

		$row	= $result->FetchRow();
		$id		= $row[$this->fmtCase('id')];
		$name	= $row[$this->fmtCase('name')];
		$level	= $row[$this->fmtCase('lvl')];
		$under	= $row[$this->fmtCase('under')];

		$level += 1;

		$ladder = $name." - ".$ladder;

		//Check Tree Climbing
		$strSQL			= "SELECT * from tbl_menu WHERE id=$id";
		$result			= $db->Execute($strSQL);
		if (!$result)	print $db->ErrorMsg();

		if ($result->RecordCount() > 0) {
			$underdata = $row[$this->fmtCase('under')];

			if ($underdata > 0) {
				$this->menuLadder($underdata);
			}
		}


	}

	/*==============================================================*
	*
	* createPaging() : Function to create paging
	*
	*==============================================================*/

	function createPaging($table,$cond="",$id="") {

		global $db, $start, $num, $pageFrom, $pageNum, $query, $field;

		if (strlen($cond)) $condString= "WHERE ".$cond;

		$strSQL		= "SELECT * from $table $condString ";
		$result		= $db->Execute($strSQL);
		if (!$result) print $db->ErrorMsg();
		$totalData	= $result->RecordCount();

		$totalPage	= ceil($totalData/$num);
		$sisa		= $totalPage - $pageFrom;

		if ( $sisa < $pageNum ) $pageTo = $pageFrom + $sisa; else $pageTo = $pageFrom + $pageNum;

		if ( ($pageFrom - $pageNum) < 0 ) $pageBefore = 0; else $pageBefore = $pageFrom - $pageNum;
		if ( ($pageFrom + $pageNum) > ($totalPage - $pageNum) ) $pageNext = $totalPage - $pageNum; else $pageNext = $pageFrom + $pageNum;
		if ( $pageNext < 0 ) $pageNext = 0;

		if ( ($totalPage-$pageNum)<0 ) $pageLast = 0; else $pageLast = $totalPage-$pageNum;

		for ($i=$pageFrom; $i<$pageTo; ++$i)  {
			$page_i = $i + 1;
			$next_i = $i * $num;
			if ($next_i == $start) {
				$page .= " <a href=$PHP_SELF?act=list&start=$next_i&num=$num&pageFrom=$pageFrom&pageNum=$pageNum&id=$id&query[]=".rawurlencode($query)."&field[]=$field><b>$page_i</b></a> ";
			} else {
				$page .= " <a href=$PHP_SELF?act=list&start=$next_i&num=$num&pageFrom=$pageFrom&pageNum=$pageNum&id=$id&query[]=".rawurlencode($query)."&field[]=$field>$page_i</a> ";
			}
		}

		$final =	"<a 		href=$PHP_SELF?act=list&start=0&num=$num&pageFrom=0&pageNum=$pageNum&id=$id&query[]=".rawurlencode($query)."&field[]=$field>awal</a>".
		" | <a href=$PHP_SELF?act=list&start=".($pageBefore*$num)."&num=$num&pageFrom=$pageBefore&pageNum=$pageNum&id=$id&query[]=".rawurlencode($query)."&field[]=$field><<</a> ".
		$page.
		" <a href=$PHP_SELF?act=list&start=".($pageNext*$num)."&num=$num&pageFrom=$pageNext&pageNum=$pageNum&id=$id&query[]=".rawurlencode($query)."&field[]=$field>>></a> | ".
		"<a href=$PHP_SELF?act=list&start=".(($totalPage-1)*$num)."&num=$num&pageFrom=".$pageLast."&pageNum=$pageNum&id=$id&query[]=".rawurlencode($query)."&field[]=$field>akhir</a>";

		return $final;
	}



	/*==============================================================*
	*
	* createPagingCustom() : Function to create paging
	* Customised for Absensi
	*
	*==============================================================*/

	function createPagingCustom($table,$cond="",$nik="") {

		global $db, $start, $num, $pageFrom, $pageNum, $query, $field;

		if (strlen($cond)) $condString= "WHERE ".$cond;

		$strSQL		= "SELECT * from $table $condString ";
		$result		= $db->Execute($strSQL);
		if (!$result) print $db->ErrorMsg();
		$totalData	= $result->RecordCount();

		$totalPage	= ceil($totalData/$num);
		$sisa		= $totalPage - $pageFrom;

		if ( $sisa < $pageNum ) $pageTo = $pageFrom + $sisa; else $pageTo = $pageFrom + $pageNum;

		if ( ($pageFrom - $pageNum) < 0 ) $pageBefore = 0; else $pageBefore = $pageFrom - $pageNum;
		if ( ($pageFrom + $pageNum) > ($totalPage - $pageNum) ) $pageNext = $totalPage - $pageNum; else $pageNext = $pageFrom + $pageNum;
		if ( $pageNext < 0 ) $pageNext = 0;

		if ( ($totalPage-$pageNum)<0 ) $pageLast = 0; else $pageLast = $totalPage-$pageNum;

		for ($i=$pageFrom; $i<$pageTo; ++$i)  {
			$page_i = $i + 1;
			$next_i = $i * $num;
			if ($next_i == $start) {
				$page .= " <a href=$PHP_SELF?act=list&start=$next_i&num=$num&pageFrom=$pageFrom&pageNum=$pageNum&nik=$nik&query[]=".rawurlencode($query)."&field[]=$field><b>$page_i</b></a> ";
			} else {
				$page .= " <a href=$PHP_SELF?act=list&start=$next_i&num=$num&pageFrom=$pageFrom&pageNum=$pageNum&nik=$nik&query[]=".rawurlencode($query)."&field[]=$field>$page_i</a> ";
			}
		}

		$final =	"<a 		href=$PHP_SELF?act=list&start=0&num=$num&pageFrom=0&pageNum=$pageNum&nik=$nik&query[]=".rawurlencode($query)."&field[]=$field>awal</a>".
		" | <a href=$PHP_SELF?act=list&start=".($pageBefore*$num)."&num=$num&pageFrom=$pageBefore&pageNum=$pageNum&nik=$nik&query[]=".rawurlencode($query)."&field[]=$field><<</a> ".
		$page.
		" <a href=$PHP_SELF?act=list&start=".($pageNext*$num)."&num=$num&pageFrom=$pageNext&pageNum=$pageNum&nik=$nik&query[]=".rawurlencode($query)."&field[]=$field>>></a> | ".
		"<a href=$PHP_SELF?act=list&start=".(($totalPage-1)*$num)."&num=$num&pageFrom=".$pageLast."&pageNum=$pageNum&nik=$nik&query[]=".rawurlencode($query)."&field[]=$field>akhir</a>";

		return $final;
	}



	/*==============================================================*
	*
	* selectList() : Function to create Select Box List
	*		$name	: name of variable passed through htmlpost
	*		$table	: name of table of DB where the list is taken
	*
	*==============================================================*/

	function selectList($name,$table,$option_name,$value_name,$curr_id,$script="",$cond="",$sql="") {
		global $db;
		$output		 = "<SELECT NAME=$name id=$name $script id=$name class=>\n";
		$output		.= "<option></option>\n";
		if($sql==""){
			$sql="select * from $table $cond ";
			//added by Heri Noviandi 211008
			if (!preg_match("/order/i", $sql)) $sql = $sql ." order by $value_name ";
		}
		$result = $db->Execute("$sql");
		if (!$result){
			print $db->ErrorMsg();
		}
		while ( $row = $result->FetchRow() ) {
			foreach($row as $key=>$val){
				$key=strtolower($key);
				$$key=trim($val);
			}
			if(preg_match("#\smultiple\s#i",$script)){
					$selected=preg_match("#$curr_id#i",$$option_name)?"selected":"";
			}else{
					$selected=(trim($curr_id)==trim($$option_name))?"selected":"";
					
			}
			
			$output .= "<option value=\"".$$option_name."\" $selected>";
#			(($curr_id == $$option_name)?"selected":"").">";
			#.$row[$this->fmtCase($value_name)].
			if(eregi(",",$value_name)){
				$value_name_arr=split(",",$value_name);
				unset($_output);
				for($i=0;$i< count($value_name_arr);$i++){
					$_output .=" ".$$value_name_arr[$i]." |";
				}
				$output .=preg_replace("/\|$/","",$_output);
			}else{
				$output.= $$value_name;
			}
			$output .="</option>\n";
		}
		$result->Close();

		$output .= "</SELECT>\n";
		return $output;
	}

	function radioList($name,$table,$option_name,$value_name,$curr_id,$script="",$cond="",$sql="") {

		global $db;

		if($sql==""){
			$sql="select * from $table $cond ";
		}
		$result = $db->Execute("$sql");
		if (!$result){
			print $db->ErrorMsg();
		}
		while ( $row = $result->FetchRow() ) {
			$i++;
			$_option_name=$row[$this->fmtCase($option_name)];
			$_value_name	=$row[$this->fmtCase($value_name)];

			$output .= "<input type=radio name=$name id=$name$i value='$_option_name' ".(($curr_id==$_option_name)?"checked":"").">
			$_value_name ";
		}
		$result->Close();


		return $output;
	}

	/*==============================================================*
	*
	* selectListArray() : Function to create Select Box List
	*					   from Array
	*
	*==============================================================*/

	function select_list_array($name,$array,$curr_id,$script="") {
		$output		 = "<SELECT NAME=$name id=$name $script><option>\n";

		foreach($array as $value=>$text){
			if($value==$curr_id) $selected="selected";
			$output .= "<option value=\"$value\" $selected>$text</option>";
			unset($selected);
		}

		$output .= "</SELECT>\n";

		return $output;
	}

	/*==============================================================*
	*
	* searchFieldArray() : Function to create Search Form
	*					   from Array
	*
	*==============================================================*/

	function searchFieldArray($option_name,$value_name) {

		global $t;

		for ($i=0; $i<count($option_name); $i++) {

			$output .= "<tr bgcolor=$t->tableColor>
						<td>".$value_name[$i]."</td>
						<td><input type=\"text\" name=\"query[]\"><input type=\"hidden\" name=\"field[]\" value=\"".$option_name[$i]."\"></td>
						</tr>";
		}
		return $output;
	}

	/*==============================================================*
	*
	* processSearch() : Function to process search strings
	*
	*==============================================================*/

	function processSearch($queryArr, $fieldArr) {

		global $query, $field, $condSQL;

		if ( count($queryArr) > 0 ) {
			for ( $i=0; $i<count($queryArr); $i++ ) {
				if (!empty($queryArr[$i])) {
					$searchSQL .= "lower($fieldArr[$i]) like '%".strtolower($queryArr[$i])."%' and ";
					if ( !$query_value ) $query_value = $queryArr[$i];
					if ( !$field_value ) $field_value = $fieldArr[$i];
				}
			}
			if ( !empty($searchSQL) ) {
				$searchSQL	= substr($searchSQL, 0, -5);
				if (!empty($condSQL)) $condSQL	= $condSQL."and ".$searchSQL; else $condSQL = $searchSQL;
			}
		}
		$query	= $query_value;
		$field	= $field_value;

	}



	/*==============================================================*
	*
	* loopTime() : Function to create
	*				Year/Month/Date/Hour/Minutes/second
	*				Select Box List
	*
	*		$date_comp : Y|m|d|H|i|s refer to date function
	*
	*==============================================================*/

	function loopTime($variable_default,$variable_name,$date_comp,$start,$end,$current='',$script=''){

		$showname = substr($variable_name,0,3);

		$output		 = "$showname <select name=$variable_default"."_"."$variable_name $script>\n";
		$output		.= "<option></option>\n";
		for($i=$start;$i<=$end;$i++){

			$x= ($i < 10)?"0$i":"$i";

			if(strlen($current)){
				if($current==$x) $selected="selected";
			}
			$output .= "<option value=$x $selected>$x</option>\n";
			unset($selected,$x);
		}
		$output .= "</select> \n";
		return $output;
	}

	function titleSection($var){
		$img		= $var[img];
		$section	= $var[section];
		$activity	= $var[activity];
		$tableWidth	= (!empty($var[tableWidth]))?$var[tableWidth]:"95%";
		echo"
		<table cellpadding=0 cellspacing=0 bgcolor=white border=0 width=$tableWidth align=center>
				<tr>
					<td style=padding-left:0px>";
		if(!empty($img)) echo"<img src=/i/$img><BR>";
		echo"</td>
					<td width=100% align=left style=padding-left:0px>
					<font size=+1><B>$section";
		echo"» $activity";
		echo"</td>
				</tr>
		</table>
		<BR>		
		";
	}

	function boxTitleSection($msg){
		global $t;
		echo"
                <table border=0 align=center cellpading=5 cellspacing=1 width=100% bgcolor=$t->tableLine>
                <tr>
                        <td>$msg
                        </td>

                </tr>
                </table>
                ";
	}

	function convert_value($var){

		//cs = column search
		//cd = column define
		//vd = value define
		//
		global $db,$f;
		$table =$var[table];
		$vd=strtolower($var[vd]);
		$cd=$var[cd];
		$cond=$var["cond"];
		if(!empty($cond)){

		}elseif(eregi(",",$cd)){
			$cond = $this->parsing_sql_cond("$cd");
		}else{
			$cond = "lower($cd) = '$vd' ";
		}
		$sql = "select $var[cs] as x from $table where $cond ";
		if($var[print_query]=='1')echo $sql;
		$result         = $db->Execute($sql);
		if (!$result){
			echo"$sql";
			print $db->ErrorMsg();
		}
		$row            = $result->FetchRow();
		$new_value      = $row['x'];
		return $new_value;
	}

	function max_id($var){

		//table = table
		//column = column
		global $db,$f;
		$table =$var[table];
		$cond =$var[cond];
		$column= (!empty($var[column]))?"$var[column]":"id";

		$sql = "select max($column) as x from $table $cond";
		if($var[print_query]=='1')echo $sql;
		$result         = $db->Execute($sql);
		if (!$result)   print $db->ErrorMsg();
		$row            = $result->FetchRow();
		$new_value      = $row['x'];
		return $new_value;
	}

	function paging($var){

		global $PHP_SELF;
		if(!empty($var[order])) $order=ttime;
		$num    = $var[num];
		$page   = $var[page];
		$total  = $var[total];
		$show_total=$var[show_total];
		$link	=$var[link];

		$start  = ($page*$num)-$num;
		$word   = $var[word];
		$nomor  = $var[nomor];
		$width	= $var[width]; if(empty($width)) $width="100%";

		$paging=10; # jumlah link paging yang ditampilkan

		#jumlah link paging yang ditampilkan
		$paging=10;
		echo"<table width=$width align=center><tr><td>";

		$bold= ($page == '0')?"1":$page;

		if($page <=0) $page=1;

		if($page != 1){
			echo"<a  href=javascript:void(0); onClick=window.location.href='$link&page=1'>First </a>  <a  href=javascript:void(0); onClick=window.location.href='$link&page=".($page-1)."' title='Previous Page'> &laquo; </a> ";
			#echo"<font class=darkorange style=font-size:11px>
			#<a  href=$link&page=".($page-1)." class=darkorange><< PREVIOUS</a>   ";
		}else{
			echo"First  &laquo; ";
			#echo"<font class=darkorange style=font-size:11px;><< PREVIOUS</a>   ";
		}
		#echo"<h1>Page:$page</h1>";
		#jika halamannya bukan kelipatan 10 kita mulai dari kelipatan 10 paling kecil

		if(($page % $paging == '0') && $page != '0') echo"<B>$page</B> ";


		if($page <10)                   $page = substr($page/10,0,0) ;
		if($page <100 && $page >= 10)   $page = substr($page/10,0,1)."0" ;
		if($page >=100 && $page < 1000) $page = substr($page/10,0,2)."0" ;
		if($page >=1000)                $page = substr($page/10,0,3)."0" ;

		for($pn=$page+1;$pn <= ($paging+$page) ;$pn++){

			if($pn == $bold){
				echo"<font class=darkorange><B>$pn</b>&nbsp</font>";

			}else{
				echo "<a  href=javascript:void(0); onClick=window.location.href='$link&page=$pn' class=darkorange>$pn</a>&nbsp";
			}

			#                if($pn > sprintf("%.0f\n",($total/$num)+0.5)-1)$pn=($paging+$page) ;
			$_pn=$pn;
			if($pn >= sprintf("%.0f\n",ceil($total/$num))) $pn=($paging+$page+1) ;
			if($_pn != sprintf("%.0f\n",ceil($total/$num))) echo "  ";
		}

		if($pn >= $total/$num){
			echo" &raquo; ";
		}else{
			echo" <a  href=javascript:void(0); onClick=\"window.location.href='$link&page=".substr($pn,0,4)."'\"  title='Next Page'> &raquo; </a>";
		}
		echo"<a  href=javascript:void(0); onClick=\"window.location.href='$link&page=".ceil($total/$num)."'\">Last</a> ";
		if($var[show_total]) echo" - Total ".ceil($total/$num)." halaman, $total record";
		echo"</td></tr></table>";

	}

	function n_format($number){
		return number_format($number,"",",",".");
	}

	function box($title,$description,$button="",$type="error",$width=""){


		echo"
	<table cellpadding=0 cellspacing=0 align=center width=$width>
	<tr>
		<td background=/i/bgleft.gif width=3><img src=/i/s.gif></td>
		<td background=/i/bgbox.jpg colspan=2 height=25 style=padding-left:15px;><font color=white><B>$title</td>
		<td bgcolor=#808080><img src=/i/s.gif width=2></td>
	</tr>
	<tr bgcolor=#D4D0C8>
		<td background=/i/bgleft.gif width=3><img src=/i/s.gif></td>
		<td width=75 align=center valign=top><BR>";
		if($type=='error') echo"<img src=/i/stop.gif>";
		if($type=='info') echo"<img src=/i/information.gif>";
		if($type=='warning') echo"<img src=/i/warning.gif>";

		echo"</td>
		<td style=padding-left:20px;padding-right:20px;><font face=arial size=2><BR>$description<P><BR>";

		if(is_array($button)){
			/*example
			$button=array("0"=>array("$PHP_SELF","Kembali"))
			*/
			for($i=0;$i<count($button);$i++){
				if($button[$i][0]=='back'){
					$action="javascript:history.back(-1)";
				}else{
					$action="location.href='".$button[$i][0]."'";
				}
				echo"<input type=button onClick=\"$action\" value='".$button[$i][1]."' ".$button[$i][2]."> ";
			}
		}else{
			echo"<input type=button onClick=history.back(-1); value='&laquo; Kembali'> ";
		}

		echo"<P><BR></td>
		<td bgcolor=#808080><img src=/i/s.gif width=2></td>
	</tr>
	<tr>
		<td background=/i/bgleft.gif width=3><img src=/i/s.gif></td>
		<td bgcolor=#808080 colspan=3 background=/i/bgbottom.gif><img src=/i/s.gif height=3></td>
	</tr>
	</table><P><BR><BR><BR><BR><BR><BR><BR><BR><BR>
	";
		if($type=='error') die();
	}

	function get_formulir_record($msf_formulir,$kso_kasusnomor,$atf_fieldketerangan){
		global $db;

		$atf_fieldketerangan_ori=$atf_fieldketerangan;
		if(eregi(",",$atf_fieldketerangan)){
			$field=split(",",$atf_fieldketerangan);
			unset($atf_fieldketerangan);
			foreach($field as $val){
				$atf_fieldketerangan .="'".$val."',";
			}
			$atf_fieldketerangan = preg_replace("#,$#","",$atf_fieldketerangan);
			$atf_fieldketerangan = preg_replace("#^\'|\'$#","",$atf_fieldketerangan);
		}
		$atf_fieldketerangan=strtolower($atf_fieldketerangan);
		$sql="select a.* from
		wf_formulirattribute a,wf_formulirfield b
                where a.atf_attributekode=b.atf_attributekode and b.msf_formulirkode='$msf_formulir'
                and a.atf_fieldlokasi is NOT NULL and atf_flagaktif='1' and lower(atf_fieldketerangan) in ('$atf_fieldketerangan')
		order by atf_fieldketerangan asc
		";	
		$result=$db->Execute($sql);
		if(!$result) print $db->ErrorMsg();
		while($row=$result->FetchRow()){
			$atf_fieldketerangan    = $row[ATF_FIELDKETERANGAN];
			$atf_fieldlokasi        = $row[ATF_FIELDLOKASI];
			$atf_formulatipe        = $row[ATF_FORMULATIPE];
			$atf_formulaparameter   = $row[ATF_FORMULAPARAMETER];
			$atf_fieldtipe          = $row[ATF_FIELDTIPE];

			$ftd_attribute=preg_replace("/attr/i","ftd_attribute",$atf_fieldlokasi);
			$ftd_attribute=strtoupper($ftd_attribute);
			$sql="select $ftd_attribute from wf_opformulirtransaksidata where fto_nomor in (
	                               select fto_nomor from wf_opformulirtransaksi where kso_kasusnomor='$kso_kasusnomor' and
	 				msf_formulirkode='$msf_formulir')
	                       ";
			$result_ftd=$db->Execute($sql);
			if(!$result_ftd) print $db->ErrorMsg();
			$row_ftd=$result_ftd->FetchRow();
			$_ftd_attribute=$row_ftd[$ftd_attribute];

			$$atf_fieldketerangan=$_ftd_attribute;

			if(eregi(",",$atf_fieldketerangan_ori)){
				$return_value[$atf_fieldketerangan] = $$atf_fieldketerangan;
			}else{
				$return_value=$$atf_fieldketerangan;
			}
		}

		$return_value=preg_replace("#,$#","",$return_value);
		return $return_value;

	}
	function get_case_record($kso_kasusnomor,$jnk_jeniskasuskode,$attribute_nama){
		global $db;
		$atk_array=array();
		$sql="select ATK_ATTRIBUETNAMA,ATK_LOKASIDATA from wf_kasusattribute where jnk_jeniskasuskode='$jnk_jeniskasuskode' and
		atk_attribuetnama in ('$attribute_nama')";
		$result_dynamic=$db->Execute($sql);
		if(!$result_dynamic) print $db->ErrorMsg();
		$row_dynamic=$result_dynamic->FetchRow();
		$atk_lokasidata                 = $row_dynamic[ATK_LOKASIDATA];
		$atk_lokasidata                 = strtoupper($atk_lokasidata);

		$sql                    = "select $atk_lokasidata from wf_opkasusattribute where kso_kasusnomor='$kso_kasusnomor'";
		$resultattr             = $db->Execute($sql);
		if(!$result) print $db->ErrorMsg();
		$rowattr                = $resultattr->FetchRow();
		$result			= $rowattr["$atk_lokasidata"];
		return $result;

	}
	function bar_level1($bar_title){
		echo"<table width=100% align=center>
	<tr>
		<td class=bar_title>$bar_title</td>
	</tr>
	</table>
	<br clear=left>
	";

	}

	function bar_level2($bar_title){
		global $bar_level2_table;
		if(empty($bar_level2_table)) $bar_level2_table="100%";
		echo"
		<table width=$bar_level2_table align=center cellpadding=0 cellspacing=0 border=0>
		<tr>
			<td class=bar_title_small>$bar_title</td>
		</tr>
		</table>
	";

	}
	function bar_level3($bar_title){
		global $bar_level3_table;
		if(empty($bar_level2_table)) $bar_level3_table="100%";
		echo"
		<table width=$bar_level3_table align=center cellpadding=0 cellspacing=0 border=0>
		<tr>
			<td class=bar_title3>$bar_title</td>
		</tr>
		</table>
	";

	}

	function quick_search($query){

		echo"
	<table width=95% border=0 cellspacing=0 cellpadding=3 align=center>
	<form method=post>
	<input type=hidden name=start value='0'>
	<tr bgcolor=d4d0c8>
		<td><img src=/i/search.gif></td> 
		<td width=14%>Query</td> 
		<td width=86%><input type=text name=query value='$query' size=30></td>
	</tr>
	<tr>
		<td width=14% class=td_one colspan=2> </td> 
		<td width=86% class=td_one><input type=image src=/i/cari.gif></td> 
	</tr>
	</form>
	</table>";



	}

	function bar_add_record($table_width="95%",$link=""){

		global $PHP_SELF;

		if(is_array($link)){
			$link_add	= $link[add];
			$link_index	= $link[index];
		}else{
			$link_add	= "$PHP_SELF?act=add";
			$link_index	= "$PHP_SELF";
		}

		echo"<BR>
	<table width=$table_width cellpadding=2 cellspacing=1 align=center>
	<tr><td align=right>
	<a href=$link_index><img src=/i/index.gif border=0></a> 
	<a href=$link_add><img src=/i/tambah_data.gif border=0></a>
	</td></tr></table>
	";
	}

	function generate_kso_kasusnomor(){
		global $db;
		$kso_kasusnomor=$this->generate_nomorkolom("wf_opkasus","kso_kasusnomor","OPK");
		return $kso_kasusnomor;
		/*
		$strSQL	= "select cast(substr(kso_kasusnomor,5,6) as number)+1 as nomerurut from wf_opkasus where
		kso_kasusnomor like 'OPK-%' order by cast(substr(kso_kasusnomor,5,6) as number) desc";
		$result	= $db->SelectLimit($strSQL,1,0);
		if (!$result) echo $db->ErrorMsg();
		$row = $result->FetchRow();
		$kso_kasusnomor = $row["NOMERURUT"];
		if(empty($kso_kasusnomor)) $kso_kasusnomor="1";
		$kso_kasusnomor="OPK-".sprintf("%06d",$kso_kasusnomor);
		*/
	}


	function generate_nomorkolom($table,$column,$prefix){
		global $db;
		$column=strtoupper($column);
		$table=strtoupper($table);
		/*
		$sql="select new_prefix from tbl_new_prefix where tablename='$table'";
		$result	= $db->SelectLimit($sql,1,0);
		if (!$result) echo $db->ErrorMsg();
		$row = $result->FetchRow();
		$new_prefix=$row['NEW_PREFIX'];
		if(!empty($new_prefix)) $prefix=$new_prefix;
		*/

		$strSQL	= "select cast(substr($column,5,6) as bigint)+1 as nomerurut
		from $table where $column like '$prefix%'
		order by cast(substr($column,5,6) as bigint) desc";
		$result	= $db->SelectLimit($strSQL,1,0);
		if (!$result) echo $db->ErrorMsg();
		$row = $result->FetchRow();
		$nomor = $row["nomerurut"];
		if(empty($nomor)) $nomor="1";
		//26 aug 2010. kalau primary key-nya lebih dari 999 ganti prefix incremental 1 letter
		if($nomor >= 500000){
			$third_char=substr($prefix,2,1);
			if(strtolower($third_char)=='z'){
				$second_char=substr($prefix,1,1);
				$second_char=$this->incremental_letter($second_char);
				$next_prefix=substr($prefix,0,1).$second_char.'A';
			}else{
				$third_char=$this->incremental_letter($third_char);
				$next_prefix=substr($prefix,0,2).$third_char;
			}
			$next_prefix=strtoupper($next_prefix);
			/*
			$sql="select tablename from tbl_new_prefix where tablename='$table'";
			if($this->check_exist_value($sql)==true){
			$sql="update tbl_new_prefix set new_prefix='$next_prefix',ctime=sysdate,last_counter='$nomor' where tablename='$table'";
			}else{
			$sql="insert into tbl_new_prefix (tablename,column_name,old_prefix,new_prefix,last_counter,ctime) values ('$table','$column','$prefix','$next_prefix','$nomor',sysdate)";
			}
			$result=$db->Execute($sql);
			if (!$result){
			print $db->ErrorMsg();
			}
			*/
			$counter_date=date("d/m/Y H:i:s");
			$this->logfile(array("filename"=>"exceeded_counter.log","message"=>"$counter_date::$table::$column::$prefix::$nomor::$next_prefix\r\n"));
		}
		$nomor="$prefix-".sprintf("%06d",$nomor);
		return $nomor;
	}

	function incremental_letter($letter){
		$letter=ord($letter);            //Convert to an integer
		if($letter=='122') $letter='96';#z
		$letter=chr($letter+1);            //Convert back to a string, but the
		return $letter;
	}

	function convert_date($ksotgl,$format="0"){
		$ksotgl=split("-",substr($ksotgl,0,10));
		if(count($ksotgl)>= 3){

			if($format=='0'){
				$tanggal=$ksotgl[1]."/".$ksotgl[2]."/".$ksotgl[0];
			}elseif($format=='1'){
				$tanggal=$ksotgl[2]."/".$ksotgl[1]."/".$ksotgl[0];
			}
		}
		if($tanggal=='01/01/1900') unset($tanggal);
		return $tanggal;
	}
	function convert_date_id($date,$format="0"){
		if($format=='0'){
			if(eregi("-",$date)){
				$date=split("-",$date);
				$tahun= $date[0];
				$bulan= $date[1];
				$hari = (int) $date[2];
			}elseif(eregi("\/",$date)){
				$date=split("/",$date);
				$tahun= $date[2];
				$bulan= $date[0];
				$hari = (int) $date[1];
			}
		}elseif($format=='1'){
			if(eregi("-",$date)){
				$date=split("-",$date);
				$tahun= $date[0];
				$bulan= $date[1];
				$hari = (int) $date[2];
			}elseif(eregi("\/",$date)){
				$date=split("/",$date);
				$tahun= $date[2];
				$bulan= $date[1];
				$hari = (int) $date[0];
			}

		}
		$bulan_array=array(
		"01"=>"Januari",
		"02"=>"Februari",
		"03"=>"Maret",
		"04"=>"April",
		"05"=>"Mei",
		"06"=>"Juni",
		"07"=>"Juli",
		"08"=>"Agustus",
		"09"=>"September",
		"10"=>"Oktober",
		"11"=>"November",
		"12"=>"Desember");
		if(count($date)>= 3){
			$tanggal=$hari." ".$bulan_array[$bulan]." ".$tahun;
		}
		return $tanggal;
	}

	function count_total($table,$cond=""){
		global $db;
		$sql="select count(1) as TOTAL from $table $cond";
		$result_total=$db->Execute("$sql");
		if(!$result_total){
			echo"$sql";
			print $db->ErrorMsg();
		}
		#                echo"$sql";
		$row_total=$result_total->FetchRow();
		$total=$row_total[TOTAL];
		return $total;
	}

	function sum($table,$column,$cond=""){
		global $db;
		$sql="select sum($column) as TOTAL from $table $cond";
		$result_total=$db->Execute("$sql");

		if(!$result_total){
			#echo"$sql";
			print $db->ErrorMsg();
		}
		$row_total=$result_total->FetchRow();
		$total=$row_total[TOTAL];
		return $total;
	}
	function logfile($array){
		$filename	= $array['filename'];
		$message= $array[message];
		global $DOCUMENT_ROOT;

		$filename=$DOCUMENT_ROOT."/i/log/$filename";
		#echo"$filename";
		$fp=fopen($filename, "ab+");
		if($fp){
			fputs($fp, stripslashes("$message"));
			fclose($fp);
		}
		return basename($filename);
	}

	function sendsms($array){
		$msisdn	= $array[msisdn];
		$message= $array[message];
		global $DOCUMENT_ROOT;

		$filename="D:/library/smsserver/gammu_win32/win32/smsd/outbox/OUT+$msisdn".".txt";
		#echo"$filename";
		$fp=fopen($filename, "wb+");
		if($fp){
			fputs($fp, stripslashes("$message"));
			fclose($fp);
		}
		return basename($filename);
	}

	
		
	function standard_buttons($array=""){
		global $PHP_SELF;global $display;
		global $standar_button_off;
		$add_parameter		= $array["add_parameter"];
		$refresh_parameter	= $array["refresh_parameter"];
		$add_button			= $array["add_button"];
		$menu				= $array["menu"];

		#if(eregi("riwayat_grad",$PHP_SELF)) echo"menu: $menu";
		if($add_button == '0')$standar_button_off="1";

		echo"
		<table class=default><tr><td align=right>
		";

		if($menu) echo"$menu";
		if(empty($standar_button_off) && $standar_button_off !='0') $standar_button_off='0';
		if($standar_button_off == '0' || $add_button=='1'){
			echo"<a href='$PHP_SELF?act=add&display=$display&".$add_parameter."'><img src=/images/button_tambah.gif border=0></a>";
		}
		echo"<a href=$PHP_SELF?".$refresh_parameter."><img src=/images/button_refresh.gif border=0></a>
		</td></tr></table>";
	}
	function search_box($query="",$input_hidden="",$advance_search_param=""){
		global $advance_search;
		global $type;
		echo"
		<table class=search width=100%>
			<input type=hidden name=start value='0'>
			<input type=hidden name=page value='1'>
			<input type=hidden name=type value='$type'>
			$input_hidden
			<tr class=bgSearch>
				<td align=right>
					<!--<img src=/i/icon_search.png>-->
					<form method=post name=f2 id='searchbox'>
					<input id='search' type=text name=query placeholder='pencarian' value=\"$query\">
					<input id='submit' type=submit value='  Search  '>
					</form>";
					if($advance_search == "1") echo"<a href=# onClick=openAdvanceSearchBox(1);>Advance Search</a>";
			echo"</td>
			</tr>
		</table>
		
		";
	}
	function search_box1($query="",$input_hidden="",$advance_search_param=""){
		global $advance_search;
		global $type;
		echo"
		<table class=search width=100%>
			<input type=hidden name=start value='0'>
			<input type=hidden name=page value='1'>
			<input type=hidden name=type value='$type'>
			$input_hidden
			<tr class=bgSearch>
				<td align=right>
					<!--<img src=/i/icon_search.png>-->
					<form method=post name=f2 id='searchbox'>
					<input id='search' type=text name=query placeholder='cari berdasarkan judul' value=\"$query\">
					<input id='submit' type=submit value='  Search  '>
					</form>";
					if($advance_search == "1") echo"<a href=# onClick=openAdvanceSearchBox(1);>Advance Search</a>";
			echo"</td>
			</tr>
		</table>
		
		";
	}
	function insert_log($activity,$sql="",$nip=""){
		global $db;
		global $_COOKIE;
		global $REMOTE_ADDR;
		if(!empty($nip)){
			$login_id = $nip;
		}else{
			$login_id = $_COOKIE["login_nip"];
		}

		$ctime=date("m/d/Y H:i:s");
		$sql=base64_encode($sql);
		$sql="insert into tbl_log (user_id,activity,ctime,ip,detail) values ('$login_id','$activity',getdate(),'$REMOTE_ADDR','$sql')";
		$result=$db->Execute($sql);
		if (!$result){
			print $db->ErrorMsg();
			echo $sql;
		}
	}

	function result_message($message){
		global $PHP_SELF;
		echo"
		<table class=index><tr><td>
		<p class=judul>Result:</p>
		$message
		<p>
		</td></tr></table>
		";
	}

	function primary_key($primary_key){
		if(eregi(",",$primary_key)){
			$cond_arr=split(",",$primary_key);
			foreach($cond_arr as $key){
				global $$key;
				if (!$$key) $$key=0;
				$cond_primary_key .=" $key='".$$key."' and";
			}
			$cond_primary_key=preg_replace("#and$#i","",$cond_primary_key);
		}else{
			global $$primary_key;
			if(!$$primary_key) $$primary_key=0;
			$cond_primary_key="$primary_key ='".$$primary_key."'";
		}
		return $cond_primary_key;
	}

	function get_record_array($array){
		//example
		//$f->get_record_array(array("table"=>"spd_jenisangkutan","key"=>"jnsang","value"=>"nmangk"));
		global $db;
		$table=$array["table"];
		$key	=strtoupper($array["key"]);
		$value=strtoupper($array["value"]);
		$sql="select $key,$value from $table";
		$result=$db->Execute($sql);
		if(!$result) print $db->ErrorMsg();
		$return=array();
		while($row=$result->FetchRow()){
			$_key		=$row[$key];
			$_value	=$row[$value];
			$return[$_key]=$_value;
		}
		return $return;

	}
	function get_record_by_value($table,$primary_key){
		global $db;
		if(eregi(",",$primary_key)){
			$cond_arr=split(",",$primary_key);
			foreach($cond_arr as $key){
				global $$key;
				$cond_primary_key .=" $key='".$$key."' and";
			}
			$cond_primary_key=preg_replace("#and$#i","",$cond_primary_key);
		}else{
			global $$primary_key;
			$cond_primary_key="$primary_key ='".$$primary_key."'";
		}
		$sql="select * from $table where $cond_primary_key";
		#echo"$sql<HR>";
		$result=$db->Execute("$sql");

		if(!$result) print $db->ErrorMsg();
		$row=$result->FetchRow();
		$array=array();
		if(is_array($row)){
			foreach($row as $key=>$val){
				$key=strtolower($key);
				$array[$key]=$val;
			}
		}
		#print_r($array);
		return $array;
	}
	function get_last_record($sql){
		global $db;
		$result=$db->SelectLimit($sql,1,0);
		if(!$result) {
			echo"<font color=red>$sql</font>";
			print $db->ErrorMsg();
		}

		$row=$result->FetchRow();
		$array=array();
		if(is_array($row)){
			foreach($row as $key=>$val){
				$key=strtolower($key);
				$$key= $val;
				$array[$key]=$val;
			}
		}
		return $array;
	}


	function check_exist_value($sql){
		global $db;
		if(empty($sql)) die("Query tidak boleh kosong");
		$result=$db->SelectLimit("$sql",1,0);
		if(!$result) print $db->ErrorMsg();
		$row=$result->FetchRow();
		$array=array();
		if(is_array($row)){
			foreach($row as $key=>$val){
				$key=strtolower($key);
				$array[$key]=$val;
			}
		}

		if(count($array) > 0) {
			return true;
		}else{
			return false;
		}
	}
	function parsing_sql_cond($cond){
		if(eregi(",",$cond)){
			$cond=split(",",$cond);
			foreach($cond as $key){
				global $$key;
				$column .=" $key='".$$key."' and";
			}
			$column=preg_replace("/(and)$/i","","$column");
		}else{
			global $$cond;
			$column ="$cond='".$$cond."' ";
		}
		return $column;
	}
	function input_text($array){
		$name	=$array[name];
		$value	=$array[value];
		$size	=$array[size];
		$cond	=$array[cond];
		$format	=$array[format];
		$action	=$array[action];
		$input ="<input type=text name='$name' value='$value' size='$size'>";
		return $input;
	}
	function tr($array){


	}

	function input_date($name,$default_value,$form_name="f1",$size="8",$class=""){
		#sebelumnya input_date($name,$default_value,$form_name="f1",$size="8",$readonly,$class=""){
		#edit ewing, 06agustus2009, samain dengan 84, attribute readonly masuk ke $class saja.

		#".($readonly=='true'?"readonly":"")."
		$result = "<input type=text name=\"$name\" size=$size value=\"$default_value\"  $class>";
		#if(empty($readonly))$readonly=false;
		#if($readonly==false){
		$result .="<a href=javascript:void(0) onClick=if(self.gfPop)gfPop.fPopCalendar(document.$form_name.$name);return false; hidefocus>
    		           <img name=popcal align=absmiddle src=/css/calbtn.gif border=0 alt=''>
    		           <font style=font-size:10px;>format: dd/mm/yyyy</font></a>";
		#}

		return $result;
	}

	/*
	function detailPegawai($nip){
	global $db;
	global $f;
	$result=array();
	$result[nip]	= $nip;
	$sql		= "select kd_jabatan_str,kd_jabatan_fung,kd_unit_org,kd_status_kepeg,kd_kantor,kd_pangkat,kd_eselon,sts_pensiun as pensiun,
	thn_reog_jabatan as thn_reog_jabatan
	from spg_data_current where nip='$nip' ";
	$resultdb		= $f->get_last_record($sql);
	foreach($resultdb as $key=>$val) {
	$result[$key]=$val;
	$$key=$val;
	}
	$thn_reog	= $f->convert_value(array("table"=>"spg_reog","cs"=>"thn_reog","cd"=>"default_reog","vd"=>"1"));
	$nm_peg		= $f->convert_value(array("table"=>"spg_pegawai","cs"=>"nm_peg","cd"=>"nip","vd"=>"$nip"));

	$result[nm_peg]	= $nm_peg;
	$result[thn_reog] = $thn_reog;

	$sql		= "select nm_pangkat,kd_gol from spg_pangkat where kd_pangkat='$kd_pangkat' and thn_reog='$thn_reog'";
	$resultdb	= $f->get_last_record($sql);
	foreach($resultdb as $key=>$val){
	$$key=$val;
	$result[$key]=$val;
	}
	#print_r($result);
	$sql		= "select nm_golongan from spg_golongan where kd_gol='$kd_gol' and thn_reog='$thn_reog'";
	$resultdb	= $f->get_last_record($sql);
	foreach($resultdb as $key=>$val) $result[$key]=$val;
	$sql		= "select nm_kantor from spg_kantor where kd_kantor='$kd_kantor' and thn_reog='$thn_reog'";
	$resultdb	= $f->get_last_record($sql); foreach($resultdb as $key=>$val) $result[$key]=$val;
	$sql		= "select nm_unit_org from spg_unit_organisasi where kd_unit_org='$kd_unit_org' and thn_reog='$thn_reog_jabatan'";
	$resultdb	= $f->get_last_record($sql); foreach($resultdb as $key=>$val) $result[$key]=$val;

	if(!empty($kd_jabatan_str)){
	$sql		= "select nm_jabatan_str as nm_jabatan from spg_jabatan_str where kd_jabatan_str='$kd_jabatan_str' and thn_reog='$thn_reog_jabatan'";
	$resultdb	= $f->get_last_record($sql);
	foreach($resultdb as $key=>$val) $result[$key]=$val;
	$result["jenis_jabatan"]="struktural";
	}elseif(!empty($kd_jabatan_fungsional)){
	$sql		= "select nm_jabatan_fung as nm_jabatan from spg_jabatan_fungsional where kd_jabatan_fung='$kd_jabatan_fung' and thn_reog='$thn_reog_jabatan'";
	$resultdb	= $f->get_last_record($sql);
	foreach($resultdb as $key=>$val) $result[$key]=$val;
	$result["jenis_jabatan"]="fungsional";
	}
	return $result;
	}

	function get_unit_organisasi($kd_unit_org,$thn_reog){
	global $db;
	$sql		= "select nm_unit_org from spg_unit_organisasi where kd_unit_org='$kd_unit_org' and thn_reog='$thn_reog'";
	$resultdb	= $this->get_last_record($sql);
	$nm_unit_org	= $resultdb["nm_unit_org"];
	return $nm_unit_org;
	}
	*/

	function detailPegawai($nip){
		global $db;
		global $f;
		$result=array();
		$result[nip]	= $nip;
		$sql		= "select nip,nm_peg,nip_baru,nm_pend_umum,kd_jabatan_str,kd_jabatan_fung,kd_unit_org,kd_status_kepeg,kd_kantor,kd_pangkat,kd_eselon,sts_pensiun as pensiun,
				thn_reog_jabatan as thn_reog_jabatan
				from spg_data_current where nip='$nip' ";
		#echo"$sql<HR>";
		$resultdb		= $f->get_last_record($sql);
		foreach($resultdb as $key=>$val) {
			$result[$key]=$val;
			$$key=$val;
		}
		$thn_reog	= $f->convert_value(array("table"=>"spg_reog","cs"=>"thn_reog","cd"=>"default_reog","vd"=>"1"));
		$nm_peg		= $f->convert_value(array("table"=>"spg_pegawai","cs"=>"nm_peg","cd"=>"nip","vd"=>"$nip"));

		$result[nm_peg]	= $nm_peg;
		$result[thn_reog] = $thn_reog;

		$sql		= "select nm_pangkat,kd_gol from spg_pangkat where kd_pangkat='$kd_pangkat' ";
		$resultdb	= $f->get_last_record($sql);
		foreach($resultdb as $key=>$val){
			$$key=$val;
			$result[$key]=$val;
		}
		#print_r($result);
		$sql		= "select nm_golongan from spg_golongan where kd_gol='$kd_gol' and thn_reog='$thn_reog'";
		$resultdb	= $f->get_last_record($sql);
		foreach($resultdb as $key=>$val) $result[$key]=$val;
		$sql		= "select nm_kantor from spg_08_kantor where kd_kantor='$kd_kantor'";
		//echo $sql;
		$resultdb	= $f->get_last_record($sql); foreach($resultdb as $key=>$val) $result[$key]=$val;
		//print_r($result);
		$sql		= "select nm_unit_org from spg_08_unit_organisasi where kd_unit_org='$kd_unit_org'";
		$resultdb	= $f->get_last_record($sql); foreach($resultdb as $key=>$val) $result[$key]=$val;



		if(!empty($kd_jabatan_str)){
			$sql		= "select nm_jabatan_str as nm_jabatan from spg_08_jabatan_str where kd_jabatan_str='".substr($kd_jabatan_str,0,3)."'";
			$resultdb	= $f->get_last_record($sql);
			foreach($resultdb as $key=>$val) $result[$key]=$val;
			/*
			$sql		= "select nm_unit_org as nm_unit_org from spg_08_unit_organisasi where kd_unit_org='".substr($kd_jabatan_str,3,10)."'";
			$resultdb	= $f->get_last_record($sql);
			foreach($resultdb as $key=>$val) $result[$key]=$val;

			$result['nm_jabatan'] .= " - ".$result['nm_unit_org'];
			*/

			$result["jenis_jabatan"]="struktural";
		}


		if(!empty($kd_jabatan_fung)){

			$sql		= "select nm_jabatan_fung as nm_jabatan from spg_08_jabatan_fungsional where kd_jabatan_fung='$kd_jabatan_fung' ";

			/*
			if($_SERVER['REMOTE_ADDR']=='10.254.36.160'){
			echo $sql;

			}
			*/

			$resultdb	= $f->get_last_record($sql);
			foreach($resultdb as $key=>$val) $result[$key]=$val;
			$result["jenis_jabatan"]="fungsional";
		}


		return $result;
	}

	function get_unit_organisasi($kd_unit_org,$thn_reog){
		global $db;
		$sql		= "select nm_unit_org from spg_08_unit_organisasi where kd_unit_org='$kd_unit_org'";
		$resultdb	= $this->get_last_record($sql);
		$nm_unit_org	= $resultdb["nm_unit_org"];
		return $nm_unit_org;
	}

	function logOut($link=""){
		if(empty($link)) $link="/index.php?act=logout";
		echo"
			<script language=Javascript>
			window.open(\"$link\",\"_parent\");
			</script>";
	}
	function redirect($delay="2",$link="",$message=""){
		if(empty($link)) $link="/logout.php";

		echo"<html><head>
		      <META HTTP-EQUIV=\"Refresh\" Content = \"$delay; URL=$link\">
		      </head><body bgcolor=#FFFFFF><font face=verdana size=2 color=#000000>$message
			</body></html>";

	}
	function checkJenis($kd_unit_org,$list_unit_org){
		$_panjang_string=strlen($kd_unit_org);
		for($i=0;$i<$_panjang_string;$i++){

			$panjang_string	= strlen($kd_unit_org);
			$chunk		= $panjang_string-1;
			$_kd_unit_org	= substr($kd_unit_org,0,$panjang_string-$i);
			$_kd_unit_org	= "$_kd_unit_org".str_repeat("0",$i);


			if(in_array("$_kd_unit_org",$list_unit_org)){
				$i="100";
			}
		}
		return $_kd_unit_org;
	}

	function tooltip($message){
		$output .="
		<a href=# onMouseover=\"ddrivetip('Keterangan:<BR><B>$message<B>', 200)\";
		onMouseout=\"hideddrivetip()\"><img src=/i/help1.gif border=0></a>
		";

		return $output;
	}


	function checkaccess()  {
		global $act;
		global $PHP_SELF;
		global $db;
		global $_COOKIE;
		#global $fua_name;
		global $REMOTE_ADDR;
		#global $login_nip;

		$current_page=strtolower($PHP_SELF);
		$result_check=$this->get_function_access();
		#if($login_nip=='060109562') echo "<h1>fua_name t: $fua_name</h1>";

		$fua_name=$result_check["fua_name"];

		if (!$fua_name) $fua_name="default";

		#if($login_nip=='060109562') echo "<h1>fua_name s: $fua_name</h1>";

		if(empty($_COOKIE["login_nip"]) && (!preg_match("/\/index\.php/i",$PHP_SELF) && !eregi("login1|logout",$act))) {
			$message="Anda tidak mempunyai akses terhadap halaman ini..Silakan login kembali...";
			$this->redirect("2","/logout.php",$message);
			exit;
		}
		//check apakah session_nya aktif?
		$time= explode( " ", microtime());
		$timeNow= (double)$time[1];

		$result_session=$this->get_last_record("select username,last_access,status from tbl_session where session_id='".$_COOKIE["login_session"]."'");
		$last_access		= $result_session["last_access"];
		$session_username		= $result_session["username"];
		$session_status		= $result_session["status"];
		//ilham patch security hole 080508
		if($session_username!=$_COOKIE['login_nip']){
			$message="<font style=background-color:EBEBEB;color:FFFFFF>Maaf Informasi Session Anda Tidak Valid. Silakan Login kembali...</font>";
			$ctime=date('d-m-Y H:i:s');
			if($session_username){
				$logmessage="NIP $session_username , cookie :".$_COOKIE['login_nip']." ip :".$REMOTE_ADDR." CTIME: ".$ctime."\n";
				#$body="Ada percobaan modifikasi cookie. $logmessage";
				@mail('erwin.danuaji@gmail.com','[sikka]Security Issues',"$body");
				$this->logfile(array("filename"=>"IDS.txt","message"=>$logmessage));

			}

			$sql="delete from tbl_session where session_id='".$_COOKIE["login_session"]."'";
			$result=$db->Execute($sql);
			if(!$result) print $db->ErrorMsg();
			$this->redirect("2","/logout.php",$message);
			die();
		}
		if($session_status=='0'){ // session di disabled karena ada user dengan login yang sama, login kembali!
			$sql="select ip,ctime from tbl_session where status='1' and username='".$_COOKIE["login_nip"]."'";
			$result=$db->Execute($sql);
			if(!$result) print $db->ErrorMsg();
			$row=$result->FetchRow();
			$ip=$row["IP"];
			$ctime=$row["CTIME"];
			$message="<font style=font-size:16px;>Session Expired.</font> User $_COOKIE[login_nip]  login dari $ip pada jam $ctime.
			<P>Silahkan Login Kembali...<i><small><font color=c0c0c0>Sistem akan otomatis redirect ke halaman login dalam 15 detik</small></i>";
			$sql="delete from tbl_session where username='".$_COOKIE[login_nip]."' and status='0'";
			$result=$db->Execute($sql);
			if(!$result) print $db->ErrorMsg();
			$this->redirect("15","/logout.php",$message);
			exit;

		}

		if(empty($session_username)){
			$message="Maaf Session telah berakhir (<i>Expired</i>).Silahan Login kembali...";
			$this->redirect("15","/logout.php",$message);
			exit;

		}
		//check apakah user idle selama lebih dari 1 jam?
		$diff   = $timeNow-$last_access;
		$limit  = 60*45;//harusnya 15 menit, tapi sementara pasang 30 menit/1/2 jam dahulu, biar gak shock

		if($diff>$limit){
			$message="Maaf status anda idle lebih dari 30 menit dan session Anda telah expired. Silakan Login kembali...";
			$this->redirect("4","/logout.php",$message);
			exit;
		}

		//check apakah cookie user dengan passwordnya sama?


		//Check apakah file terdapat dalamCHECK APAKAH FILE TERDAPAT DALAM DAFTAR URL DI TABLE MENU? KALAU TIDAK DI KELUARIN SAJA!
		$sql="select men_nomorurut from wf_menu where lower(men_url) like '$current_page%'";

		$result=$db->Execute($sql);
		if(!$result) print $db->ErrorMsg();
		$row=$result->FetchRow();
		$men_nomorurut=$row["MEN_NOMORURUT"];

		if(!empty($men_nomorurut) && !preg_match("/^(\/index\.php|\/check_thang\.php)/i",$PHP_SELF)
		&& !preg_match("/demo/i",$_COOKIE["login_grup"])){


			$result_access=$this->get_allow_access("$fua_name");
			$fua_add	= $result_access["fua_add"];
			$fua_edit	= $result_access["fua_edit"];
			$fua_delete	= $result_access["fua_delete"];
			$fua_read	= $result_access["fua_read"];
			if(!is_array($fua_add)) 	$fua_add=array();
			if(!is_array($fua_edit)) 	$fua_edit=array();
			if(!is_array($fua_delete)) $fua_delete=array();
			if(!is_array($fua_read)) 	$fua_read=array();
			$button=array(
			"0"=>array("back","Back","target=_parent")
			);

			#print_r($fua_read);

			if(preg_match("/do_add|add|save_add/i",$act)){
				if(!in_array("$men_nomorurut",$fua_add)) $this->box("Error!","Anda tidak memiliki otorisasi melihat halaman ini.",$button,"error","600");
			}elseif(preg_match("/do_update|update|edit|do_edit|save_edit/i",$act)){
				if(!in_array("$men_nomorurut",$fua_edit)) $this->box("Error!","Anda tidak memiliki otorisasi melihat halaman ini.",$button,"error","600");
			}elseif(preg_match("/delete/i",$act)){
				if(!in_array("$men_nomorurut",$fua_delete)) $this->box("Error!","Anda tidak memiliki otorisasi melihat halaman ini.",$button,"error","600");
			}else{
				if(!in_array("$men_nomorurut",$fua_read)) $this->box("Error!","Anda tidak memiliki otorisasi melihat halaman ini.",$button,"error","600");
			}

		}
		$sql="update tbl_session set last_access='$timeNow' where session_id='".$_COOKIE["login_session"]."'";
		$result=$db->Execute($sql);
		if(!$result) print $db->ErrorMsg();


	}


	function get_session($session_name){
		global $db;
		global $_COOKIE;
		$session_name=strtolower(trim($session_name));
		$result_session=$this->get_last_record("select $session_name from tbl_session where session_id='".$_COOKIE["login_session"]."'");
		$value=$result_session["$session_name"];
		return $value;

	}

	function get_function_access(){
		global $db;
		global $_COOKIE;
		$result_session=$this->get_last_record("select username from tbl_session where session_id='".$_COOKIE["login_session"]."'");
		$nip=$result_session["username"];

		#$nip	= $_COOKIE["login_nip"];
		$result				= $this->detailPegawai($nip);
		$kd_jabatan_str		= $result["kd_jabatan_str"];
		$kd_unit_org		= $result["kd_unit_org"];

		#ewing 10-02-2012, format hris 2 dipisahkan didata current, kd_unit_org dan kd_jabatan_str, tidak digabung lagi!
		$kd_jabatan=$kd_jabatan_str.$kd_unit_org;

		/*
		$kd_jabatan_fung	= $result["kd_jabatan_fung"];
		if(!empty($kd_jabatan_fung)){
		$kd_jabatan=$kd_jabatan_fung;
		}else{
		$kd_jabatan=$kd_jabatan_str;
		}

		//check apakah merupakan pjs? jika ya. set jabatan pjs
		*/
		$login_kd_jabatan_pjs=$this->get_session("login_kd_jabatan_pjs");
		if(!empty($login_kd_jabatan_pjs)) $kd_jabatan=$login_kd_jabatan_pjs;

		$sql			= "select * from tbl_role where kd_jabatan='".trim($kd_jabatan)."'";

		$result		= $this->get_last_record("$sql");
		return $result;
	}
	function get_allow_access($fua_name){
		global $db;
		$sql="select * from wf_functionaccess where fua_name='$fua_name'";
		$result=$db->Execute($sql);
		if(!$result) print $db->ErrorMsg();
		$array_result=array();
		while($row=$result->FetchRow()){
			foreach($row as $key=>$val){
				$key=strtolower($key);
				$$key=$val;
			}
			if($fua_read=='1')   $array_result["fua_read"][]=$men_nomorurut;
			if($fua_delete=='1') $array_result["fua_delete"][]=$men_nomorurut;
			if($fua_add=='1')    $array_result["fua_add"][]=$men_nomorurut;
			if($fua_edit=='1')   $array_result["fua_edit"][]=$men_nomorurut;

		}
		return $array_result;
	}

	function get_inquiry_access($wia_inquiryname){
		global $db;
		global $_COOKIE;
		$function_access=$this->get_function_access();
		$wia_accessname=$function_access["wia_accessname"];
		$sql="select wia_inquiryaccess from wf_inquiry_access
			where lower(wia_inquiryname)='".strtolower($wia_inquiryname)."'
			and wia_accessname='$wia_accessname'";
		$result=$db->Execute($sql);
		if(!$result) print $db->ErrorMsg();
		$row=$result->FetchRow();
		$wia_inquiryaccess=$row['wia_inquiryaccess'];
		return $wia_inquiryaccess;


	}
	/*
	function check_inquiry_access($wia_inquiryname,$wia_inquiryaccess){
	global $db;
	global $_COOKIE;
	$function_access=$this->get_function_access();
	$wia_accessname=$function_access["wia_accessname"];
	$sql="select wia_id from wf_inquiry_access where lower(wia_inquiryname)='".strtolower($wia_inquiryname)."' and
	lower(wia_inquiryaccess)='".strtolower($wia_inquiryaccess)."' and wia_accessname='$wia_accessname'";
	$result=$db->Execute($sql);
	if(!$result) print $db->ErrorMsg();
	$row=$result->FetchRow();
	$wia_id=$row[WIA_ID];
	if(!empty($wia_id)){
	return true;
	}else{
	return false;
	}

	}
	*/
	function check_inquiry_access($wia_inquiryname,$wia_inquiryaccess){
		global $db;
		global $_COOKIE;
		$function_access=$this->get_function_access();
		$wia_accessname=$function_access["wia_accessname"];
		//ewing 02-09-2008
		if(!eregi("\|",$wia_inquiryaccess)){
			$cond_wia_inquiryaccess ="and lower(wia_inquiryaccess)='".strtolower($wia_inquiryaccess)."'";
		}else{
			$wia_inquiryaccess_array=split("\|",$wia_inquiryaccess);
			foreach($wia_inquiryaccess_array as $key){
				$key_list .="'$key',";
			}
			$key_list=preg_replace("#,$#","",$key_list);
			$cond_wia_inquiryaccess =" and lower(wia_inquiryaccess) in ($key_list) ";
		}
		$sql="select wia_id from wf_inquiry_access where lower(wia_inquiryname)='".strtolower($wia_inquiryname)."'
		$cond_wia_inquiryaccess
		and wia_accessname='$wia_accessname'";
		#		echo"$sql<HR>";
		$result=$db->Execute($sql);
		if(!$result) print $db->ErrorMsg();
		$row=$result->FetchRow();
		$wia_id=$row[WIA_ID];
		if(!empty($wia_id)){
			return true;
		}else{
			return false;
		}

	}
	function generate_menu_hirarki(){

	}
	function generate_menu_hirarki_old(){

		global $PHP_SELF;
		global $f;
		global $act;
		global $db;

		$result=$this->get_function_access();
		$fua_name=$result["fua_name"];
		$allowed_page=$this->get_allow_access($fua_name);

		$path=pathinfo("$PHP_SELF");
		$dirname=$path["dirname"];
		//check dari wf_menu apakah ada yang menggunakan hirarki?
		$result=$f->get_last_record("select men_nomorurut from wf_menu where men_basishirarki='$dirname' and men_hirarki='1'");
		$men_nomorurut=$result["men_nomorurut"];
		if(!empty($men_nomorurut)){

			/*===========================
			LEVEL 1
			============================*/
			$sql="select men_judul,men_url,men_keterangan,men_nomorurut from wf_menu where men_referensi='$men_nomorurut' and men_tampil='1'
			order by men_nomorurut";
			$result=$db->Execute($sql);
			if(!$result) print $db->ErrorMsg();

			while($row=$result->FetchRow()){
				foreach($row as $key=>$val){
					$key=strtolower($key); $$key=$val;
				}
				#check_parent("$");
				$style="style=\"background-color:EBEBEB;\"";
				if(in_array("$men_nomorurut",$allowed_page["fua_read"])) $output1 .="<li><a href=\"#\" target=main class=daddy>$men_judul</a>\n";
				/*===========================================
				LEVEL 2
				=============================================*/

				$sql="select men_judul,men_url,men_keterangan,men_nomorurut from wf_menu where men_referensi='$men_nomorurut' and men_tampil='1' order by men_nomorurut";
				$result1=$db->Execute($sql);
				if(!$result1) print $db->ErrorMsg();
				while($row1=$result1->FetchRow()){
					#foreach($row1 as $key=>$val){
					#	$key=strtolower($key); $$key=$val;
					#}
					if(in_array($row1["MEN_NOMORURUT"],$allowed_page["fua_read"])) $output2 .="<li><a href=\"$row1[MEN_URL]\">".$row1["MEN_JUDUL"]." </a>\n";
					/*=====================================
					LEVEL 3
					======================================*/
					$sql="select men_judul,men_url,men_keterangan,men_nomorurut from wf_menu where men_referensi='".$row1["MEN_NOMORURUT"]."' and men_tampil='1' order by men_nomorurut";
					$result2=$db->Execute($sql);
					if(!$result2) print $db->ErrorMsg();

					while($row2=$result2->FetchRow()){
						foreach($row2 as $key=>$val){
							$key=strtolower($key); $$key=$val;
						}

						if(in_array("$row2[MEN_NOMORURUT]",$allowed_page["fua_read"])) $output3 .="<li><a href=\"$men_url\">$men_judul</a></li>";
						/*=====================================
						LEVEL 4
						======================================*/
						$sql="select men_judul,men_url,men_keterangan,men_nomorurut from wf_menu where men_referensi='$men_nomorurut' and men_tampil='1' order by men_nomorurut";
						$result3=$db->Execute($sql);
						if(!$result3) print $db->ErrorMsg();

						while($row3=$result3->FetchRow()){
							foreach($row3 as $key=>$val){
								$key=strtolower($key); $$key=$val;
							}
							if(in_array("$men_nomorurut",$allowed_page["fua_read"])) $output4 .="<li><a href=\"$men_url\">$men_judul</a></li>\n";
						}

						if(!empty($output4)) $output3 .="<ul>$output4</ul>";
						if(in_array($row2["MEN_NOMORURUT"],$allowed_page["fua_read"])) $output3 .="</li>";
						unset($output4);

					}
					if(!empty($output3)) $output2 .="<ul>$output3</ul>";
					if(in_array($row1["MEN_NOMORURUT"],$allowed_page["fua_read"])) $output2 .=" </li>";
					unset($output3);
				}
				if(!empty($output2)) $output1 .="<ul>$output2</ul>";
				$output1 .="</li>";
				unset($output2);
			}

			if(!empty($output1)) $output .="<div id=\"menu\"><ul>$output1</ul></div>";
			unset($output1);
		}
		if(!empty($output)){
			echo"
			<script type=\"text/javascript\" language=\"javascript\" charset=\"utf-8\" src=\"/js/suckerfish.js\"></script>	
			$output";
		}
		#print_r($result);

	}
	function ribuan($angka){
		$angka = number_format($angka,"0",",",".");
		return $angka;
	}
	function terbilang($bilangan) {

		$angka = array('0','0','0','0','0','0','0','0','0','0',
		'0','0','0','0','0','0');
		$kata = array('','satu','dua','tiga','empat','lima',
		'enam','tujuh','delapan','sembilan');
		$tingkat = array('','ribu','juta','milyar','triliun');

		$panjang_bilangan = strlen($bilangan);

		/* pengujian panjang bilangan */
		if ($panjang_bilangan > 15) {
			$kalimat = "Diluar Batas";
			return $kalimat;
		}

		/* mengambil angka-angka yang ada dalam bilangan,
		dimasukkan ke dalam array */
		for ($i = 1; $i <= $panjang_bilangan; $i++) {
			$angka[$i] = substr($bilangan,-($i),1);
		}

		$i = 1;
		$j = 0;
		$kalimat = "";


		/* mulai proses iterasi terhadap array angka */
		while ($i <= $panjang_bilangan) {

			$subkalimat = "";
			$kata1 = "";
			$kata2 = "";
			$kata3 = "";

			/* untuk ratusan */
			if ($angka[$i+2] != "0") {
				if ($angka[$i+2] == "1") {
					$kata1 = "Seratus";
				} else {
					$kata1 = $kata[$angka[$i+2]] . " ratus";
				}
			}

			/* untuk puluhan atau belasan */
			if ($angka[$i+1] != "0") {
				if ($angka[$i+1] == "1") {
					if ($angka[$i] == "0") {
						$kata2 = "Sepuluh";
					} elseif ($angka[$i] == "1") {
						$kata2 = "Sebelas";
					} else {
						$kata2 = $kata[$angka[$i]] . " belas";
					}
				} else {
					$kata2 = $kata[$angka[$i+1]] . " puluh";
				}
			}

			/* untuk satuan */
			if ($angka[$i] != "0") {
				if ($angka[$i+1] != "1") {
					$kata3 = $kata[$angka[$i]];
				}
			}

			/* pengujian angka apakah tidak nol semua,
			lalu ditambahkan tingkat */
			if (($angka[$i] != "0") OR ($angka[$i+1] != "0") OR
			($angka[$i+2] != "0")) {
				$subkalimat = "$kata1 $kata2 $kata3 " . $tingkat[$j] . " ";
			}

			/* gabungkan variabe sub kalimat (untuk satu blok 3 angka)
			ke variabel kalimat */
			$kalimat = $subkalimat . $kalimat;
			$i = $i + 3;
			$j = $j + 1;

		}

		/* mengganti satu ribu jadi seribu jika diperlukan */
		if (($angka[5] == "0") AND ($angka[6] == "0")) {
			$kalimat = str_replace("satu ribu","Seribu",$kalimat);
		}

		return trim($kalimat);

	}

	function get_server_var($name, $default) {
		global $HTTP_SERVER_VARS, $HTTP_ENV_VARS;
		if( !is_string($name) || empty($name) )
		{
			return $default;
		}
		if( isset($_SERVER[$name]) )
		{
			return $_SERVER[$name];
		}
		else if( isset($_ENV[$name]) )
		{
			return $_ENV[$name];
		}
		else if( isset($HTTP_SERVER_VARS[$name]) )
		{
			return $HTTP_SERVER_VARS[$name];
		}
		else if( isset($HTTP_ENV_VARS[$name]) )
		{
			return $HTTP_ENV_VARS[$name];
		}
		else if( @getenv($name) !== false )
		{
			return getenv($name);
		}
		return $default;
	}

	function get_user_ip() {
		global $client_ip;
		if( isset($client_ip) )
		{
			return $client_ip;
		}
		global $HTTP_CLIENT_IP;
		global $HTTP_X_FORWARDED_FOR;
		global $HTTP_FROM;
		global $REMOTE_ADDR;

		$PIP = '([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])';
		$UIP = '';
		$CIP = $this->get_server_var('HTTP_CLIENT_IP', false);
		$FIP = $this->get_server_var('HTTP_X_FORWARDED_FOR', false);
		$MIP = $this->get_server_var('HTTP_FROM', false);
		$RIP = $this->get_server_var('REMOTE_ADDR', false);
		if( $CIP && $RIP )
		{
			$UIP = $CIP;
		}
		else if( $FIP && $RIP )
		{
			$UIP = $FIP;
		}
		else if( $MIP && $RIP )
		{
			$UIP = $MIP;
		}
		else if( $RIP ){
			$UIP = $RIP;
		}
		/*
		$matches = array();
		if( !empty($UIP) && preg_match_all('#'.$PIP.'\.'.$PIP.'\.'.$PIP.'\.'.$PIP.'#',$UIP, $matches) ) {
		$found = array();
		print_r($matches);

		for( $i=0, $l=count($matches[0]); $i<$l; $i++ ){
		if( !preg_match('#^(10|172\.(1[6-9]|2[0-9]|3[0-1])|192\.168)\.#', $matches[0][$i]) )
		{
		$found = array($matches[1][$i], $matches[2][$i], $matches[3][$i], $matches[4][$i]);
		echo"found: $found<HR>";
		break;
		}
		}
		$client_ip = (count($found) == 4) ? sprintf('%d.%d.%d.%d', $found[0], $found[1], $found[2], $found[3]) : '';
		unset($found);
		echo"client_ip: $client_ip";
		} else {
		$client_ip = '';
		}
		#	die();
		#	unset($matches);
		unset($PIP, $UIP, $CIP, $FIP, $MIP, $RIP);
		*/
		$client_ip=$UIP;
		if( empty($client_ip) ){
			die("You are online using invalid IP address $RIP $REMOTE_ADDR");
		}
		return $client_ip;
	}
	function get_key() {

		#global $rm;
		$rm= "http://localhost/file.key";
		@$handle = fopen ($rm, "rb");
		$contents = "";
		do {
			@$data = fread($handle, 8192);
			if (strlen($data) == 0) {
				break;
			}
			$contents .= $data;
		} while(true);
		@fclose ($handle);

		return $contents;

	}
	function workflow_sp($name,$current,$dir=""){
		global $DOCUMENT_ROOT;
		if(empty($dir)) $dir="$DOCUMENT_ROOT/operasional/sp";
		$select .="<select name=$name><option>";
		$handle = opendir("$dir");
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$value  =strtoupper(trim(preg_replace("#sp_|_|\.php#i"," ",$file)));
				if($value==$current) $selected=" selected style=background-color:ffdd00";
				$select .= "<option$selected>$value</option>\n";
				unset($selected);
			}
		}

		closedir($handle);
		$select .="</select>";
		return $select;
	}

	function upload_wf_input_file($file_array){
		global $DOCUMENT_ROOT;
		global $HTTP_POST_FILES;
		$input	=$file_array["input"];
		$dirfile	=$file_array["dirfile"];
		$filename	=$file_array["filename"];
		move_uploaded_file($HTTP_POST_FILES["$input"]["tmp_name"],"$dirfile/$filename") or die("upload gagal. periksa permission direktori");
		$path=eregi_replace("$DOCUMENT_ROOT","","$dirfile/$filename");
		return $path;
	}

	function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

	function loadtime(){
		global $time_start;
		$time_end = $this->getmicrotime();
		$time = $time_end - $time_start;
		$time = sprintf("%01.3f", $time);
		return $time;
	}
	function create_report($filename){
		$fp = fopen($filename, 'rb');
		if ($fp) {
			do {
				$data = fread($fp, 8192);
				if (strlen($data) == 0) {
					break;
				}
				$contents .= $data;
			} while(true);
			fclose($fp);

		} else {
			echo"gagal buka file";
		}

		preg_match_all("#«(.*)»#i",$contents,$contents_array);
		foreach($contents_array[0] as $key=>$val){
			$_val=preg_replace("/«|»/","",$val);
			global $$_val;
			$_value=$$_val;
			$contents=preg_replace("/$val/",$_value,$contents) or die("$val");
		}
		global $HTTP_HOST;
		#$contents=preg_replace("#\<v\:imagedata(.*)\<\/v\:imagedata>#is","<img src=http://$HTTP_HOST/images/logo_print.jpg alt=''>",$contents);
		#$contents=preg_replace("#\<v\:imagedata#is","<img src=http://$HTTP_HOST/images/logo_print.jpg alt=''><v\:xmagedata",$contents);
		$contents=eregi_replace("host_image_source","http://$HTTP_HOST",$contents);

		return $contents;
	}

	function draw_graph($percentage="0",$width="126"){
		$graph .="
		<table width=$width height=13 border=0 cellpadding=0 cellspacing=0>
		<tr>
	        	<td colspan=3><img src=/i/polltop.gif width=$width height=2></td>
		</tr>
		<tr>
			<td width=2 height=9><img src=/i/pollleft.gif width=2 height=9></td>
			<td width=122 height=9><table height=9 border=0 cellpadding=0 cellspacing=0 width=".$percentage."%>
	                          <tr>
	                            <td width=1><img src=/i/pollresultleft.gif width=1 height=9></td>
	                            <td background=/i/pollresultmid.gif><img src=/i/tr.gif width=1 height=9></td>
	                            <td width=1><img src=/i/pollresultright.gif width=1 height=9></td>
	                          </tr>
	                        </table></td>
			<td width=2><img src=/i/pollright.gif width=2 height=9></td>
		</tr>
		<tr>
			<td colspan=3><img src=/i/pollbot.gif width=$width height=2></td>
		</tr>
		</table>

		";
		return $graph;

	}
	function note($message){
		echo"
		<fieldset>
				<legend><img src=/images/button_help.gif align=bottom></legend>
				<div style='padding:5px 5px 5px 5px;'>$message</div>
				</fieldset>
				";

	}
	function tab_top_sub_menu($sub_menu,$attribute=""){
		global $no_padding;
		global $base_url;
		global $PHP_SELF;
		if(empty($base_url)) $base_url=$PHP_SELF;
		global $act;
		echo"
		<P><BR><table BORDER=0 cellpadding=0 cellspacing=0 width=100%>
		<tr>";

		foreach($sub_menu as $key=>$val){
			//key
			unset($menu);
			if(eregi("&",$key)){
				$key_array=split("\&",$key);
				$_act=$key_array[0];
			}else{
				$_act=$key;
			}
			#			echo"$_act | $key | $val<BR>";
			if(is_array($attribute)){
				if(!empty($attribute["$val"])){
					$menu=$attribute[$val]['color'];
					$font_color=$attribute[$val]['font_color'];
				}
			}
			if(preg_match("#^($_act)$#i",$act)){
				if(empty($menu)) $menu="FFFFFF";
				$font_weight="<B>";
				if(empty($font_color)) $font_color="#000000";
				$style="border:1px solid #CCCCCC;background: red url(/images/bg_submenu_on.jpg) top left repeat-x;";
			}else{
				if(empty($menu)) $menu="#898989";
				$font_weight="";
				if(empty($font_color)) $font_color="#FFFFFF";
				$style="";
			}

			echo"<td height=30 bgcolor=\"$menu\" style=\"text-align:center;padding-left:10px;padding-right:10px;$style;\"><a
			href=\"$base_url?act=$key\"><font color=\"$font_color\">$font_weight $val</a></td>
			<td bgcolor=FFFFFF width=5><img src=/i/s.gif width=4></td>";
			$j++;
		}
		echo"
		<td bgcolor=FFFFFF width=50%> </td>
		</tr>
		<tr>
			<td height=5 bgcolor=CCCCCC colspan=".(($j*2)+2)."><img src=/i/s.gif ></td>
		</tr>";
		if(!$no_padding || $no_padding=='0'){
			echo"
			<tr>
			<td height=14 bgcolor=FFFFFF colspan=".(($j*2)+2)."> </td>
			</tr>
			";
		}
		echo"</table>";
	}

	function writefile($array){
		$filename   = $array['filename'];
		$message    = $array[message];

		global $DOCUMENT_ROOT;

		$filename=$DOCUMENT_ROOT."/exportsql/$filename";
		#echo"$filename";
		$fp=fopen($filename, "ab+");

		if($fp){
			fputs($fp, stripslashes("$message"));
			fclose($fp);
		}
		return basename($filename);
	}
	function pre($input,$die='0'){
		echo"<pre>";
		print_r($input);
		echo"</pre>";
		if($die=='1')die();

	}
	function masa_kerja_golongan($nip,$kd_pangkat=""){
		//hitung masa kerja
		global $db;
		if(!empty($kd_pangkat)) $cond=" and kd_pangkat='$kd_pangkat'";
		$sql="select
				 floor((sysdate-tmt_pang_peg)/365) as masa_kerja_tahun,
				 TRUNC(MONTHS_BETWEEN(SYSDATE, TMT_PANG_PEG)) - (floor((sysdate-tmt_pang_peg)/365)*12) as masa_kerja_bulan
				 from
				 spg_riwayat_kepangkatan where nip='$nip' $cond order by
				tmt_pang_peg asc";
		#			$this->pre($sql);
		$result=$this->get_last_record($sql);
		$masa_kerja_tahun=$result["masa_kerja_tahun"];
		$masa_kerja_bulan=$result["masa_kerja_bulan"];
		$return =array("$masa_kerja_tahun","$masa_kerja_bulan");
		return $return;
	}

	/*
	function cari_atasan($nip,$level="1"){
	global $db;global $f;

	$sql="select kd_unit_org,kd_jabatan_str,kd_kantor from spg_data_current where nip='$nip'";
	$result=$f->get_last_record($sql);
	$kd_unit_org=$result["kd_unit_org"];
	$kd_jabatan_str=$result["kd_jabatan_str"];
	$kd_kantor=$result["kd_kantor"];
	if(!empty($kd_jabatan_str) && !empty($kd_unit_org)){
	if(substr($kd_jabatan_str,0,1)=='6'){
	$jabatan_atasan="4";
	}else{
	$jabatan_atasan=substr($kd_jabatan_str,0,1) - 1;
	}
	$jabatan_atasan=$jabatan_atasan-$level+1;
	$panjang_string=$jabatan_atasan*2;
	$_kd_unit_org=substr($kd_unit_org,0,$panjang_string);

	$sql="select nip,nm_peg from spg_data_current
	where substr(kd_unit_org,1,$panjang_string)='$_kd_unit_org' and kd_kantor='$kd_kantor'
	and substr(kd_jabatan_str,0,1)='$jabatan_atasan' and sts_pensiun='0'";
	$result=$f->get_last_record($sql);
	$nip_atasan=$result["nip"];
	return $nip_atasan;
	}else{
	return;
	}
	}
	*/
	function cari_atasan($nip,$level="1"){
		global $db;global $f;

		$sql="select kd_unit_org,kd_jabatan_str,kd_kantor from spg_data_current where nip='$nip'";
		$result=$f->get_last_record($sql);
		$kd_unit_org=$result["kd_unit_org"];
		$kd_jabatan_str=$result["kd_jabatan_str"];
		$kd_kantor=$result["kd_kantor"];
		if(!empty($kd_jabatan_str) && !empty($kd_unit_org)){
			if(substr($kd_jabatan_str,0,1)=='6'){
				$jabatan_atasan="4";
			}else{
				$jabatan_atasan=substr($kd_jabatan_str,0,1) - 1;
			}
			$jabatan_atasan=$jabatan_atasan-$level+1;
			$panjang_string=$jabatan_atasan*2;
			$_kd_unit_org=substr($kd_unit_org,0,$panjang_string);
			$_kd_kantor=substr($kd_kantor,0,$panjang_string);

			if(substr($kd_jabatan_str,0,1)<='3'){
				$cond="where substr(kd_kantor,1,$panjang_string)='$_kd_kantor'";
			}elseif(substr($kd_kantor,7,2)<>'00'){
				$cond="where substr(kd_kantor,1,$panjang_string)=substr('$kd_kantor',1,$panjang_string)";
			}else{
				$cond="where substr(kd_unit_org,1,$panjang_string)='$_kd_unit_org' and substr(kd_kantor,1,8)='".substr($kd_kantor,0,8)."'";
			}

			$sql="select nip,nm_peg from spg_data_current
						$cond
						and substr(kd_jabatan_str,0,1)='$jabatan_atasan' and sts_pensiun='0'";
			#$f->pre($sql);
			$result=$f->get_last_record($sql);
			$nip_atasan=$result["nip"];
			return $nip_atasan;
		}else{
			if(substr($kd_kantor,-4)=='0000'){
				$cond="and substr(kd_jabatan_str,1,1)=3";
			}else{
				$cond="and substr(kd_jabatan_str,1,1)=2";
			}
			$sql="select nip,nm_peg from spg_data_current where sts_pensiun='0' and kd_kantor='$kd_kantor' $cond";
			#       if($nip=='060109970') echo"$sql";
			$result=$f->get_last_record($sql);
			$nip_atasan=$result["nip"];
			return $nip_atasan;
		}
	}


	function cari_pejabat($kd_unit_org,$kd_kantor,$eselon="4"){
		global $db;global $f;
		if($eselon > 4 || empty($eselon)){
			$message="pegawai tidak ditemukan. hanya untuk pejabat eselon dibawah IV ";
			return $message;
		}else{

			$panjang_string	=	$eselon*2;
			$_kd_unit_org	=	substr($kd_unit_org,0,$panjang_string);
			$_kd_kantor		=	substr($kd_kantor,0,$panjang_string);
			$sql="select nip,nm_peg from spg_data_current
								where substr(kd_unit_org,1,$panjang_string)='$_kd_unit_org'
								and substr(kd_kantor,1,$panjang_string)='$_kd_kantor'
								and substr(kd_jabatan_str,0,1)='$eselon' and sts_pensiun='0'";
			$result=$f->get_last_record($sql);
			$nip=$result["nip"];
			return $nip;
		}
	}
	/*
	function normalisasi_nama_jabatan($nm_jabatan){
	$nm_jabatan=preg_replace("/[\s]+/"," ",$nm_jabatan);
	$nm_jabatan=preg_replace("/seksi\sseksi/i","Seksi",$nm_jabatan);
	$nm_jabatan=preg_replace("/bagian\sbagian/i","Bagian",$nm_jabatan);
	$nm_jabatan=preg_replace("/subbag\ssub\sbagian/i","Subbag",$nm_jabatan);
	$nm_jabatan=preg_replace("/subdit\ssub\sdirektorat/i","Subdit",$nm_jabatan);
	$nm_jabatan=preg_replace("/bidang\sbidang/i","Bidang",$nm_jabatan);
	$nm_jabatan=preg_replace("/kpp\spratama/i","",$nm_jabatan);
	$nm_jabatan=preg_replace("/sekretaris\ssekretaris/i","Bidang",$nm_jabatan);

	$nm_jabatan=preg_replace("/kantor\swilayah\smodern/i","",$nm_jabatan);


	return $nm_jabatan;
	}
	*/
	function normalisasi_nama_jabatan($nm_jabatan){
		$nm_jabatan=preg_replace("/[\s]+/"," ",$nm_jabatan);
		$nm_jabatan=preg_replace("/\//"," ",$nm_jabatan);
		$nm_jabatan=preg_replace("/seksi\sseksi/i","Seksi",$nm_jabatan);
		$nm_jabatan=preg_replace("/direktorat\s/i","",$nm_jabatan);
		$nm_jabatan=preg_replace("/subdirektorat\s/i","",$nm_jabatan);
		$nm_jabatan=preg_replace("/subbagian\s/i","",$nm_jabatan);
		$nm_jabatan=preg_replace("/sekretariat/i","Direktorat",$nm_jabatan);
		$nm_jabatan=preg_replace("/bagian\sbagian/i","Bagian",$nm_jabatan);
		$nm_jabatan=preg_replace("/subbag\ssub\sbagian/i","Subbag",$nm_jabatan);
		$nm_jabatan=preg_replace("/subdit\ssub\sdirektorat/i","Subdit",$nm_jabatan);
		$nm_jabatan=preg_replace("/kpp\spratama/i","",$nm_jabatan);
		$nm_jabatan=preg_replace("/kp2kp\skp2kp/i","KP2KP",$nm_jabatan);
		$nm_jabatan=preg_replace("/pusat\spusat/i","Pusat",$nm_jabatan);
		$nm_jabatan=preg_replace("/kpp\swp\sbesar\smadya/i","",$nm_jabatan);
		$nm_jabatan=preg_replace("/kanwil\skantor\swilayah/i","Kantor",$nm_jabatan);
		$nm_jabatan=preg_replace("/kanwil\skantor\swilayah\swp\sbesar\skhusus/i","Kantor",$nm_jabatan);
		$nm_jabatan=preg_replace("/direktur\sdirektorat/i","Direktur",$nm_jabatan);

		$nm_jabatan=preg_replace("/kepala\skantor/i","Kepala KPP Pratama",$nm_jabatan);
		$nm_jabatan=preg_replace("/kepala\skpp\spratama\skpp/i","Kepala KPP",$nm_jabatan);
		return $nm_jabatan;
	}
	function kirim_pesan($tpe_untuk,$tpe_subyek ,$tpe_pesan,$tpe_dari=""){
		global $f;
		global $_COOKIE;
		global $db,$REMOTE_ADDR;
		if(empty($tpe_dari)) $tpe_dari = $_COOKIE["login_nip"];
		$tpe_id=$f->generate_nomorkolom("tbl_pesan","tpe_id","TPE");
		$columns .="tpe_id,tpe_subyek,tpe_dari,tpe_tanggal,tpe_untuk,tpe_pesan,tpe_status,tpe_untuk_grup,ip,tpe_prioritas";
		$values 	.="'$tpe_id','$tpe_subyek','ADMIN',sysdate,'$tpe_untuk','$tpe_pesan','0','5','$REMOTE_ADDR','1'";

		$sql="insert into tbl_pesan  ($columns) values ($values)";
		$result=$db->Execute($sql);

		if (!$result){
			print $db->ErrorMsg();
			die($sql_insert);
		}
	}
	/*
	function upload_file($array){

	#	$this->pre($array);
		$dir		=$array['dir'];
		$change_file=$array['change_file'];
		$path_old	=$array['path_old'];
		$act		=$array['act'];
		$input_name	=$array['input_name'];
		$extension	=$array['extension'];
		$keyid		=$array['keyid'];# id table
		$table		=$array['table'];# table reference for spg_sipeg_pictures
		$lk			=$array['lk'];#1 untuk proses diproses di lk.
		$nip		=$array['nip'];
		$table		=strtolower($table);
		$pk			=$array['pk']; #primary key
		$max_size	=$array['max_size'];
		$debug	=$array['debug'];
		$rep_id	=$array['rep_id'];
		$recruitment	= $array['recruitment'];
		if(empty($change_file)) $change_file=2; #default replace
		global $db,$f;
		global $HTTP_POST_FILES;

		global $DOCUMENT_ROOT,$REMOTE_ADDR,$login_nip,$REQUEST_URI,$PHP_SELF;
		if(($act=='do_update' && $change_file=='2') || $act=='do_add'){ //change file
			$originalname	=$HTTP_POST_FILES[$input_name]['name'];
			$filename		=time()."_".$HTTP_POST_FILES[$input_name]['name'];
			$filesize		=$HTTP_POST_FILES[$input_name]['size'];
			$filename=preg_replace("/\'|\s|\"|\-/","_",strtolower($filename));
			if(empty($dir)){
				$dir ="$DOCUMENT_ROOT/i/upload";
			}else{
				$dir =$DOCUMENT_ROOT."$dir";
			}
			if($debug==1) $this->pre($HTTP_POST_FILES);
			if($debug==1) $this->pre($array);
			#if(empty($originalname)){
			#	$error .="<li>Upload file yang anda inginkan";
			#}
			if(eregi("\.php",$filename)){
				$this->logfile(array("filename"=>"ilegal_upload.log","message"=>"$REMOTE_ADDR,$login_nip,$REQUEST_URI $PHP_SELF"));
				die("Error: file extensi ini tidak diperbolehkan untuk diupload ke server!");
			}
			if(!empty($extension) && !empty($originalname)){
				if(!preg_match("/($extension)$/",$filename)) $error .="<li>file extensi ini tidak diperbolehkan untuk diupload ke server! pastikan hanya file dengan ekstensi ".preg_replace("#\|#",",",$extension);
			}
			if(!empty($max_size)){
				if($filesize > $max_size) $error .="<li>file ini memiliki ukuran ".round(($filesize/1000))." kb,lebih besar dari ukuran yang diperbolehkan  maks ".round(($max_size/1000))." kb";

			}
			if($error) $f->box("error","error:<ul>$error</ul>","","error","600");
			if(!empty($originalname)) {
				move_uploaded_file($HTTP_POST_FILES[$input_name]['tmp_name'],"$dir/$filename") or die("upload gagal. periksa permission direktori $dir/$filename");
				$path=eregi_replace("$DOCUMENT_ROOT","","$dir/$filename");
			}

		}elseif($act=='do_update' && $change_file=='3'){ //delete
			if(empty($path_old)&& $lk==1){
				$sql="select path as path_old from spg_sipeg_pictures where ".(!empty($pk)?"$pk='$keyid'":"keyid='$keyid'")." and lower(tablename)='$table'";
				$path_old=$db->getOne($sql);
			}
			//$path_old=$f->convert_value(array("table"=>"","cs"=>"path","cd"=>"dik_id","vd"=>"$dik_id"));
			@unlink("$DOCUMENT_ROOT$path_old");
			$path="";
		}
		if($lk==1){
			$this->upload_file_lk_db(array(
			"act"=>"$act",
			"table"=>"$table",
			"path"=>"$path",
			"change_file"=>$change_file,
			"id"=>$keyid,
			"nip"=>"$nip",
			"pk"=>$pk,
			"originalname"=>"$originalname",
			"filesize"=>"$filesize",
			"debug"=>$debug
			));
		}
#		$debug=1;
#		echo"<h1>debug: $debug</h1>";
		if($recruitment==1){
			$this->upload_file_recruitment(array(
			"act"=>"$act",
			"table"=>"$table",
			"path"=>"$path",
			"change_file"=>$change_file,
			"id"=>$keyid,
			"nip"=>"$nip",
			"rep_id"=>"$rep_id",
			"pk"=>$pk,
			"originalname"=>"$originalname",
			"filesize"=>"$filesize",
			"debug"=>$debug
			));
		}
		return $path;
	}
	*/
	
	function upload_file_recruitment($array){
		global $db,$f;
		global $HTTP_POST_FILES;
		global $DOCUMENT_ROOT,$REMOTE_ADDR,$login_nip,$REQUEST_URI,$PHP_SELF;

		$dir			= $array['dir'];
		$act			= $array['act'];
		$input_name		= $array['input_name'];
		$field			= $array['field'];
		$change_file	= $array['change_file'];
		$debug			= $array['debug'];
		$rep_id			= $array['rep_id'];
		$max_size		= $array['max_size'];
		$extension		=$array['extension'];
		$debug	=$array['debug'];
		if($debug==1)$f->pre($HTTP_POST_FILES);

		$originalname	=$HTTP_POST_FILES[$input_name]['name'];
		$filename		=time()."_".$HTTP_POST_FILES[$input_name]['name'];
		$filesize		=$HTTP_POST_FILES[$input_name]['size'];
		$filename=preg_replace("/\'|\s|\"|\-/","_",strtolower($filename));

		
		$temp_dir=$dir;
		$path=$dir."/".$filename;
		if(empty($dir)){
			$dir ="$DOCUMENT_ROOT/i/upload";
		}else{
			$dir =$DOCUMENT_ROOT."$dir";
		}
		
		if($debug==1) $this->pre($HTTP_POST_FILES);
		if($debug==1) $this->pre($originalname);
		if($debug==1) $this->pre($filesize);
		if($debug==1) $this->pre($filename);
		if($debug==1) $this->pre($array);
		//$this->pre($HTTP_POST_FILES);
		//echo "<h1>filename: '".$path."' , $originalname , $extension</h1>";
		//echo "$path";
		
		//echo $input_name;

		if(eregi("\.php",$filename)){
			$this->logfile(array("filename"=>"ilegal_upload.log","message"=>"$REMOTE_ADDR,$login_nip,$REQUEST_URI $PHP_SELF"));
			die("Error: file extensi ini tidak diperbolehkan untuk diupload ke server!");
		}
		if(!empty($extension) && !empty($originalname)){
			if(!preg_match("/($extension)$/",$filename)) $error .="<li>file extensi ini tidak diperbolehkan untuk diupload ke server! pastikan hanya file dengan ekstensi ".preg_replace("#\|#",",",$extension);
		}
		if(!empty($max_size)){
			if($filesize > $max_size) $error .="<li>file ini memiliki ukuran ".round(($filesize/1000))." kb,lebih besar dari ukuran yang diperbolehkan  maks ".round(($max_size/1000))." kb";
							
		}
		if($error) {
			
			
			#$f->box("error","error:<ul>$error</ul>","","error","600");
			echo"<tr>
					<td valign=top align=center style=padding-top:20px; >
						<table style='width:300px;border:2px solid #C00000;' >
							<tr>
								<td valign=top ><img src=/i/stop.png></td>
								<td>
									ERROR:<ul>$error</ul><a href=javascript:void(0); onClick=window.history.go(-1);>&larr; Kembali</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>";
		}
		
		else{

		if(!empty($originalname)) {
			move_uploaded_file($HTTP_POST_FILES[$input_name]['tmp_name'],"$dir/$filename") or die("upload gagal. periksa permission direktori $dir/$filename");
			if($temp_dir	=="/i/recruitment"){
				#copy("$dir/$filename", "D:/www//i/recruitment/$filename");
			}
		}
		if($act=='do_update'){
			if($change_file=='3'){
				$sql="select $field as path_old from rec_pelamar where rep_id='$rep_id'";				
				$path_old=$db->getOne($sql);
				@unlink("$DOCUMENT_ROOT$path_old");
				#@unlink("D:/www/$path_old");
				

				$sql = "update rec_pelamar set $field='' where rep_id='$rep_id'";
				if($debug==1)echo"$sql<HR>";
				//echo"$sql<HR>";
				$result=$db->Execute("$sql");
				if (!$result){
					print $db->ErrorMsg();
				}
			}elseif($change_file=='2'){
				$sql="select $field as path_old from rec_pelamar where rep_id='$rep_id'";				
				$path_old=$db->getOne($sql);
				@unlink("$DOCUMENT_ROOT$path_old");
				#@unlink("D:/www/$path_old");
				
				#$exp1 = explode("/",$path);
				$filename = basename($path);
				//check apakah sudah ada tau belum?
#				$sql="select id from spg_sipeg_pictures where keyid='$id' and lower(tablename)='$table'";
#				if($f->check_exist_value($sql)==true){
					$sql= "UPDATE rec_pelamar set $field='$path' where rep_id='$rep_id'";	
					if($debug==1)echo"$sql<HR>";
#				}else{


#				}
				$result=$db->Execute("$sql");
				if (!$result){
					print $db->ErrorMsg();
				}
			}
		}else{
			$filename=basename($path);
			$sql= "UPDATE rec_pelamar set $field='$path' where rep_id='$rep_id'";
			if($debug==1)echo"$sql<HR>";				
			$result=$db->Execute($sql);
			if(!$result)print $db->ErrorMsg();

		}
		//return($sql);
	}
	}


	/*
	function upload_file_lk_db($array){
		global $db,$f;
		$act			= $array['act'];
		$table			= $array['table'];
		$path			= $array['path'];
		$change_file	= $array['change_file'];
		$id				= $array['id'];
		$pk				= $array['pk'];
		$nip			= $array['nip'];
		$filesize		= $array['filesize'];
		$originalname	= $array['originalname'];
		$debug			= $array['debug'];
		if($debug==1) $f->pre($array);
		if($act=='do_update'){
			if($change_file=='3'){
				$sql = "delete from spg_sipeg_pictures where keyid='$id' and lower(tablename)='$table'";
				#echo"$sql<HR>";
				$result=$db->Execute("$sql");
				if (!$result){
					print $db->ErrorMsg();
				}
			}elseif($change_file=='2'){
				#$exp1 = explode("/",$path);
				$filename = basename($path);
				//check apakah sudah ada tau belum?
				$sql="select id from spg_sipeg_pictures where keyid='$id' and lower(tablename)='$table'";
				if($f->check_exist_value($sql)==true){
					$sql= "UPDATE SPG_SIPEG_PICTURES set filename='$filename',originalname='$originalname',filesize='$filesize',
										lastupdate=sysdate,title='',
										description='',fieldname='',thumbname='',path='$path',nip='$nip' 
							   WHERE keyid='$id' and lower(tablename)='$table'";			
				}else{

					$max_id_upload=$this->max_id(array("table"=>"spg_sipeg_pictures"));
					$max_id_upload=$max_id_upload+1;
					$sql="INSERT INTO SPG_SIPEG_PICTURES (id,originalname,filename,filesize,tablename,path,keyid,nip)
						values ('$max_id_upload','$originalname','$filename','$filesize','$table','$path','$id','$nip')";

				}
				if($debug==1) $f->pre($sql);
				$result=$db->Execute("$sql");
				if (!$result){
					print $db->ErrorMsg();
				}
			}
		}else{
			$max_id_upload=$f->max_id(array("table"=>"spg_sipeg_pictures"));
			if(empty($max_id_upload)){
				$max_id_upload=1;
			}else{
				$max_id_upload=$max_id_upload+1;
			}
			$filename=basename($path);
			$sql="INSERT INTO SPG_SIPEG_PICTURES (id,originalname,filename,filesize,tablename,path,keyid,nip)
					values ('$max_id_upload','$originalname','$filename','$filesize','$table','$path','$id','$nip')";
			$result=$db->Execute($sql);
			if(!$result)print $db->ErrorMsg();
			if($debug==1) $f->pre($sql);

		}
	}
	*/
	function input_image($nama_input,$file_location,$keterangan="",$display_thumnail="",$thumbnail_size="",$field_name_change_file=""){
		global $db;
		global $act;

		$field_name_change_file=(empty($field_name_change_file))?'change_file':$field_name_change_file;
		

		if(empty($file_location)){
			$output .="<input type=file name='$nama_input' id='$nama_input' value=''>$keterangan<BR>
			<input type=hidden name='$field_name_change_file' id='$field_name_change_file'  value='2'>";
		}else{

			$output .="<input type=file name=$nama_input value='$path'> <BR>$keterangan<BR>";
			#if($act=='update'){
			$output .="<table style='vertical-align:left;text-align:left;color:#000;'><tr><td style='vertical-align:top;text-align:left;' width=10>";
			if($display_thumnail==1 && !empty($file_location) && preg_match("#(\.jpg|\.gif|\.jpeg|\.png)$#i",$file_location)){
				$output .="<img src=$file_location width=$thumbnail_size>";
			}else{
				$output .= (!empty($file_location)?$this->check_file_type($file_location,$file_location,"_blank"):"")."<BR>";

			}
			$output .="</td><td style='vertical-align:top;text-align:left;'>
				<input type=radio name='$field_name_change_file' value='1' checked >Keep File
				<input type=radio name='$field_name_change_file' value='2'>Change<BR>
				<input type=radio name='$field_name_change_file' value='3'>Delete</td></tr>
				</table>";
		}
		return  $output;

	}
	function check_file_type($filename,$href="",$target=""){
		global $download;
		
		$extension=substr($filename,-4);
		if(eregi("pdf",$extension)){
			$icon="<img src=/i/icon/pdf.gif border=0>";
		}elseif(eregi("jpg|jpeg",$extension)){
			$icon="<img src=/i/icon/jpg.gif border=0>";
		}elseif(eregi("png",$extension)){
			$icon="<img src=/i/icon/png.gif border=0>";
		}elseif(eregi("gif",$extension)){
			$icon="<img src=/i/icon/gif.gif border=0>";
		}elseif(eregi("doc",$extension)){
			$icon="<img src=/i/icon/doc.gif border=0>";
		}elseif(eregi("xls",$extension)){
			$icon="<img src=/i/icon/xls.gif border=0>";
		}elseif(eregi("ppt",$extension)){
			$icon="<img src=/i/icon/ppt.gif border=0>";
		}elseif(eregi("rar|zip",$extension)){
			$icon="<img src=/i/icon/zip.gif border=0>";
		}else{
			$icon=$extension;
		}
		if($download == '0'){
			return "$icon";
		}else{
			return "<a href=$href target=$target>$icon</a>";
		}
	}
	function date_diff($date_start,$date_end=""){
		if(empty($date_end)) $date_end=date("d/m/Y");

		$date_start_array=split("\/",$date_start);
		$d1=$date_start_array[0];
		$m1=$date_start_array[1];
		$y1=$date_start_array[2];

		$date_end_array=split("\/",$date_end);
		$d2=$date_end_array[0];
		$m2=$date_end_array[1];
		$y2=$date_end_array[2];

		$date_diff = mktime(1,0,0,$m2,$d2,$y2) - mktime(1,0,0,$m1,$d1,$y1);
		$day_diff = floor($date_diff/60/60/24);
		return $day_diff;
	}
	function nip_baru($nip){
		return $this->convert_value(array("table"=>"spg_data_current","cs"=>"nip_baru","cd"=>"nip","vd"=>$nip));
	}
	function nip9($nip_baru){
		return $this->convert_value(array("table"=>"spg_data_current","cs"=>"nip","cd"=>"nip_baru","vd"=>$nip_baru));
	}

	function usia_pegawai($nip){
		global $db;
		$sql="select
				 floor((sysdate-tgl_lahir_peg)/365) as umur
				 	 from spg_data_current where nip='$nip'";
		$result=$this->get_last_record($sql);
		$umur=$result['umur'];
		return $umur;
	}

	#YM: to handle session accross 69 and 70
	function SetNipSession($session_id, $nip){
		global $db;

		$sql = "delete from spg_select_nip where session_id = '".$session_id."' ";
		$result = $db->Execute($sql);
		if(!$result){
			print $db->ErrorMsg();
			die;
		}

		$sql = "insert into spg_select_nip (session_id, nip, tgl_buat) values ('".$session_id."' , '".$nip."' ,sysdate)";
		$result = $db->Execute($sql);

		if(!$result){
			print $db->ErrorMsg();
			die;
		}

	}


	function GetNipSession($login_session){
		global $db;
		global $login_nip;

		$sql = "select nip from spg_select_nip where session_id = '".$login_session."' ";
		$nip = $db->GetOne($sql);

		$nip = (empty($nip)) ? $login_nip : $nip;

		return $nip;

	}
	function getPrivileges(){
		global $db,$_SERVER;
		$fuaname=$this->get_function_access();
		$sqlNo="select men_nomorurut from wf_menu where lower(men_url) like '".$_SERVER['PHP_SELF']."%'";
		$r=$this->get_last_record($sqlNo);
		$sql="select * from wf_functionaccess  where fua_name='".$fuaname["fua_name"]."'  and men_nomorurut='".$r['men_nomorurut']."'";
		$data=$this->get_last_record($sql);
		return $data;
	}
	function foto_pegawai($nip){
		global $db,$f,$DOCUMENT_ROOT;
		if(file_exists("$DOCUMENT_ROOT/img/foto/".$nip.".jpg")){
			$path_foto="/img/foto/".$nip.".jpg";
		}else{
			#$nip_lama=$this->convert_value(array("table"=>"map_nip_peg","cs"=>"nip_lama","cd"=>"nip_baru","vd"=>$nip,"print_query"=>0));
			if(empty($nip_lama)){
				$nip_lama=$this->convert_value(array("table"=>"spg_data_current","cs"=>"nip","cd"=>"nip","vd"=>$nip,"print_query"=>0));
			}
			#echo"<h1>nip_lama: $nip_lama</h1>";
			if(file_exists("$DOCUMENT_ROOT/img/foto/".$nip_lama.".jpg")){
				$path_foto="/img/foto/".$nip_lama.".jpg";
			}else{
				$path_foto="/images/no_photo.jpg";
			}
		}
		return $path_foto;

	}

	function autocomplete($file,$asal,$cond="'",$cond2=""){
		$gabung="'".$file.$cond;
		$output .="
		<script type=\"text/javascript\">
						$().keyup(function(){
							$cond2
							randval=Math.random();
							$('#$asal').autocomplete($gabung, {
								width: 350,
								matchContains: false,
								//mustMatch: true,
								//minChars: 0,
								//multiple: true,
								//highlight: false,
								//multipleSeparator: ',',
								selectFirst: false
							});
						});
		</script>";

		return $output;
	}

	function popup_js($url="",$name="",$width="",$urutan=""){
		global $PHP_SELF;
		global $f;
		global $DOCUMENT_ROOT;

		if($width==null)$width="400";

		if($urutan==1 or $urutan==""){
		?>
			<script language="javascript" src="/js/modal/jquery.js"></script>
			<script language="javascript" src="/js/modal/modal.popup.js"></script>
		<?
		}
		?>
			<script language="javascript">

			$(document).ready(function() {

				//Change these values to style your modal popup
				var align = 'center';									//Valid values; left, right, center
				var top = 100; 											//Use an integer (in pixels)
				var width = <?=$width?>; 										//Use an integer (in pixels)
				var padding = 10;										//Use an integer (in pixels)
				var backgroundColor = '#FFFFFF'; 						//Use any hex code
				var source = "<?=$url?>"; 					//Refer to any page on your server, external pages are not valid e.g. http://www.google.co.uk
				var borderColor = '#333333'; 							//Use any hex code
				var borderWeight = 2; 									//Use an integer (in pixels)
				var borderRadius = 5; 									//Use an integer (in pixels)
				var fadeOutTime = 300; 									//Use any integer, 0 = no fade
				var disableColor = '#666666'; 							//Use any hex code
				var disableOpacity = 40; 								//Valid range 0-100
				var loadingImage = '/js/modal/loading.gif';		//Use relative path from this page

				//This method initialises the modal popup
				$(".modal<?=$urutan?>").click(function() {
					modalPopup(align, top, width, padding, disableColor, disableOpacity, backgroundColor, borderColor, borderWeight, borderRadius, fadeOutTime, source, loadingImage);
				});

				//This method hides the popup when the escape key is pressed
				$(document).keyup(function(e) {
					if (e.keyCode == 27) {
						closePopup(fadeOutTime);
					}
				});
			});

			</script>
		<?
		$coba="<a href='javascript:void(0)' class='modal$urutan'>$name</a>";
		return $coba;
	}
	function is_valid_date($date){
		if (!isset($date) || $date=="")
		{
			return false;
		}

		list($dd,$mm,$yy)=explode("/",$date);
		if ($dd!="" && $mm!="" && $yy!="")
		{
			return checkdate($mm,$dd,$yy);
		}

		return false;
	}
	function tab(){
		
	}
	function unit_org($kd_unit_org){
		return $this->convert_value(array("table"=>"spg_08_unit_organisasi","cs"=>"nm_unit_org","cd"=>"kd_unit_org","vd"=>$kd_unit_org));		
	}
	function get_image_lk($keyid,$tablename){
		global $db,$debug;
#		$debug=1;
		$tablename=strtolower($tablename);
		$sql="SELECT path FROM SPG_SIPEG_PICTURES WHERE keyid='$keyid' and lower(tablename)='$tablename'";
		if($debug==1) $this->pre($sql);
		$path=$db->getOne($sql);
		return $path;
	}
	function get_file_rec($keyid,$field){
		global $db,$debug;
		#$debug=0;
		$sql="SELECT $field FROM rec_pelamar WHERE rep_id='$keyid'";
		if($debug==1) $this->pre($sql);
		$path=$db->getOne($sql);
		return $path;
	}
	function upload_file($array){

		/*
		# cara pemakaian
		$array=array(
			"dir"=>"/i/diklat",
			"act"=>"$act",
			"input_name"=>"file",
			"path_old"=>"$file_loc",
			"change_file"=>"$change_file",
			"extension"=>"doc|docx|jpg|jpeg|pdf",
			"keyid"=>"$id",
			"table"=>"spg_pegawai",
			"lk"=>"1",
			"nip"=>"$nip",
			"pk"=>"id" // optional
			);
		if($act=='add'){
			if(!empty($HTTP_POST_FILES['file']['name'])) $filename=$f->upload_file($array);
		}else{
			$filename=$f->upload_file($array);
		}


		*/
		#$this->pre($array);
		$dir		=$array['dir'];
		$change_file=$array['change_file'];
		$path_old	=$array['path_old'];
		$act		=$array['act'];
		$input_name	=$array['input_name'];
		$extension	=$array['extension'];
		$keyid		=$array['keyid'];# id table
		$table		=$array['table'];# table reference for spg_sipeg_pictures
		$lk			=$array['lk'];#1 untuk proses diproses di lk.
		$nip		=$array['nip'];
		$table		=strtolower($table);
		$pk			=$array['pk']; #primary key
		$max_size	=$array['max_size'];
		$debug	=$array['debug'];
		if(empty($change_file)) $change_file=2; #default replace
		global $db,$f;
		global $HTTP_POST_FILES;

		global $DOCUMENT_ROOT,$REMOTE_ADDR,$login_nip,$REQUEST_URI,$PHP_SELF;
		$temp_dir=$dir;
		if(($act=='do_update' && $change_file=='2') || $act=='do_add' || $act=='do_register'){ //change file
			$originalname	=$HTTP_POST_FILES[$input_name]['name'];
			$filename		=time()."_".$HTTP_POST_FILES[$input_name]['name'];
			$filesize		=$HTTP_POST_FILES[$input_name]['size'];
			$filename=preg_replace("/\'|\s|\"|\-/","_",strtolower($filename));
			if(empty($dir)){
				$dir ="$DOCUMENT_ROOT/i/upload";
			}else{
				$dir =$DOCUMENT_ROOT."$dir";
			}
			if($debug==1) $this->pre($HTTP_POST_FILES);
			if($debug==1) $this->pre($array);
			#if(empty($originalname)){
			#	$error .="<li>Upload file yang anda inginkan";
			#}
			if(eregi("\.php",$filename)){
				$this->logfile(array("filename"=>"ilegal_upload.log","message"=>"$REMOTE_ADDR,$login_nip,$REQUEST_URI $PHP_SELF"));
				die("Error: file extensi ini tidak diperbolehkan untuk diupload ke server!");
			}
			if(!empty($extension) && !empty($originalname)){
				if(!preg_match("/($extension)$/",$filename)) $error .="<li>file extensi ini tidak diperbolehkan untuk diupload ke server! pastikan hanya file dengan ekstensi ".preg_replace("#\|#",",",$extension);
			}
			if(!empty($max_size)){
				if($filesize > $max_size) $error .="<li>file ini memiliki ukuran ".round(($filesize/1000))." kb,lebih besar dari ukuran yang diperbolehkan  maks ".round(($max_size/1000))." kb";
								
			}
			if($error) {
				#$f->box("error","error:<ul>$error</ul>","","error","600");
				echo "
					<tr>
						<td valign=top align=center style=padding-top:20px; >
							<table style='width:300px;border:2px solid #C00000;' >
								<tr>
									<td valign=top ><img src=/i/stop.png></td>
									<td>
										ERROR:<ul>$error</ul><a href=$PHP_SELF>&larr; Kembali</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				";
			}
			if(!empty($originalname)) {

				//add by yussa 17/07/2013
				/*=============================================================================================================================================*/
				$sql="select path as path_old from spg_sipeg_pictures where ".(!empty($pk)?"$pk='$keyid'":"keyid='$keyid'")." and lower(tablename)='$table'";				
				$path_old=$db->getOne($sql);
				@unlink("$DOCUMENT_ROOT$path_old");
				/*=============================================================================================================================================*/
				#echo $HTTP_POST_FILES[$input_name]['tmp_name']." || $dir/$filename";
				move_uploaded_file($HTTP_POST_FILES[$input_name]['tmp_name'],"$dir/$filename") or die("upload gagal. periksa permission direktori $dir/$filename");
				#$this->pre($temp_dir);
				if($temp_dir=="/i/recruitment"){
					#copy("$dir/$filename", "D:/www//i/recruitment/$filename");
					/*if(copy("$dir/$filename", "D:/www//i/recruitment/$filename")) {
				      $this->pre("File copy was success!");
				    }else {
				      $this->pre("File copy failed");
				    }*/	
				}
				
				$path=str_replace($DOCUMENT_ROOT,"","$dir/$filename");
				#$path=eregi_replace("$DOCUMENT_ROOT","","$dir/$filename");
			}

		}elseif($act=='do_update' && $change_file=='3'){ //delete
			if(empty($path_old)&& $lk==1){
				$sql="select path as path_old from spg_sipeg_pictures where ".(!empty($pk)?"$pk='$keyid'":"keyid='$keyid'")." and lower(tablename)='$table'";				
				$path_old=$db->getOne($sql);
			}
			//$path_old=$f->convert_value(array("table"=>"","cs"=>"path","cd"=>"dik_id","vd"=>"$dik_id"));
			@unlink("$DOCUMENT_ROOT$path_old");
			$path="";
		}
		if($lk==1){
			$this->upload_file_lk_db(array(
				"act"=>"$act",
				"table"=>"$table",
				"path"=>"$path",
				"change_file"=>$change_file,
				"id"=>$keyid,
				"nip"=>"$nip",
				"pk"=>$pk,
				"originalname"=>"$originalname",
				"filesize"=>"$filesize",
				"debug"=>$debug
			));			
		}
		return $path;
	}
	function upload_file_lk_db($array){
		global $db,$f;
		$act			= $array['act'];
		$table			= $array['table'];
		$path			= $array['path'];
		$change_file	= $array['change_file'];
		$id				= $array['id'];
		$pk				= $array['pk'];
		$nip			= $array['nip'];
		$filesize		= $array['filesize'];
		$originalname	= $array['originalname'];
		$debug			= $array['debug'];
		if($debug==1) $f->pre($array);
		if($act=='do_update'){
			if($change_file=='3'){
				$sql = "delete from spg_sipeg_pictures where keyid='$id' and lower(tablename)='$table'";
				#echo"$sql<HR>";
				$result=$db->Execute("$sql");
				if (!$result){
					print $db->ErrorMsg();
				}
			}elseif($change_file=='2'){
				#$exp1 = explode("/",$path);
				$filename = basename($path);
				//check apakah sudah ada tau belum?
				$sql="select id from spg_sipeg_pictures where keyid='$id' and lower(tablename)='$table'";
				if($f->check_exist_value($sql)==true){
					$sql= "UPDATE SPG_SIPEG_PICTURES set filename='$filename',originalname='$originalname',filesize='$filesize',
										lastupdate=sysdate,title='',
										description='',fieldname='',thumbname='',path='$path',nip='$nip' 
							   WHERE keyid='$id' and lower(tablename)='$table'";			
				}else{
						
						$max_id_upload=$this->max_id(array("table"=>"spg_sipeg_pictures"));
						$max_id_upload=$max_id_upload+1;
			            $sql="INSERT INTO SPG_SIPEG_PICTURES (id,originalname,filename,filesize,tablename,path,keyid,nip) 
						values ('$max_id_upload','$originalname','$filename','$filesize','$table','$path','$id','$nip')";
					
				}
				if($debug==1) $f->pre($sql);
				$result=$db->Execute("$sql");
				if (!$result){
					print $db->ErrorMsg();
				}
			}		
		}else{
            $max_id_upload=$f->max_id(array("table"=>"spg_sipeg_pictures"));
			if(empty($max_id_upload)){
				$max_id_upload=1;
			}else{
				$max_id_upload=$max_id_upload+1;
			}
			$filename=basename($path);				
            $sql="INSERT INTO SPG_SIPEG_PICTURES (id,originalname,filename,filesize,tablename,path,keyid,nip) 
					values ('$max_id_upload','$originalname','$filename','$filesize','$table','$path','$id','$nip')";
            $result=$db->Execute($sql);
			if(!$result)print $db->ErrorMsg();
			if($debug==1) $f->pre($sql);
			
		}
	}
	function check_pemberi_wewenang_cuti($nip,&$yth){
		global $db;
		$result=$this->detailPegawai($nip);
		$kd_pangkat=$result['kd_pangkat'];
		$kd_kantor=$result['kd_kantor'];
		$kd_jabatan_str=$result['kd_jabatan_str'];
		$nm_kantor=$result['nm_kantor'];
		$jns_jabatan_cur=$result['jns_jabatan_cur'];
#		$this->pre($result);
		$jns_kantor=$this->convert_value(array("table"=>"spg_08_kantor","cs"=>"jns_kantor","cd"=>"kd_kantor","vd"=>"$kd_kantor"));
		//untuk cari unit kerja
		$nip_atasan=$this->cari_atasan($nip,2);
		$sql="select nm_unit_org from spg_data_current a left join spg_08_unit_organisasi b on a.kd_unit_org=b.kd_unit_org where nip='$nip'";
		$nm_unit_org_atasan=$db->getOne($sql);
	#	echo"<h1>kd_jabatan_str:$kd_jabatan_str , kd_pangkat:$kd_pangkat, jns_kantor: $jns_kantor</h1>";
		if(substr($kd_jabatan_str,0,1)<='2' && $jns_jabatan_cur=='1'){ //eselon 2 keatas
				$sql="select nip from spg_data_current where sts_pensiun='0' and kd_jabatan_str='101100200000000'";//sekjen
				$nip_pejabat=$db->getOne($sql);	
				$yth="Yth. Bapak/Ibu Director \rMelalui $nm_unit_org_atasan";		
		}elseif($kd_pangkat >40){
				$sql="select nip from spg_data_current where sts_pensiun='0' and kd_jabatan_str='202100203000000'";//kabiro SDM
				$nip_pejabat=$db->getOne($sql);			
				$yth="Yth. Bapak/Ibu Kepala Divisi Tiki JNE \rMelalui $nm_unit_org_atasan";		
		}else{
			if($jns_kantor >=12 && $jns_kantor <=43){//perwakilan
				$sql="select nip from spg_data_current where sts_pensiun='0' and substr(kd_jabatan_str,1,3)='204' and kd_kantor='$kd_kantor'";//kepala perwakilan
				$nip_pejabat=$db->getOne($sql);							
				$yth="Yth. Bapak/Ibu Kepala Kantor Perwakilan $nm_kantor ";		
			}else{				
				if($kd_pangkat >32){
					$sql="select nip from spg_data_current where sts_pensiun='0' and kd_jabatan_str='302100203020000'";//kabag PKPK
					$nip_pejabat=$db->getOne($sql);			
					$yth="Yth. Bapak/Ibu Kepala Bagian Pengembangan Kompetensi dan Penilaian Kinerja \rMelalui $nm_unit_org_atasan";							
				}else{
					$sql="select nip from spg_data_current where sts_pensiun='0' and kd_jabatan_str='402100203020200'";//kabag evkin
					$nip_pejabat=$db->getOne($sql);			
					$yth="Yth. Bapak/Ibu Kepala Bagian Pengembangan Kompetensi dan Penilaian Kinerja \rMelalui $nm_unit_org_atasan";							
				}
			}			
		}
		return $nip_pejabat;
	}

	//add by yussa 17/07/2013
	/*=============================================================================================================================================*/
	function delete_upload_file($array)
	{
		global $db;
		global $DOCUMENT_ROOT;

		$keyid=$array['keyid'];
		$table=$array['table'];
		$debug=$array['debug'];
		
		if(empty($debug)){$debug=0;}

		if($debug=='1') {$this->pre($array);}

		$sql="select path as path_old from spg_sipeg_pictures where keyid='$keyid' and lower(tablename)='$table'";	
		if($debug=='1') {$this->pre($sql);}
		$path_old=$db->getOne($sql);
		if(file_exists("$DOCUMENT_ROOT$path_old"))
		@unlink("$DOCUMENT_ROOT$path_old");

		$sql2 = "delete from spg_sipeg_pictures where keyid='$keyid' and lower(tablename)='$table'";
		$result=$db->Execute($sql2);
		if($debug=='1') {$this->pre($sql2);}
		if (!$result){
			print $db->ErrorMsg();
		}

		if($result && $path_old)
			return true;
		else
			return false;
	}
	/*=============================================================================================================================================*/

	function insert_sk($no_sk,$tgl_sk,$jns_sk)
	{
		#insert sk
		global $db;
		if(!empty($no_sk)){
			$sql="select no_sk from spg_sk where no_sk='$no_sk'";
			if($this->check_exist_value($sql)){
				$sql="update spg_sk set tgl_sk=to_date('$tgl_sk','dd/mm/yyyy'),jns_sk='$jns_sk' where no_sk='$no_sk'";
			}else{
				$sql="insert into spg_sk (no_sk,tgl_sk,jns_sk) values ('$no_sk',to_date('$tgl_sk','dd/mm/yyyy'),'$jns_sk')";
			}
			$result=$db->Execute($sql);
			if(!$result)print $db->ErrorMsg();
		}
	}
	function indonesian_date ($timestamp = '', $date_format = 'j F Y ') {
			global $db;
			global $f;
		if (trim ($timestamp) == '')
		{
				$timestamp = time ();
		}
		elseif (!ctype_digit ($timestamp))
		{
			$timestamp = strtotime ($timestamp);
		}
		# remove S (st,nd,rd,th) there are no such things in indonesia :p
		$date_format = preg_replace ("/S/", "", $date_format);
		$pattern = array (
			'/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
			'/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
			'/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
			'/April/','/June/','/July/','/August/','/September/','/October/',
			'/November/','/December/',
		);
		$replace = array ('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
			'Januari','Februari','Maret','April','Juni','Juli','Agustus','September',
			'Oktober','November','Desember',
		);
		$date = date ($date_format, $timestamp);
		$date = preg_replace ($pattern, $replace, $date);
		$date = "{$date}";
		return $date;
	} 


	function selectListPangkat($name,$curr_id,$script="") {
		global $db;

		$output		 = "<SELECT NAME=$name $script id=$name class=>\n";
		$output		.= "<option></option>\n";
		/*if($sql==""){
			$sql="select * from $table $cond ";
			//added by Heri Noviandi 211008
			if (!preg_match("/order/i", $sql)) $sql = $sql ." order by $value_name ";
		}*/
		$sql="select kd_pangkat, a.nm_pangkat, b.nm_golongan from spg_pangkat a, spg_golongan b where a.kd_gol=b.kd_gol  order by b.kd_gol desc, a.nm_pangkat asc";
		$result = $db->Execute("$sql");
		if (!$result){
			print $db->ErrorMsg();
		}
		while ( $row = $result->FetchRow() ) {
			
			foreach($row as $key=>$val){
				$key=strtolower($key);
				$$key=trim($val);
				#echo $key." | ".$val."<br>";
				//echo $val."<br>";
			}
			//$this->pre($row);

			/*echo $kd_pangkat."<br>";
			echo $nm_pangkat."<br>";
			echo $nm_golongan."<br>";
*/
			if(preg_match("#\smultiple\s#i",$script)){
					$selected=preg_match("#$curr_id#i",$kd_pangkat)?"selected":"";
			}else{
					$selected=(trim($curr_id)==trim($kd_pangkat))?"selected":"";	
			}
			
			$output .= "<option value=\"".$kd_pangkat."\" $selected>";

			if(!empty($nm_pangkat))
				$output.= $nm_golongan." (".$nm_pangkat.")";
			else
				$output.= $nm_golongan;
			
			$output .="</option>\n";
		}
		$result->Close();

		$output .= "</SELECT>\n";
		return $output;
	}
	
		function message_style($message,$type,$link){
		//$message = "pesan yang ditampilkan"
		//$type    = error atau success
		//$link	   = link URL
		$link =($link=="")?"<a href=# onClick=history.back(-1);>Kembali</a>":$link;
		if($type=="success"){
			echo"
			<center>
				<table align='center' style='width:300px;border:2px solid #52db05;' >
					<tr>
						<td valign=top ><img src=/i/button_correct.png></td>
						<td>
							SUKSES:<ul><li>$message !</li></ul>
							<br/> $link
						</td>
					</tr>
				</table>
			</center>";
		}elseif($type=="error"){
			echo"
			<center>
				<table align='center' style='width:300px;border:2px solid #C00000;' >
					<tr>
						<td valign=top ><img src=/i/stop.png></td>
						<td>
							ERROR:<ul><li>$message !</li></ul>
							<br/> $link
						</td>
					</tr>
				</table>
			</center>";
		}
	}
	
	//tombol style By Aep
	function btn_link_green($url,$dispaly_link){
		echo"<table class=default><tr><td align=right>";
		$css="style='float:right;
					 background:#5CCD00;
					 color:#ffffff;
					 font-size:14px;
					 border-radius:5px;
					 -moz-border-radius:5px;
					 -webkit-border-radius:5px;
					 border:1px solid #459A00;' 
					 ";
			echo"<input type=button onClick=location.href='$url' value='$dispaly_link' ".$css.">";
		echo"</td></tr></table>";
	}
	
	function cur_page_url(){
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"];
		 }
		 return $pageURL;
	}

	function cur_url(){
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"];
		 }
		 return $pageURL;
	}
	
	function cur_page() {
		 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	function get_uri() {
		 return substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["REQUEST_URI"],"/")+1);
	}

	function convert_date_sql($tanggal,$format="0"){
		$tanggal_array=split("\/",$tanggal); //format 31/01/2013
		$_tanggal_array=count($tanggal_array);
		if($format=='0'){ //format 01/31/2013
			if( $_tanggal_array > 1){
				$output="'".$tanggal_array[1]."/".$tanggal_array[0]."/".$tanggal_array[2]."'";				
			}else{
				$output="''";
			}
		}elseif($format=='1'){ //format 2013-01-31
			if( $_tanggal_array > 1){
				$output=$tanggal_array[2]."-".$tanggal_array[1]."-".$tanggal_array[0];
			}else{
				$output="";
			}
		}
		return $output;	
	}
	
	function get_file_data_pelamar($keyid,$id,$field, $column, $table){
			global $db,$debug;
			#$debug=0;
			#$tablename=strtolower($tablename);
			$sql="SELECT $field FROM $table WHERE rep_id='$keyid' and $column='$id'";
			if($debug==1) $this->pre($sql);
			// $this->pre($sql);
			$path=$db->getOne($sql);
			return $path;
		}
}



?>
