<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{	
	public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function get_user_info_by_username($username) {
        $data = array();
        $iconn = $this->db->conn_id;
        $sql = "SELECT * FROM tbl_user WHERE username = :username";
        $stmt = $iconn->prepare($sql);
        if($stmt)
        {
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);

            if($stmt->execute())
            {
                if($stmt->rowCount() > 0)
                {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                $stmt->closeCursor();
            } else {
                // var_dump($stmt->errorInfo());die;
            }
        }
        $stmt->closeCursor();
        return $data;
    }
}