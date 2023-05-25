<?php
class ControllerInformationPoll extends Controller {
	public function index() {
		$this->load->language('extension/module/poll');

		$this->load->model('extension/poll');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['poll_id'])) {
			$poll_id = (int)$this->request->get['poll_id'];
		} else {
			$poll_id = 0;
		}

		$poll = $this->model_extension_poll->getPoll($poll_id);

		if ($poll) {

			if ($poll['meta_title']) {
				$this->document->setTitle($poll['meta_title']);
			} else {
				$this->document->setTitle($poll['name']);
			}

			$this->document->setDescription($poll['small_description']);
			$this->document->setKeywords($poll['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $poll['name'],
				'href' => $this->url->link('information/poll', 'poll_id=' .  $poll_id)
			);

			if ($poll['meta_h1']) {
				$data['heading_title'] = $poll['meta_h1'];
			} else {
				$data['poll_title'] = $poll['name'];
			}
			
			$data_poll['button_votes'] = $this->language->get('button_votes');
			$data_poll['text_login'] = $this->language->get('text_login');
			$data_poll['text_date_start'] = $this->language->get('text_date_start');
			$data_poll['text_date_end'] = $this->language->get('text_date_end');
			$data_poll['text_not_voted'] = $this->language->get('text_not_voted');
			$data_poll['text_detailed'] = $this->language->get('text_detailed');
			$data_poll['text_dialog_error'] = $this->language->get('text_dialog_error');
			$data_poll['text_dialog_title'] = $this->language->get('text_dialog_title');
			$data_poll['text_dialog_text'] = sprintf($this->language->get('text_dialog_text'), $poll['name']);
			
			$datatime = date("Y-m-d H:i:s");
			$data_poll['datatime'] = $datatime;
			
			$data_poll['language_id'] = (int)$this->config->get('config_language_id');
			$data_poll['isLogged'] = $this->customer->isLogged();

			$poll['small_description'] = html_entity_decode($poll['small_description'], ENT_QUOTES, 'UTF-8');

			$data_poll['MyVote'] = $this->model_extension_poll->getPollVote( $poll_id );
			$data_poll['heading_title'] = $poll['name'];
			
			if( $poll['status']>0 ){
				$data_poll['poll_enable'] = ($poll['date_start'] <= $datatime && $poll['date_end'] >= $datatime && $this->customer->isLogged() );
				$data_poll['poll'] = $poll;
				$data_poll['answers'] = $this->model_extension_poll->getPollAnswer($poll_id);
				$data_poll['module'] = 99999;
				$data['data_poll'] =  $this->load->view('extension/module/poll', $data_poll);
			}
			

			$data['button_continue'] = $this->language->get('button_continue');

			$data['description'] = html_entity_decode($poll['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/poll', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/poll', 'poll_id=' . $poll_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

}