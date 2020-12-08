<?php 
namespace App\Models\Admin;

use CodeIgniter\Model;

class M_Settings extends Model {
    protected $DBGroup = 'default';
    
    protected $table        = 'settings';
    protected $primaryKey   = 'id';

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;    //Need new column such as created_at, updated_at, deleted_at

    protected $allowedFields = ['name', 'value', 'type'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $builder;

    /**
	 * Constructor
	 *
	 * @return void
	 */
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table($this->table);
    }

     /**
	 * Get all setting data.
	 *
	 * @param  string   $id
	 *
	 * @return array    success
	 * @return boolean  failed
	 * @author Naufal Hakim
	 */
    public function get_all()
    {
        return $this->findAll();
        // return $this->db->table('settings')->get();
        // return $this->builder->get();
    }

    /**
	 * Get setting data by id.
	 *
	 * @param  string   $id
	 *
	 * @return array    success
	 * @return boolean  failed
	 * @author Naufal Hakim
	 */
    public function get_by_id(int $id)
    {
        if($id){
            return $this->find($id);
            // return $this->where('id', $id)->findAll();;
        } else {
            return false;
        }
    }
    
    /**
	 * Get setting data by name.
	 *
	 * @param  string   $name
	 *
	 * @return array    success
	 * @return boolean  failed
	 * @author Naufal Hakim
	 */
    public function get_by_name(string $name)
    {
        if(!empty($name)){
            $result = $this->where('name', $name)->first();
            // $result = $this->builder->where('name', $name)->get(1);
            if($result){
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
	 * Updates a setting row with name.
	 *
	 * @param string $name Setting Name.
	 * @param array|string  $data
	 *
	 * @return boolean
	 * @author Naufal Hakim
	 */
    public function set_by_name(string $name, $data)
    {
        if(!empty($data) && !empty($name)){
            if(is_array($data)){
                return $this->builder->where('name', $name)->update($data);
            } else {
                return $this->builder->set('value', $data)->where('name', $name)->update();
            }
        } else {
            return false;
        }
    }

    /**
	 * Updates a setting row with id.
	 *
	 * @param integer   $id Settings id
	 * @param array     $data
	 *
	 * @return boolean
	 * @author Naufal Hakim
	 */
    public function set_by_id(int $id, $data)
    {
        if(!empty($data) && !empty($id)){
            return $this->update($id, $data);
            // return $this->builder->where('id', $id)->update($data);
        } else {
            return false;
        }
    }

    /**
	 * Create a new setting row.
	 *
	 * @param array     $data
	 *
	 * @return boolean
	 * @author Naufal Hakim
	 */
    public function create($data)
    {
        if(!empty($data)){
            return $this->insert($data);
            // return $this->builder->insert($data);
        } else {
            return false;
        }
    }
}
?>