<?php
class ModelExtensionPoll extends Model {

	public function addPoll($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "poll set 
			  date_added = NOW()
			, status = '" . (int)$data['status'] . "'
			, sort_order = '" . (int)$data['sort_order'] . "'
			, date_start = '" . $data['date_start'] . "'
			, date_end = '" . $data['date_end'] . "'
		");
		
       	$poll_id = $this->db->getLastId();
		
		foreach ($data['poll'] as $key => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX ."poll_description SET 
				  poll_id = '" . (int)$poll_id . "'
				, language_id = '" . (int)$key . "'
				, name = '" . $this->db->escape($value['name']) . "'
				, description = '" . $this->db->escape($value['description']) . "'
				, small_description = '" . $this->db->escape($value['small_description']) . "'
				, meta_title = '" . $this->db->escape($value['meta_title']) . "'
				, meta_h1 = '" . $this->db->escape($value['meta_h1']) . "'
				, meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'
			");
        }
		
		if( isset($data['answers']) ) {
			foreach ($data['answers'] as $answer) {
				$this->db->query("INSERT INTO " . DB_PREFIX ."poll_answer SET poll_id = '" . (int)$poll_id . "' , sort_order = '" . (int)$answer['sort_order'] . "'");
				$answer_id = $this->db->getLastId();
				
				foreach ($answer['description'] as $language_id => $desc) {
					$this->db->query("INSERT INTO " . DB_PREFIX ."poll_answer_description SET 
						  answer_id = '" . (int)$answer_id . "' 
						, language_id = '" . (int)$language_id . "' 
						, name = '" . $this->db->escape($desc['name']) . "'
					");
				}
			}
		}
		
		return $poll_id;
		
	}
	
	public function editPoll($poll_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "poll SET 
			  status = '" . (int)$data['status'] . "'
			, sort_order = '" . (int)$data['sort_order'] . "' 
			, date_start = '" . $data['date_start'] . "'
			, date_end = '" . $data['date_end'] . "'
			WHERE poll_id = '" . (int)$poll_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "poll_description WHERE poll_id = '" . (int)$poll_id. "'");
		
		foreach ($data['poll'] as $key => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "poll_description SET 
				  poll_id = '" . (int)$poll_id . "'
				, language_id = '" . (int)$key . "'
				, name = '" . $this->db->escape($value['name']) . "'
				, description = '" . $this->db->escape($value['description']) . "'
				, small_description = '" . $this->db->escape($value['small_description']) . "'
				, meta_title = '" . $this->db->escape($value['meta_title']) . "'
				, meta_h1 = '" . $this->db->escape($value['meta_h1']) . "'
				, meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'
			");
		}
		
		$this->db->query("DELETE pa, pad FROM " . DB_PREFIX . "poll_answer pa 
			LEFT JOIN " . DB_PREFIX . "poll_answer_description pad ON (pa.answer_id = pad.answer_id) 
			WHERE pa.poll_id = '" . (int)$poll_id. "'");
			
		if( isset($data['answers']) ) {
			foreach ($data['answers'] as $answer) {
				if(!isset($answer['answer_id'])) { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "poll_answer SET poll_id = '" . (int)$poll_id . "', sort_order = '" . (int)$answer['sort_order'] . "'");
					$answer_id = $this->db->getLastId();
				} else {
					$answer_id = (int)$answer['answer_id'];
					$this->db->query("INSERT INTO " . DB_PREFIX . "poll_answer SET poll_id = '" . (int)$poll_id . "', answer_id = '" . $answer_id . "', sort_order = '" . (int)$answer['sort_order'] . "'");
				}
				
				foreach ($answer['description'] as $language_id => $desc) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "poll_answer_description SET 
						  answer_id = '" . (int)$answer_id . "' 
						, language_id = '" . (int)$language_id . "' 
						, name = '" . $this->db->escape($desc['name']) . "'
					");
				}
			}
		}
		
	}
	
	public function getPoll($poll_id) {
		$query = $this->db->query("SELECT *, (SELECT count(*) AS votes FROM " . DB_PREFIX . "poll_vote pv WHERE p.poll_id = pv.poll_id  ) AS votes
			FROM " . DB_PREFIX . "poll p WHERE p.poll_id = '" . (int)$poll_id . "'"); 
 
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
   
	public function getPollDescription($poll_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "poll_description WHERE poll_id = '" . (int)$poll_id . "'"); 
		
		foreach ($query->rows as $result) {
			$poll_description[$result['language_id']] = array(
				  'name'       			=> $result['name']
				, 'description' 		=> $result['description']
				, 'small_description'	=> $result['small_description']
				, 'meta_title'			=> $result['meta_title']
				, 'meta_h1'				=> $result['meta_h1']
				, 'meta_keyword'		=> $result['meta_keyword']
			);
		}
		
		return $poll_description;
	}
 
	public function getPollAnswer($poll_id) {
		$query = $this->db->query("SELECT *, (SELECT count(*) AS vote FROM " . DB_PREFIX . "poll_vote WHERE pa.answer_id = answer_id  ) AS vote 
			FROM " . DB_PREFIX . "poll_answer pa
			WHERE pa.poll_id = '" . (int)$poll_id . "' ORDER BY vote DESC, pa.sort_order ASC"); 
 
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
	
	public function getPollVotes($answer_id) {
		$query = $this->db->query("SELECT pv.customer_id, pv.date_modified, CONCAT(c.firstname, ' ', c.lastname) as customer_name
			FROM " . DB_PREFIX . "poll_vote pv 
			LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = pv.customer_id) 
			WHERE pv.answer_id = '" . (int)$answer_id . "' ORDER BY pv.date_modified DESC");
		
		if ($query->num_rows) {
			return $query->rows;
		} else {
			return false;
		}
		
	}	
 
	public function deletePollVotes($poll_id) {
		$this->db->query( "DELETE FROM " . DB_PREFIX . "poll_vote WHERE poll_id = '" . (int)$poll_id . "'" );
	}	
 
	public function getAllPoll($data) {
		$sql = "SELECT *, (SELECT count(*) AS votes FROM " . DB_PREFIX . "poll_vote pv WHERE p.poll_id = pv.poll_id  ) AS votes 
			FROM " . DB_PREFIX . "poll p 
			LEFT JOIN " . DB_PREFIX . "poll_description pd ON (p.poll_id = pd.poll_id) 
			WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
       
       $implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
       
        
		if (isset($data['start']) && isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " ORDER BY p.sort_order ASC, p.date_added DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
 
		return $query->rows;
	}
   
	public function getPolls() {
		$sql = "SELECT * FROM " . DB_PREFIX . "poll p 
			LEFT JOIN " . DB_PREFIX . "poll_description pd ON (p.poll_id = pd.poll_id) 
			WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sql .= " ORDER BY p.sort_order ASC, p.date_added DESC";
		
		$query = $this->db->query($sql);
 
		return $query->rows;
	}
   
	public function deletePoll($poll_id) {
		$sql = "DELETE " . DB_PREFIX . "poll, " . DB_PREFIX . "poll_description  FROM " . DB_PREFIX . "poll
			LEFT JOIN " . DB_PREFIX . "poll_description ON " . DB_PREFIX . "poll.poll_id = " . DB_PREFIX . "poll_description.poll_id
			WHERE " . DB_PREFIX . "poll.poll_id = '" . (int)$poll_id . "'";
		$this->db->query($sql);
			
		$sql = "DELETE " . DB_PREFIX . "poll_answer, " . DB_PREFIX . "poll_answer_description  FROM " . DB_PREFIX . "poll_answer
			LEFT JOIN " . DB_PREFIX . "poll_answer_description ON " . DB_PREFIX . "poll_answer_description.answer_id = " . DB_PREFIX . "poll_answer.answer_id
			WHERE " . DB_PREFIX . "poll_answer.poll_id = '" . (int)$poll_id . "'";
		$query = $this->db->query($sql);
			
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'poll_id=" . (int)$poll_id. "'");
		
	}
   
	public function copyPoll($poll_id) {
		$data = $this->model_extension_poll->getPoll($poll_id);
		$data['poll'] = $this->model_extension_poll->getPollDescription($poll_id);
		$data['answers'] = $this->model_extension_poll->getPollAnswer($poll_id);

		$data['status']=0;
		$data['sort_order']=-1;

		$this->addPoll($data);
	}
   
	public function getTotalPoll($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "poll p 
			LEFT JOIN " . DB_PREFIX . "poll_description pd ON p.poll_id = pd.poll_id 
			WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
		
        $implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
		   $sql .= " AND " . implode(" AND ", $implode);
		}
        
       	$query = $this->db->query($sql);

		return (isset($query->row['total']) ? $query->row['total'] : 0 );
	}
}