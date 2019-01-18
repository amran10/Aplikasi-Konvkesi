<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_kalender extends CI_Model {
/*Read the data from DB */
	Public function getEvents()
	{
		
	$sql = "SELECT * FROM events WHERE events.start BETWEEN ? AND ? ORDER BY events.start ASC";
	return $this->db->query($sql, array($_GET['start'], $_GET['end']))->result();
	}
/*Create new events */
	Public function addEvent()
	{
	$sql = "INSERT INTO events (title,events.start,events.end,description, color) VALUES (?,?,?,?,?)";
	$this->db->query($sql, array($_POST['title'], $_POST['start'],$_POST['end'], $_POST['description'], $_POST['color']));
		return ($this->db->affected_rows()!=1)?false:true;
	}
	/*Update  event */
	Public function updateEvent()
	{
	$sql = "UPDATE events SET title = ?, description = ?, color = ? WHERE id = ?";
	$this->db->query($sql, array($_POST['title'],$_POST['description'], $_POST['color'], $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}
	/*Delete event */
	Public function deleteEvent()
	{
	$sql = "DELETE FROM events WHERE id = ?";
	$this->db->query($sql, array($_GET['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}
	/*Update  event */
	Public function dragUpdateEvent()
	{
			//$date=date('Y-m-d h:i:s',strtotime($_POST['date']));
			$sql = "UPDATE events SET  events.start = ? ,events.end = ?  WHERE id = ?";
			$this->db->query($sql, array($_POST['start'],$_POST['end'], $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	public function get_events($start, $end)
{
    return $this->db->where("start >=", $start)->where("end <=", $end)->get("calendar_events");
}

	public function add_event($data)
	{
	    $this->db->insert("calendar_events", $data);
	}

	public function get_event($id)
	{
	    return $this->db->where("ID", $id)->get("calendar_events");
	}

	public function update_event($id, $data)
	{
	    $this->db->where("ID", $id)->update("calendar_events", $data);
	}

	public function delete_event($id)
	{
	    $this->db->where("ID", $id)->delete("calendar_events");
	}
	
}