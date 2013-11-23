<?php

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		
		
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		
		$this->load->model('users_model');
		$this->lang->load('auth');
		$this->load->helper('language');
		
	}

	function index()
	{
		$data['users'] = $this->ion_auth->users()->result();
		$data['title'] = 'Shot';
		$data['use_sidebar'] = TRUE;
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('admin/users', $data);
		$this->load->view('templates/footer');
	}

	function users()
	{
		$this->load->helper('language');
		$data['users'] = $this->ion_auth->users()->result();
		foreach ($data['users'] as $key => $user)
		{
			$data['users'][$key]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}
		//$data['users'] = $this->ion_auth_model->users();
		$data['title'] = 'Users';
		$data['use_sidebar'] = TRUE;
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('admin/users/index', $data);
		$this->load->view('templates/footer');
	}
	
	function edit_user($id)
	{
		$data['title'] = "Edit User";


		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();


		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
		//$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($id != $this->input->post('id'))
			{
				//show_error($this->lang->line('error_csrf'));
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}

			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				//redirect("auth", 'refresh');
				redirect('admin/users/');
			}
		}

		$data['groups'] = $groups;
		
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$data['user'] = array(
			'id' => $user->id,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'current_groups' => $currentGroups,
		);
		
		
		$this->load->view('admin/users/edit_modal', $data);
/*
		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);

		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page('auth/edit_user', $this->data);
 * 
 */
	}
	
	
	function _post_delete_user($id)
	{
		$this->ion_auth->delete_user($id);
		return redirect('/admin/users/', 'refresh');
	}


	function calendar()
	{
		$data['title'] = 'Calendar';
		$data['use_sidebar'] = TRUE;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);	
		$this->load->view('admin/calendar', $data);
		$this->load->view('templates/footer');
	}	
}
