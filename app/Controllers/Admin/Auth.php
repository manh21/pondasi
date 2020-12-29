<?php namespace App\Controllers\Admin;

class Auth extends \IonAuth\Controllers\Auth
{
    /**
     * If you want to customize the views,
     *  - copy the ion-auth/Views/auth folder to your Views folder,
     *  - remove comment
     */
	protected $viewsFolder = 'App\Views\auth';
	
	public function __construct()
	{
		parent::__construct();
		// helper(['general']);
	}

    public function index()
    {
        if (!$this->ionAuth->loggedIn())
		{
			// redirect them to the login page
			return redirectAdmin('auth/login');
		}
		else if (!$this->ionAuth->isAdmin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			//show_error('You must be an administrator to view this page.');
			throw new \Exception('You must be an administrator to view this page.');
		}
		else
		{
			return redirectAdmin('admin/userlist');
		}
    }

    /**
	 * Log the user in
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
    public function login()
	{

        if ($this->ionAuth->loggedIn())
		{
			// redirect them to the login page
			return redirectAdmin('admin');
			// return redirect()->to('/admin');
		}

		$this->data['title'] = lang('Auth.login_heading');

		// validate form input
        $this->validation->setRules([
            'identity' => 'required',
            'password' => 'required|min_length[8]'
        ]);

		if ($this->request->getPost() && $this->validation->withRequest($this->request)->run())
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->request->getVar('remember');

			if ($this->ionAuth->login($this->request->getVar('identity'), $this->request->getVar('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->setFlashdata('message', $this->ionAuth->messages());
				return redirectAdmin('admin');
				// return redirect()->to('/admin');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
				// use redirects instead of loading views for compatibility with MY_Controller libraries
				return redirect()->back()->withInput();
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

			return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'login', $this->data);
		}
    }
    
    /**
	 * Log the user out
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function logout()
	{		
		$this->data['title'] = 'Logout';

		// log the user out
		$this->ionAuth->logout();

		// redirect them to the login page
		$this->session->setFlashdata('message', $this->ionAuth->messages());
		return redirectAdmin('auth/login');
		// return redirect()->to('/auth/login');
	}

	/**
	 * Change password
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function change_password()
	{
		$this->validation->setRule('old', lang('Auth.change_password_validation_old_password_label'), 'required');
		$this->validation->setRule('new', lang('Auth.change_password_validation_new_password_label'), 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
		$this->validation->setRule('new_confirm', lang('Auth.change_password_validation_new_password_confirm_label'), 'required');

		if (! $this->ionAuth->loggedIn())
		{
			return redirectAdmin('auth/login');
			// return redirect()->to('/auth/login');
		}

		$user = $this->ionAuth->user()->row();

		if ($this->validation->run() === false)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = ($this->validation->getErrors()) ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

			$this->data['minPasswordLength'] = $this->configIonAuth->minPasswordLength;
			$this->data['old_password'] = [
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			];
			$this->data['new_password'] = [
				'name'    => 'new',
				'id'      => 'new',
				'type'    => 'password',
				'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
			];
			$this->data['new_password_confirm'] = [
				'name'    => 'new_confirm',
				'id'      => 'new_confirm',
				'type'    => 'password',
				'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
			];
			$this->data['user_id'] = [
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			];

			// render
			return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'change_password', $this->data);
		}
		else
		{
			$identity = $this->session->get('identity');

			$change = $this->ionAuth->changePassword($identity, $this->request->getPost('old'), $this->request->getPost('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->setFlashdata('message', $this->ionAuth->messages());
				$this->logout();
			}
			else
			{
				$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
				return redirectAdmin('auth/change_password');
				// return redirect()->to('/auth/change_password');
			}
		}
    }
    
    /**
	 * Forgot password
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function forgot_password()
	{
		$this->data['title'] = lang('Auth.forgot_password_heading');

		// setting validation rules by checking whether identity is username or email
		if ($this->configIonAuth->identity !== 'email')
		{
			$this->validation->setRule('identity', lang('Auth.forgot_password_identity_label'), 'required');
		}
		else
		{
			$this->validation->setRule('identity', lang('Auth.forgot_password_validation_email_label'), 'required|valid_email');
		}

		if (! ($this->request->getPost() && $this->validation->withRequest($this->request)->run()))
		{
			$this->data['type'] = $this->configIonAuth->identity;
			// setup the input
			$this->data['identity'] = [
				'name' => 'identity',
				'id'   => 'identity',
			];

			if ($this->configIonAuth->identity !== 'email')
			{
				$this->data['identity_label'] = lang('Auth.forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = lang('Auth.forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
			return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
		}
		else
		{
			$identityColumn = $this->configIonAuth->identity;
			$identity = $this->ionAuth->where($identityColumn, $this->request->getPost('identity'))->users()->row();

			if (empty($identity))
			{
				if ($this->configIonAuth->identity !== 'email')
				{
					$this->ionAuth->setError('Auth.forgot_password_identity_not_found');
				}
				else
				{
					$this->ionAuth->setError('Auth.forgot_password_email_not_found');
				}

				$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
				return redirectAdmin('auth/forgot_password');
				// return redirect()->to('/auth/forgot_password');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ionAuth->forgottenPassword($identity->{$this->configIonAuth->identity});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->setFlashdata('message', $this->ionAuth->messages());
				return redirectAdmin('auth/login');
				// return redirect()->to('/auth/login'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
				return redirectAdmin('auth/forgot_password');
				// return redirect()->to('/auth/forgot_password');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function reset_password($code = null)
	{
		if (! $code)
		{
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$this->data['title'] = lang('Auth.reset_password_heading');

		$user = $this->ionAuth->forgottenPasswordCheck($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->validation->setRule('new', lang('Auth.reset_password_validation_new_password_label'), 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
			$this->validation->setRule('new_confirm', lang('Auth.reset_password_validation_new_password_confirm_label'), 'required');

			if (! $this->request->getPost() || $this->validation->withRequest($this->request)->run() === false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

				$this->data['minPasswordLength'] = $this->configIonAuth->minPasswordLength;
				$this->data['new_password'] = [
					'name'    => 'new',
					'id'      => 'new',
					'type'    => 'password',
					'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
				];
				$this->data['new_password_confirm'] = [
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
				];
				$this->data['user_id'] = [
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				];
				$this->data['code'] = $code;

				// render
				return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
			}
			else
			{
				$identity = $user->{$this->configIonAuth->identity};

				// do we have a valid request?
				if ($user->id != $this->request->getPost('user_id'))
				{
					// something fishy might be up
					$this->ionAuth->clearForgottenPasswordCode($identity);

					throw new \Exception(lang('Auth.error_security'));
				}
				else
				{
					// finally change the password
					$change = $this->ionAuth->resetPassword($identity, $this->request->getPost('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->setFlashdata('message', $this->ionAuth->messages());
						return redirectAdmin('auth/login');
						// return redirect()->to('/auth/login');
					}
					else
					{
						$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
						return redirectAdmin('auth/reset_password/' . $code);
						// return redirect()->to('/auth/reset_password/' . $code);
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
			return redirectAdmin('auth/forgot_password');
			// return redirect()->to('/auth/forgot_password');
		}
	}

}