<?php
class ControllerExtensionModulePoll extends Controller {
	public function index($setting) {
		static $module = 1;
		
		$this->load->language('extension/module/poll');
		$data['button_votes'] = $this->language->get('button_votes');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_date_start'] = $this->language->get('text_date_start');
		$data['text_date_end'] = $this->language->get('text_date_end');
		$data['text_not_voted'] = $this->language->get('text_not_voted');
		$data['text_detailed'] = $this->language->get('text_detailed');
		$data['text_dialog_error'] = $this->language->get('text_dialog_error');
		$data['text_dialog_title'] = $this->language->get('text_dialog_title');
		$data['text_dialog_text'] = sprintf($this->language->get('text_dialog_text'), $setting['name']);
		
		$this->load->model('extension/poll');

		$data['heading_title'] = $setting['name'];
		$data['answers'] = array();
		$data['language_id'] = (int)$this->config->get('config_language_id');
		
		$datatime = date("Y-m-d H:i:s");
		$data['datatime'] = $datatime;
		
		$data['isLogged'] = $this->customer->isLogged();
		
		if( $setting['poll_id'] == 0 && $data['isLogged'] ) {
			$setting['poll_id']  = $this->model_extension_poll->getOnePoll();
		}
		
		if( $setting['poll_id'] > 0 ) {

			$poll 	 = $this->model_extension_poll->getPoll($setting['poll_id']);
			$poll['small_description'] = html_entity_decode($poll['small_description'], ENT_QUOTES, 'UTF-8');

			$data['MyVote'] = $this->model_extension_poll->getPollVote( $setting['poll_id'] );
			$data['heading_title'] = $poll['name'];
			$poll['href'] = $this->url->link('information/poll', 'poll_id=' . $setting['poll_id'] , true);
			
			if( $poll['status']>0 ){
				$data['poll_enable'] = ($poll['date_start'] <= $datatime && $poll['date_end'] >= $datatime && $this->customer->isLogged() );
				$data['poll'] = $poll;
				$data['answers'] = $this->model_extension_poll->getPollAnswer($setting['poll_id']);
				$data['module'] = $module++;
				return $this->load->view('extension/module/poll', $data);
			}
		
		} elseif ( $setting['poll_id'] == -1 ) {
		
			$polls 	 = $this->model_extension_poll->getAllPoll(array('start'=>0, 'limit'=>20));
			
			$Return = "";
			
			foreach($polls as $_poll){
				$poll 	 = $this->model_extension_poll->getPoll($_poll['poll_id']);
				$poll['small_description'] = html_entity_decode($poll['small_description'], ENT_QUOTES, 'UTF-8');
				
				$data['heading_title'] = $poll['name'];
				$poll['name'] = '';
				$poll['href'] = $this->url->link('information/poll', 'poll_id=' . $_poll['poll_id'] , true);

				$data['MyVote'] = $this->model_extension_poll->getPollVote( $_poll['poll_id'] );
				
				$data['poll_enable'] = ($poll['date_start'] <= $datatime && $poll['date_end'] >= $datatime && $this->customer->isLogged() );
				$data['poll'] = $poll;
				$data['answers'] = $this->model_extension_poll->getPollAnswer($_poll['poll_id']);
				$data['module'] = $module++;

				$Return .= $this->load->view('extension/module/poll', $data);
			}
			return $Return;
			
		}
	}
	
	public function update() {
		$json = array();
		
		if (!$this->customer->isLogged()) {
			$json['error'] = 'Необходима авторизация';
		}
		
		if (!isset($this->request->get['answer_id']) || !isset($this->request->get['poll_id']) ) {
			$json['error'] = 'Не удалось передать ваш голос серверу.';
		} 
		if( !isset($json['error']) ){
		
			$this->load->model('extension/poll');
			$this->model_extension_poll->addPollVote( $this->request->get['poll_id'], $this->request->get['answer_id'] );
		
			$poll 	 = $this->model_extension_poll->getPoll($this->request->get['poll_id']);
			$answers = $this->model_extension_poll->getPollAnswer($this->request->get['poll_id']);
		
			$json['succes'] = 'done';
			$json['votes'] = $poll['votes'];
			$json['answers'] = $answers;
			
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}