<?php
class ControllerExtensionPoll extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('extension/module/poll');
		$this->language->load('extension/poll');
		
		$this->load->model('extension/poll');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->document->addStyle('/catalog/view/javascript/jquery/magnific/magnific-popup.css');
		$this->document->addScript('/catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		$data['votes'] = $this->url->link('extension/poll/getPollVotes', '&token=' . $this->session->data['token'], 'SSL');
		
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/poll', 'token=' . $this->session->data['token'] . $url, 'SSL')
   		);
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error'] = $this->error['warning'];
		
			unset($this->error['warning']);
		} else {
			$data['error'] = '';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}
        
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
            $data['filter_name'] = $this->request->get['filter_name']; 
		} else {
			$filter_name = null;
            $data['filter_name'] = '';
            
		}
        
       	if (isset($this->request->get['filter_date_added'])) {
		    $filter_date_added = $this->request->get['filter_date_added'];
            $data['filter_date_added'] = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
            $data['filter_date_added'] = '';
		}
        
		$url = '';
		
		$filter_data = array(
            'filter_name'       => $filter_name,
			'filter_date_added' => $filter_date_added,
			'page' => $page,
			'limit' => $this->config->get('config_limit_admin'),
			'start' => $this->config->get('config_limit_admin') * ($page - 1),
		);
		
		$total = $this->model_extension_poll->getTotalPoll($filter_data);

		$data['all_poll'] = array();
		
		$all_poll = $this->model_extension_poll->getAllPoll($filter_data);
		
		foreach ($all_poll as $poll) {
			$Action = array();
			
			$data['all_poll'][] = array (
				'poll_id' 			=> $poll['poll_id'],
				'sort_order' 		=> $poll['sort_order'],
				'name' 				=> $poll['name'],
				'votes' 			=> $poll['votes'],
				'status' 			=> $poll['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added' 		=> date($this->language->get('date_format_short'), strtotime($poll['date_added'])),
				'date_end' 		    => date($this->language->get('date_format_short'), strtotime($poll['date_end'])),
				'edit' 				=> $this->url->link('extension/poll/edit', 'poll_id=' . $poll['poll_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL')
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

		$data['heading_title'] = $this->language->get('heading_title');
		
        $data['entry_name'] = $this->language->get('entry_name');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		
		
		$data['text_sort_order'] = $this->language->get('text_sort_order');
		$data['text_title'] = $this->language->get('text_title');
		$data['text_question'] = $this->language->get('text_question');
        $data['text_answer'] = $this->language->get('text_answer');
        $data['text_votes'] = $this->language->get('text_votes');
		$data['text_date'] = $this->language->get('text_date');
		$data['text_action'] = $this->language->get('text_action');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['button_filter'] = $this->language->get('button_filter');
        $data['button_view_all_poll'] = $this->language->get('button_view_all_poll');        
		$data['button_add'] = $this->language->get('button_add');
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_votes'] = $this->language->get('button_votes');
		
        $data['token'] = $this->session->data['token'];
        
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['add'] = $this->url->link('extension/poll/insert', '&token=' . $this->session->data['token'] . $url, 'SSL');
		$data['copy'] = $this->url->link('extension/poll/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('extension/poll/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/poll_list.tpl', $data));	
	}
	
	public function edit() {
		$this->language->load('extension/poll');
		
		$this->load->model('extension/poll');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			foreach ($this->request->post['poll'] as $language_id => $value) {
				if (empty($value['meta_title'])) {
					$this->request->post['poll'][$language_id]['meta_title'] = $value['name'];
				}
				if (empty($value['meta_h1'])) {
					$this->request->post['poll'][$language_id]['meta_h1'] = $value['name'];
				}
				if (empty($value['meta_keyword'])) {
					$this->request->post['poll'][$language_id]['meta_keyword'] = $value['name'];
				}
			}
		
			$this->model_extension_poll->editPoll($this->request->get['poll_id'], $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('extension/poll', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->form();
	}
	
	public function insert() {
		$this->language->load('extension/poll');
		
		$this->load->model('extension/poll');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			foreach ($this->request->post['poll'] as $language_id => $value) {
				if (empty($value['meta_title'])) {
					$this->request->post['poll'][$language_id]['meta_title'] = $value['name'];
				}
				if (empty($value['meta_h1'])) {
					$this->request->post['poll'][$language_id]['meta_h1'] = $value['name'];
				}
				if (empty($value['meta_keyword'])) {
					$this->request->post['poll'][$language_id]['meta_keyword'] = $value['name'];
				}
			}
			
			$this->model_extension_poll->addPoll($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('extension/poll', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->form();
	}
	
	protected function form() {
		$this->language->load('extension/module/poll');
		$this->language->load('extension/poll');
		
		$this->load->model('extension/poll');
		
		$this->document->addScript('view/javascript/summernote/summernote.js');
		$this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
		$this->document->addScript('view/javascript/summernote/opencart.js');
		$this->document->addStyle('view/javascript/summernote/summernote.css');

		$this->document->addStyle('/catalog/view/javascript/jquery/magnific/magnific-popup.css');
		$this->document->addScript('/catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/poll', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->request->get['poll_id'])) {
			$data['action'] = $this->url->link('extension/poll/edit', '&poll_id=' . $this->request->get['poll_id'] . '&token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/poll/insert', '&token=' . $this->session->data['token'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('extension/poll', '&token=' . $this->session->data['token'], 'SSL');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_title'] = $this->language->get('text_title');
		$data['text_name'] = $this->language->get('text_name');
		$data['text_small_description'] = $this->language->get('text_small_description');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_meta_title'] = $this->language->get('text_meta_title');
		$data['text_meta_h1'] = $this->language->get('text_meta_h1');
		$data['text_meta_keyword'] = $this->language->get('text_meta_keyword');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_date_start'] = $this->language->get('text_date_start');
		$data['text_date_end'] = $this->language->get('text_date_end');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_sort_order'] = $this->language->get('text_sort_order');
		$data['text_votes'] = $this->language->get('text_votes');
		$data['text_answer'] = $this->language->get('text_answer');
	
		
		$data['button_add_answer'] = $this->language->get('button_add_answer');
		$data['button_del_answer'] = $this->language->get('button_del_answer');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_votes'] = $this->language->get('button_votes');
		
		$data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->error['warning'])) {
			$data['error'] = $this->error['warning'];
		} else {
			$data['error'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}
		
		
		if (isset($this->request->get['poll_id'])) {
			$poll = $this->model_extension_poll->getPoll($this->request->get['poll_id']);
			$answers = $this->model_extension_poll->getPollAnswer($this->request->get['poll_id']);
			$data['votes_url'] = $this->url->link('extension/poll/getPollVotes', '&token=' . $this->session->data['token']. '&poll_id=' . $this->request->get['poll_id'], 'SSL');
			
		} else {
			$poll = array();
			$answers = array();
		}
		
		if (isset($this->request->post['poll'])) {
			$data['poll'] = $this->request->post['poll'];
		} elseif (!empty($poll)) {
			$data['poll'] = $this->model_extension_poll->getPollDescription($this->request->get['poll_id']);
		} else {
			$data['poll'] = '';
		}
		
		if (isset($this->request->post['answers'])) {
			$data['answers'] = $this->request->post['answers'];
		} elseif (!empty($answers)) {
			$data['answers'] = $answers;
		} else {
			$data['answers'] = array();
		}

		$data['votes'] = 0;
		if (!empty($poll)) {
			$data['votes'] = $poll['votes'];
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($poll)) {
			$data['status'] = $poll['status'];
		} else {
			$data['status'] = '';
		}
		
		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($poll)) {
			$data['date_start'] = $poll['date_start'];
		} else {
			$data['date_start'] = date("Y-m-d H:i:s", time());
		}
		
		if (isset($this->request->post['date_end'])) {
			$data['date_end'] = $this->request->post['date_end'];
		} elseif (!empty($poll)) {
			$data['date_end'] = $poll['date_end'];
		} else {
			$data['date_end'] = date("Y-m-d H:i:s", time() + 86400 * 365);
		}
		
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($poll)) {
			$data['sort_order'] = $poll['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/poll_form.tpl', $data));
	}
	
	public function getPollVotes() {
		$this->language->load('extension/poll');

		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_no_votes'] = $this->language->get('text_no_votes');

		$this->load->model('extension/poll');

		$data['customer_edit'] = $this->url->link('customer/customer/edit', '&token=' . $this->session->data['token'] . '&customer_id=', 'SSL');
		$data['config_language_id'] = (int)$this->config->get('config_language_id');
			
		if (isset($this->request->get['poll_id'])) {
		
			$poll_id = $this->request->get['poll_id'];
			$data['poll_id'] = $poll_id;
			$answers = array();
			$result = $this->model_extension_poll->getPollAnswer($poll_id);
			
			foreach($result as $answer){
				$answer['votes'] = array();
				$votes = $this->model_extension_poll->getPollVotes($answer['answer_id']);
				if($votes) {
					$answer['votes'] = $votes;
				}
				$answers[] = $answer;
			}
			$data['answers'] = $answers;
			$data['clearVotes'] = $this->url->link('extension/poll/clearvotes', '&token=' . $this->session->data['token'] . '&poll_id=' . $poll_id , 'SSL');
			
			echo $this->load->view('extension/poll_votes.tpl', $data);
			
		}
	}
	
	public function clearvotes() {
		$this->language->load('extension/poll');
		$this->load->model('extension/poll');

		if (isset($this->request->get['poll_id'])) {
		
			$poll_id = (int)$this->request->get['poll_id'];

            if( $this->validate() ) {
                $result = $this->model_extension_poll->deletePollVotes($poll_id);
            }
            
            $this->response->redirect($this->url->link('extension/poll/getPollVotes', 'token=' . $this->session->data['token'] . '&poll_id=' . $poll_id, 'SSL'));
        }
	}
	
	public function delete() {
		$this->language->load('extension/poll');
		
		$this->load->model('extension/poll');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $poll_id) {
				$this->model_extension_poll->deletePoll($poll_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->response->redirect($this->url->link('extension/poll', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function copy() {
		$this->language->load('extension/poll');
		
		$this->load->model('extension/poll');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			print_r($this->request->post['selected']);
			foreach ($this->request->post['selected'] as $poll_id) {
				$this->model_extension_poll->copyPoll($poll_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->response->redirect($this->url->link('extension/poll', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/poll')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/poll')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['poll'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 6) || (utf8_strlen($value['name']) > 128)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
				$this->error['warning'] = $this->language->get('error_name');
			}
		}
		
		if( empty($this->request->post['answers']) || count($this->request->post['answers']) < 2 ){
			$this->error['answers'][$language_id] = $this->language->get('error_answers');
			$this->error['warning'] = $this->language->get('error_answers');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}