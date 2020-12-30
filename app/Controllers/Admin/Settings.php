<?php 
namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;

use App\Controllers\Admin\AdminController;
use App\Models\Admin\M_Settings;

use IonAuth\Libraries\IonAuth;
use RuntimeException;

class Settings extends AdminController
{
    /**
	 * ResponseTrait
	 *
	 * @var CodeIgniter\Api\ResponseTrait
	 */
    use ResponseTrait;

    /**
	 * Current User Data
	 *
	 * @var App\Controllers\Admin\AdminController::getCurrentUserData
	 */
    private $currentUserData;

    /**
	 * IonAuth library
	 *
	 * @var \IonAuth\Libraries\IonAuth
	 */
    private $ionAuth;

    /**
	 * Validation library
	 *
	 * @var \CodeIgniter\Validation\Validation
	 */
	private $validation;
    
    /**
     * Setting Model
     * 
     * @var App\Models\Admin\M_Settings
     */
    private $m_settings;

    /**
	 * Constructor
	 *
	 * @return void
	 */
    public function __construct()
    {
        $this->ionAuth = new IonAuth();	
        $this->m_settings = new M_Settings();
        $this->currentUserData = parent::getCurrentUserData();
        $this->validation = Services::validation();
    }

    /**
	 * Redirect if needed, otherwise display the user list
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
    public function index()
    {
        $settings = $this->m_settings->get_all();
        
		$site_data = array(
            'appName' => 'Admin Panel',
			'pageTitle' => 'Settings',
			'contentTitle' => '',
			'authFullname' => $this->currentUserData['username'],
			'contentView' => null,
			'actionUrl' => adminURL('admin/settings/save'),
			'backWardUrl' => '#',
            'viewScripts' => [],
            'settings' => (object) array(
                'site_name'         => get_setting($settings, 'site_name'),
                'site_title'        => get_setting($settings, 'site_title'),
                'site_description'  => get_setting($settings, 'site_description'),
                'site_logo'         => get_setting($settings, 'site_logo'),
                'site_icon'         => get_setting($settings, 'site_icon'),
                'gtag'              => get_setting($settings, 'gtag'),
                'disqus'            => get_setting($settings, 'disqus'),
                'maintenance'       => get_setting($settings, 'maintenance'),
            ),
		);

		echo view('App\\Views\\admin\\settings', $site_data);
    }

    public function update()
    {
        if(!$this->request->getPost()){
            return redirectAdmin('admin/settings');
        } else {
            
            $this->validation->setRule('site_name','site_name', 'required|trim');
            $this->validation->setRule('site_title','site_title', 'required|trim');
            $this->validation->setRule('site_description','site_description', 'trim');
            $this->validation->setRule('gtag','gtag', 'trim');
            $this->validation->setRule('disqus','disqus', 'trim');
            $this->validation->setRule('maintenance','maintenance', 'trim');

            if($this->request->getPost() && $this->validation->withRequest($this->request)->run())
            {
                $maintenance = $this->request->getPost('maintenance');
                $additionalData = array(
                    'site_name' => $this->request->getPost('site_name'),
                    'site_title' => $this->request->getPost('site_title'),
                    'site_description' => $this->request->getPost('site_description'),
                    'gtag' => $this->request->getPost('gtag'),
                    'disqus' => $this->request->getPost('disqus'),
                    'maintenance' => isset($maintenance) ? 2 : 1,
                );

                if(!empty($_FILES['site_icon']) && $_FILES['site_icon']['error'] !== 4)
                {
                    if($this->validate(['site_icon' => 'uploaded[site_icon]|is_image[site_icon]']))
                    {
                        $file = $this->request->getFile('site_icon');

                        if(!$file->isValid()){
                            throw new RuntimeException($file->getErrorString().'('.$file->getError().')');
                        }

                        $file->move(FCPATH);

                        $additionalData['site_icon'] = $file->getName();
                    }
                }
                
                if(!empty($_FILES['site_logo']) && $_FILES['site_logo']['error'] !== 4)
                {
                    if($this->validate(['site_logo' => 'uploaded[site_logo]|is_image[site_logo]']))
                    {
                        $file = $this->request->getFile('site_logo');

                        if(!$file->isValid()){
                            throw new RuntimeException($file->getErrorString().'('.$file->getError().')');
                        }

                        $file->move(FCPATH);

                        $additionalData['site_logo'] = $file->getName();
                    }
                }

                var_dump($additionalData);
                foreach ($additionalData as $key => $value) {
                    $this->m_settings->set_by_name($key, $value);
                    // var_dump($this->m_settings->getlastquery()->getQuery());
                    // if(!$this->m_settings->set_by_name($key, $value)){
                    // break;
                    // }
                }

                return redirectAdmin('admin/settings');
            } 
            else 
            {
                $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : ($this->ionAuth->errors($this->validationListTemplate) ? $this->ionAuth->errors($this->validationListTemplate) : $this->session->getFlashdata('message'));
                return redirectAdmin('admin/settings');
            }
        }
    }
}

?>