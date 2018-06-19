<?php
/**
 * Database Class
 * @author Manish Jangir
 */

require 'setup.php';

session_start();

 // Create a new Google API client
$client = new apiClient();
//$client->setApplicationName("Tutorialzine");

// Configure it
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setDeveloperKey($api_key);
$client->setRedirectUri($redirect_url);
$client->setApprovalPrompt(false);
$oauth2 = new apiOauth2Service($client);
$calendar = new apiCalendarService($client);
$tasksService = new apiTasksService($client);
$tasks = $tasksService->tasks;

if(isset($_SESSION['access_token']))
{
$client->setAccessToken($_SESSION['access_token']);
}


$person = unserialize(urldecode($_SESSION['person']));
  
class Database {
	const DB_HOST = 'localhost';
	const DB_USER = 'root';
	const DB_PASSWORD = '23581321';
	const DB_NAME = 'calen-do';
	private $_dbconnect = NULL;
	private $_table1 = 'users';
	private $_table2 = 'calendar_table';
	private	$_table3 = 'tasks';
	private $_adj = 4;
	private $_tpages = 0;
	private $_limit = 7;
	private $_offset= 0;
	private $_page = 1;
	private $_prev_lbl = '<';//'&lsaquo; Prev Week';
	private $_next_lbl = '>>';//'Next Week &rsaquo;';

    
	
	public function __construct() {
		$this->_dbconnect = mysql_connect(self::DB_HOST,self::DB_USER,self::DB_PASSWORD);
		if ($this->_dbconnect) {
			$db =  mysql_select_db(self::DB_NAME,$this->_dbconnect);
		} else {
			die(mysql_error());
		}
		$this->_page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$this->_offset = ($this->_page - 1) * $this->_limit;
	}

	public function todays_page()
	{


     $date = new DateTime();
     $fdate = $date->format('Y-m-d');
     $query = mysql_query("SELECT * FROM $this->_table2 WHERE dt = '$fdate'");
     $row = mysql_fetch_array($query);
     return $row['weekNumber'];
     
	}
	
	private function total() {
		$result = mysql_query("select count(dt) AS total FROM $this->_table2");
		$row = mysql_fetch_array($result);
		return $row['total'];
	}
	
	public function get_calendar() {
		$query = mysql_query("SELECT * FROM $this->_table2 ORDER BY dt ASC LIMIT $this->_offset,$this->_limit");
		$result = array();
		$i = 1;
		while($res = mysql_fetch_assoc($query)){
			$result[$i] = $res;
			$i++;
		}
		return $result;
	}

	public function set_done($id)
	{
        $ids = is_array($id) ? implode(',', $id) : $id;
        $query = mysql_query("UPDATE $this->_table3 SET status='DONE' WHERE id IN ($ids)");
        return $this->result($query);
	
	}

	public function set_undone($id)
	{
        $ids = is_array($id) ? implode(',', $id) : $id;
        $query = mysql_query("UPDATE $this->_table3 SET status='UNDONE' WHERE id IN ($ids)");
        return $this->result($query);
	
	}

    public function get_task() {
		$query = mysql_query("SELECT * FROM $this->_table3 ORDER BY date ASC, time ASC"); // ASC LIMIT $this->_offset,$this->_limit");
		$result = array();
		$i = 1;
		while($res = mysql_fetch_assoc($query)){
			$result[$i] = $res;
			$i++;
		}
		return $result;
	}
	
	public function delete($id){
	    echo $id;
		$ids = is_array($id) ? implode(',', $id) : $id;
		$query = mysql_query("DELETE FROM $this->_table3 WHERE id IN ($ids)");
		return $this->result($query);
	}

	
	public function insert($data) {
		$keys = implode(',', array_keys($data));
		$values = "'" . implode("','", array_values($data)) . "'";
		$query = mysql_query("INSERT INTO $this->_table ($keys) VALUES ($values)");
		return $this->result($query);
	}


    public function update_todos()
    {
        $query = mysql_query("SELECT * FROM $this->_table3");
        	$result = array();
        	$i = 1;
        	while($res = mysql_fetch_assoc($query)){
			$result[$i] = $res;
			$i++;
		}
		$today = new DateTime();
		$todayf = $today->format('Y-m-d');
		$today = strtotime($todayf);
        foreach ($result as $x)
        {
            $todo_date = strtotime($x['date']);
            
            if ($x['status'] == 'UNDONE' && ($today - $todo_date > 0) && ($x['type'] == 'TASK'))
            {
            $id = $x['id'];
            $query = mysql_query("UPDATE $this->_table3 SET date = '$todayf' WHERE id=$id");
            }
        
        }
        return $this->result($query);
    }
	
	public function update($data) {
		$id = $data['id'];
		unset($data['id']);
		if ($data['task'] == "")
		{
		$query = mysql_query("UPDATE $this->_table3 SET deleted='YES' WHERE id IN ($id)");
		
		}
		else
		{
		$query = "UPDATE $this->_table3 SET ";
		foreach ($data as $key => $value) {
			$params[] = $key." = '".$value."'";
		}
		$query .= implode(',', $params)." WHERE id = $id";
		}
		
		return $this->result(mysql_query($query));
		}
	
	private function result($q) {
		return $q ? true : false;
	}
	
	public function paginate() {
		$this->_tpages = ceil($this->total()/$this->_limit);
		$out = '<div class="pagin green">';
		if($this->_page == 1) {
			$out .= "<span>$this->_prev_lbl</span>";
		} else {
			$out .= "<span style='color:greenyellow;position:absolute; left:40px; top:115px; font-size:200%; shadow: 10px 10px 5px #888888'><a href='javascript:void(0);' id='".($this->_page-1)."'><$this->_prev_lbl</span></a></span>";
		}
#		$out.= ($this->_page>($this->_adj+1)) ? "<a href='javascript:void(0);' id='1'>Today</a>" : '';
#		$out.= ($this->_page>($this->_adj+2)) ? $out.= "...\n" : '';
#		$pmin = ($this->_page>$this->_adj) ? ($this->_page-$this->_adj) : 1;
#		$pmax = ($this->_page<($this->_tpages-$this->_adj)) ? ($this->_page+$this->_adj) : $this->_tpages;
#		for($i=$pmin; $i<=$pmax; $i++) {
#			if($i==$this->_page) {
#				$out.= "<span class='current'>$i</span>";
#			}else {
#				$out.= "<a href='javascript:void(0);' id='$i'>$i</a>";
#			}
#		}
#		$out. ($this->_page<($this->_tpages-$this->_adj-1)) ? $out.= "...\n" : '';
#		$out.= ($this->_page<($this->_tpages-$this->_adj))? $out.= "<a href='javascript:void(0);' id='$this->_tpages'>$this->_tpages</a>" : '';
		if($this->_page<$this->_tpages) {
    			$out.= "<span style='color: greenyellow;position:absolute; right:40px; top:115px; font-size:200%;'><a href='javascript:void(0);' id='".($this->_page+1)."'>$this->_next_lbl</a></span>";
		}else {
			$out.= "<span>$this->_next_lbl</span>";
		}
		$out.= "</div>";
		return $out;
	}
}
?>
