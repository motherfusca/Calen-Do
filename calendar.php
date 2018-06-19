<?php
require_once 'database.php';
require 'input.php';
require 'setup.php';

$db1 = new Database; 
$action = (isset($_REQUEST['action']) && !empty($_REQUEST['action']))?$_REQUEST['action']: NULL;
if(empty($action)) {
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Calen-do</title>
<link rel="stylesheet" href="assets/css/styles.css" />
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700" />
<script type="text/javascript" src="jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="script.js"></script>
</head>

<body onload="reload(<?= $db1->todays_page() ?>)">
 
<div id="outer_container">
<div id="loader" ><img src="loader.gif"></div>
<div id="data" style="position:relative;">
<?php } ?>

<?php if(empty($action)) {?>
</div>
</div>
</body>

</html>
<?php }


if($action == 'ajax' || $action == 'update' || $action == 'delete' || $action == 'setDone' || $action == 'setUndone') {
    require_once 'database.php';
	
	function getTable() {
		GLOBAL $db;
		$db = new Database;

        $person = unserialize(urldecode($_SESSION['person']));

		$data ='
			<div id="paginator">'.$db->paginate().'</div>';
			if($person)
			{
			$data.='
			<header>

    <h2><a href="login.php" style="color:greenyellow;" class="logo">Calen-do</a></h2><p>Beta</p>
    <p class="greeting" align="right">Welcome, <b>'.htmlspecialchars($person->name).'</b></p>
            	<p class="register_info" align="right">You registered <b>'.new RelativeTime($person->registered).'</b></p>
    </header>';
            	}
            	else
            	{
            	echo 'nao achou';
            	}
		$data.=	'<form action="input.php" method="post" style="margin-top:-3px">
            <div class="form-group2">
            <input autofocus class="enterdata" name="input" placeholder="  Enter your  : TO-DO TEXT :  or : DD/MM/YY, TO-DO TEXT : or : DD/MM/YY, HH:MM, EVENT TEXT : then hit Go (or ENTER)! Please remember, only european format dates for now!" type="text" "/>
            <button type="submit" class="goButton">Go!</button>
            </div>
            </form>
		    <form><table width="90%" cellspacing="0" cellpadding="2" align="center" border="0" id="data_tbl">
			<thead>
			  <tr>';
			  $db->update_todos();
			  $today = new DateTime();
              $ftoday = $today->format('Y-m-d');
			  foreach ($db->get_calendar() as $x)
			  {
			    $time = strtotime($x['dt']);
			    $newformat = date('d  M',$time);
                if($x['dt'] == $ftoday)
			    {
			        
			        $data .='<td width="10%" align="center" style="color:white;font-weight:bold;background-color:greenyellow;font-size:20px;">'.$newformat.' >>> '.$x['dayName'].'</td>';
			    }
			    else
			    {
			  $data .='<td width="10%" align="center" style="color:white;font-weight:bold;font-size:20px;">'.$newformat.' >>> '.$x['dayName'].'</td>';
			  }
              }
              
        $data .='</tr>
			 </thead>
			 </table>
	
			 <table width="90%" cellspacing="0" cellpadding="2" align="center" border="0" id="data_tbl">
			 <tbody>'; 

		foreach ($db->get_task() as $value)
	     {
			$data .='<tr id="cells">';

		    foreach ($db->get_calendar() as $b)
		    {
		        if ($value['type'] == 'TASK' && ($value['rg'] == $person->email))
		        {
        		        if ($b['dt'] == $value['date'] && ($value['status'] == 'UNDONE' && ($value['deleted'] == 'NO')))
        		        {
            			    $data .='<td width="10%" height="20" align="left"><img style="padding-top:2px; padding:3px; margin-right:-5px" align="right"  src="edit.png" class="updrow"
            			    title="Update"/>&nbsp;<!--<img align="right" src="delete.png" class="delrow" title="Delete"/>--><input type="hidden" value="'.$value['id'].'" name="id" />
            			   <span class="task" style=" padding:10px;color:black;font-weight:bold; float:right; text-decoration:none;font-size:21px;">'.$value['task'].'</span></td>';
            				        
        				}
        				else if ($b['dt'] == $value['date'] && ($value['status'] == 'DONE') && ($value['deleted'] == 'NO'))
        		        {
            			    $data .='<td width="10%" height="20" align="left"><img style="padding-top:2px; padding:3px; margin-right:-5px" align="right"  src="edit.png" class="updrow"
            			    title="Update"/>&nbsp;<!--<img align="right" src="delete.png" class="delrow" title="Delete"/>--><input type="hidden" value="'.$value['id'].'" name="id" />
            			   <span class="task" style="padding:10px;color:gray;font-weight:bold;float:right; text-decoration:line-through;font-size:21px;">'.$value['task'].'</span></td>';
            				        
        				}
        				else 
        				{
        				    $data .='<td width="10% height="10"></td>';
        				    
        				}
				}

			}
	         $data .='</tr>';   
			
		} 
$data .='<script type="text/javascript">
        emptyRows();
    </script>';		
		$data .='</tbody>
			</table>
            </table>
			 <table width="90%" cellspacing="0" cellpadding="2" align="center" border="0" id="data_tbl">
			 <thead>
			 <tr>
			 <td width="90%" align="center" style="color:white;font-weight:bold;font-size:20px;">Scheduled Tasks</td>
			 </tr>
			 </thead>
			 </table>
			 <table width="90%" cellspacing="0" cellpadding="2" align="center" border="0" id="data_tbl">
			 <tbody>';
			 
			 $i = 1;
		$cls = true;
		foreach ($db->get_task() as $value)
	     {

			$data .='<tr id="cells2">';
		    foreach ($db->get_calendar() as $b)
		    {

		        if ($value['type'] == 'CAL' && ($value['rg'] == $person->email))
		        {
        		        if ($value['date'] == $b['dt'] && ($value['deleted'] == 'NO'))
        		        {
            			    $data .='<td width="10%" height="20" align="left"><img style="padding:3px; margin-right:-5px" align="right"  src="edit.png" class="updrow"
            			    title="Update"/>&nbsp;<!--<img align="right" src="delete.png" class="delrow" title="Delete"/>--><input type="hidden" value="'.$value['id'].'" name="id" />
            			   <span class="time" style="font-size:18px;font-weight:bold;float:left;">'.$value['time'].'</span>
            			   <span class="task" style="font-size:18px;font-weight:bold;float:right;padding-right:5px;">'.$value['task'].'</span></td>';
        				}
        				else
        				{
        				    $data .='<td width="10%"></td>';
        				}
				}
			}
			  $data .='</tr>';
			$i++;
		} 
    $data .='
    </tbody></table>
    <table width="90%" cellspacing="0" cellpadding="2" align="center" border="0" id="data_tbl">
			 <thead>
			 <tr>
			 <td width="90%" align="center" style="color:grey">.</td>
			 </tr>
			 </thead>
			 </table><div><a href="logout.php" class="logoutButton">Logout</a></div>
    <footer>
    </footer>';
	return $data;
	}
    $db = new Database;
	if($action == 'ajax') {
		echo getTable();
	} else if($action == 'delete')
     {
			$db->delete($_REQUEST['id']);
			echo getTable();
			echo '<script type="text/javascript">reload('.$db->todays_page().');</script>';
	} else if($action == 'update')
	 {
			unset($_REQUEST['action']);
			$db->update($_REQUEST);
	}
	else if($action == 'setDone')
	{
            $db->set_done($_REQUEST['id']);
            echo getTable();
            	echo '<script type="text/javascript">reload('.$db->todays_page().');</script>';
	    
	}
	else if($action == 'setUndone')
	{
            $db->set_undone($_REQUEST['id']);
            echo getTable();
            	echo '<script type="text/javascript">reload('.$db->todays_page().');</script>';
	    
	}
}
?>
