<?php
class ModelExtensionPoll extends Model {	
	
	
	public function addPollVote($poll_id, $answer_id) {
		$customer_id = $this->customer->getId();
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "poll_vote SET poll_id = '" . (int)$poll_id . "', customer_id = '" . (int)$customer_id . "', answer_id = '" . (int)$answer_id . "', date_added = NOW(), date_modified = NOW() ON DUPLICATE KEY UPDATE answer_id = '" . (int)$answer_id . "', date_modified = NOW()"); 
	}
   
	public function getPollVote($poll_id) {
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "poll_vote WHERE poll_id = '" . (int)$poll_id . "' AND customer_id = '" . (int)$customer_id . "'"); 
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
		
	}
   
	public function getPoll($poll_id) {
		$query = $this->db->query("SELECT *, (SELECT count(*) AS votes FROM " . DB_PREFIX . "poll_vote pv WHERE p.poll_id = pv.poll_id  ) AS votes
			FROM " . DB_PREFIX . "poll p 
			LEFT JOIN " . DB_PREFIX . "poll_description pd ON (p.poll_id = pd.poll_id) 
			WHERE p.poll_id = '" . (int)$poll_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"); 
 
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
   
	public function getPollAnswer($poll_id) {
		$query = $this->db->query("SELECT *, (SELECT count(*) AS vote FROM " . DB_PREFIX . "poll_vote pv WHERE pa.answer_id = pv.answer_id  ) AS vote 
			FROM " . DB_PREFIX . "poll_answer pa
			WHERE pa.poll_id = '" . (int)$poll_id . "' ORDER BY pa.sort_order ASC"); 
 
		if ($query->num_rows) {
			$ansvers = array();
			foreach($query->rows as $key => $row){
				$ansvers[$key] = $row;
				$ansvers[$key]['description'] = $this->getPollAnswerDescription($row['answer_id']);
			}
			return $ansvers;
		} else {
			return false;
		}
	}
   
	public function getPollAnswerDescription($answer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "poll_answer_description WHERE answer_id = '" . (int)$answer_id . "'"); 
		
		$poll_description = array();
		foreach ($query->rows as $result) {
			$poll_description[$result['language_id']] = array(
				'name'       		=> $result['name'],
			);
		}
		
		return $poll_description;
	}
 
	
	public function getAllPoll($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "poll p 
			LEFT JOIN " . DB_PREFIX . "poll_description pd ON (p.poll_id = pd.poll_id) 
			WHERE p.status=1 && pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY p.sort_order ASC, p.date_added DESC";
		
		if (isset($data['start']) && isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
 
		return $query->rows;
	}
	
	public function getTotalPoll() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "poll where status=1");
	
		return $query->row['total'];
	}
	
	public function getOnePoll() {
		$customer_id = $this->customer->getId();
		$sql = "SELECT p.poll_id FROM " . DB_PREFIX . "poll p 
			LEFT JOIN " . DB_PREFIX . "poll_description pd ON (p.poll_id = pd.poll_id) 
			WHERE p.status=1 && 
				( (p.date_start = '0000-00-00' || p.date_start < NOW() ) && (p.date_end = '0000-00-00' || p.date_end > NOW()) ) && 
				pd.language_id = '" . (int)$this->config->get('config_language_id') . "' && 
				(SELECT answer_id FROM " . DB_PREFIX . "poll_vote pv WHERE p.poll_id=pv.poll_id && customer_id='" . $customer_id . "'  ) IS NULL";
			
		$sql .= " ORDER BY p.sort_order ASC, p.date_added DESC LIMIT 0,1";
		
		$query = $this->db->query($sql);
		if($query->num_rows){
			return $query->row['poll_id'];
		} else {
			return 0;
		}
 
	}
	
	
}