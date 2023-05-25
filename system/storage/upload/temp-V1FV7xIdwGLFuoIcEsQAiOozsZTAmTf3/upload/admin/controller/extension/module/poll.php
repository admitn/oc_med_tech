<?php
class ControllerExtensionModulepoll extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('extension/module/poll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('poll', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_poll'] = $this->language->get('entry_poll');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_poll'] = $this->language->get('button_poll');
		
		$data['poll_href'] = $this->url->link('extension/poll', 'token=' . $this->session->data['token'], true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/poll', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/poll', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/poll', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/poll', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['poll_id'])) {
			$data['poll_id'] = $this->request->post['poll_id'];
		} elseif (!empty($module_info)) {
			$data['poll_id'] = $module_info['poll_id'];
		} else {
			$data['poll_id'] = '';
		}

		$this->load->model('extension/poll');

		$data['polls'] = $this->model_extension_poll->getPolls();
        $data['polls'][] = array('poll_id'=>-1, 'name'=>'Все включеные голосования', 'status'=>1);
		array_unshift($data['polls'], array('poll_id'=>0, 'name'=>'Один неголосованный', 'status'=>1) );
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/poll', $data));
	}

	public function install() { 
	
		$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'poll"');
		if(!$query->num_rows) {
			$this->db->query(
					"CREATE TABLE
						`" . DB_PREFIX . "poll` (
						`poll_id` int(11) NOT NULL AUTO_INCREMENT,
						`status` int(1) NOT NULL,
						`sort_order` int(3) NOT NULL,
						`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
						`date_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
						`date_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
						PRIMARY KEY (`poll_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
			);
		}
	
		$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'poll_answer"');
		if(!$query->num_rows) {
			$this->db->query(
					"CREATE TABLE
						`" . DB_PREFIX . "poll_answer` (
						`answer_id` int(11) NOT NULL AUTO_INCREMENT,
						`poll_id` int(11) NOT NULL,
						`sort_order` int(3) NOT NULL,
						PRIMARY KEY (`answer_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
			);
		}
	
		$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'poll_answer_description"');
		if(!$query->num_rows) {
			$this->db->query(
					"CREATE TABLE
						`" . DB_PREFIX . "poll_answer_description` (
						`answer_id` int(11) NOT NULL,
						`language_id` int(11) NOT NULL,
						`name` varchar(256) NOT NULL, 
						PRIMARY KEY (`answer_id`, `language_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
			);
		}
	
		$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'poll_description"');
		if(!$query->num_rows) {
			$this->db->query(
					"CREATE TABLE
						`" . DB_PREFIX . "poll_description` (
						`poll_id` int(11) NOT NULL,
						`language_id` int(11) NOT NULL,
						`name` varchar(128) NOT NULL,
						`small_description` varchar(2048) NOT NULL,
						`description` TEXT NOT NULL,
						`meta_title` varchar(2048) NOT NULL,
						`meta_h1` varchar(2048) NOT NULL,
						`meta_keyword` varchar(2048) NOT NULL,
						PRIMARY KEY (`poll_id`,`language_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
			);
		}
	
		$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'poll_vote"');
		if(!$query->num_rows) {
			$this->db->query(
					"CREATE TABLE
						`" . DB_PREFIX . "poll_vote` (
						`poll_id` int(11) NOT NULL,
						`customer_id` int(11) NOT NULL,
						`answer_id` int(11) NOT NULL,
						`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
						`date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
						PRIMARY KEY (`poll_id`,`customer_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8"
			);
		}
	
	
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/module/poll');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/module/poll');
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/poll');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/poll');
		
    }	

	public function uninstall() { 
		$this->db->query('DROP TABLE `' . DB_PREFIX . 'poll`');
		$this->db->query('DROP TABLE `' . DB_PREFIX . 'poll_answer`');
		$this->db->query('DROP TABLE `' . DB_PREFIX . 'poll_answer_description`');
		$this->db->query('DROP TABLE `' . DB_PREFIX . 'poll_description`');
		$this->db->query('DROP TABLE `' . DB_PREFIX . 'poll_vote`');
		
        $this->load->model('user/user_group');
        $this->model_user_user_group->removePermission($this->user->getId(), 'access', 'extension/module/poll');
        $this->model_user_user_group->removePermission($this->user->getId(), 'modify', 'extension/module/poll');
        $this->model_user_user_group->removePermission($this->user->getId(), 'access', 'extension/poll');
        $this->model_user_user_group->removePermission($this->user->getId(), 'modify', 'extension/poll');
	}	
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/poll')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}