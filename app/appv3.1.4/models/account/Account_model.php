<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct();
	}

    function user_info_by_list_uid($lstUserid)
    {
        $data = array();
		$iconn = $this->db->conn_id;
        $sql   = "CALL sl_user_info_by_list_uid(:lstUserid);";
        $stmt  = $iconn->prepare($sql);
        if($stmt)
        {
            $stmt->bindParam(':lstUserid', $lstUserid, PDO::PARAM_STR);
            if($stmt->execute())
            {
                if($stmt->rowCount() > 0)
                {
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        $data[$row['user_id']] = $row;
                    }
                }
            }
            $stmt->closeCursor();
        }
		
    	return $data;
    }
    
    function user_info($user_id)
    {
    	$data = array();
		// get user info
		$uInfo = SSOServices::user_info($user_id);
		
        $iconn = $this->db->conn_id;
		$sql = "CALL sl_user_info(:user_id);";
		$stmt = $iconn->prepare($sql);
		if($stmt)
		{
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			if($stmt->execute())
			{
				if($stmt->rowCount() > 0)
				{
					$data = $stmt->fetch(PDO::FETCH_ASSOC);
				}
				$stmt->closeCursor();
			}
		}

    	if(!empty($data))
		{
			$data['phone'] = '';
			$data['contact_name'] = '';
			$data['email_address'] = '';
			$data['address'] = '';
			$data['gender'] = '0';
			$data['birthday'] = '';
			$data['gau'] = '';
			$data['password'] = '';

			if(!empty($uInfo))
			{
				$data['phone'] = $uInfo['phone'];
				$data['contact_name'] = $uInfo['fullname'];
				$data['email_address'] = $uInfo['email'];
				$data['address'] = $uInfo['address'];
				$data['gender'] = $uInfo['gender'];
				$data['birthday'] = $uInfo['birthday'];
				$data['password'] = $uInfo['password'];
				$data['gau'] = $uInfo['gau'];
				
				if($data['groupid'] != $uInfo['groupid'] || $data['active'] != $uInfo['active'] || $data['block'] != $uInfo['block'] || $data['delete'] != $uInfo['delete'] || $data['issale'] != $uInfo['issale'] || $data['saleid'] != $uInfo['saleid'])
				{
					$sql = "CALL sl_user_sync_from_sso(:user_id, :username, :groupid, :issale, :saleid, :active, :block, :delete, :role);";
					$stmt = $iconn->prepare($sql);
					if($stmt)
					{
						$role = $data['role'];
						$stmt->bindParam(':user_id', $uInfo['user_id'], PDO::PARAM_INT);
						$stmt->bindParam(':username', $uInfo['username'], PDO::PARAM_STR);
						$stmt->bindParam(':groupid', $uInfo['groupid'], PDO::PARAM_INT);
						$stmt->bindParam(':issale', $uInfo['issale'], PDO::PARAM_INT);
						$stmt->bindParam(':saleid', $uInfo['saleid'], PDO::PARAM_INT);
						$stmt->bindParam(':active', $uInfo['active'], PDO::PARAM_INT);
						$stmt->bindParam(':block', $uInfo['block'], PDO::PARAM_INT);
						$stmt->bindParam(':delete', $uInfo['delete'], PDO::PARAM_INT);
						$stmt->bindParam(':role', $role, PDO::PARAM_INT);
		                if($stmt->execute())
						{
							$stmt->closeCursor();
						}
					}
				}
			}
		}
		// sync user info to safelist db
		else
		{
			if(!empty($uInfo))
			{
				
				//syn user
				$sql = "CALL sl_user_sync_from_sso(:user_id, :username, :groupid, :issale, :saleid, :active, :block, :delete, :role);";
				$stmt = $iconn->prepare($sql);
				if($stmt)
				{
					$role = '3';
					$stmt->bindParam(':user_id', $uInfo['user_id'], PDO::PARAM_INT);
					$stmt->bindParam(':username', $uInfo['username'], PDO::PARAM_STR);
					$stmt->bindParam(':groupid', $uInfo['groupid'], PDO::PARAM_INT);
					$stmt->bindParam(':issale', $uInfo['issale'], PDO::PARAM_INT);
					$stmt->bindParam(':saleid', $uInfo['saleid'], PDO::PARAM_INT);
					$stmt->bindParam(':active', $uInfo['active'], PDO::PARAM_INT);
					$stmt->bindParam(':block', $uInfo['block'], PDO::PARAM_INT);
					$stmt->bindParam(':delete', $uInfo['delete'], PDO::PARAM_INT);
					$stmt->bindParam(':role', $role, PDO::PARAM_INT);
	                if($stmt->execute())
					{
						$stmt->closeCursor();
						
						$sql2 = "CALL sl_user_info(:user_id);";
				        $stmt2 = $iconn->prepare($sql2);
						if($stmt)
						{
							$stmt2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
							if($stmt2->execute())
							{
								if($stmt2->rowCount() > 0)
								{
				                    $data = $stmt2->fetch(PDO::FETCH_ASSOC);
								}
								$stmt2->closeCursor();
							}
						}
					}
				}
				if(!empty($data))
				{
					$data['phone'] = $uInfo['phone'];
					$data['contact_name'] = $uInfo['fullname'];
					$data['email_address'] = $uInfo['email'];
					$data['address'] = $uInfo['address'];
					$data['gender'] = $uInfo['gender'];
					$data['birthday'] = $uInfo['birthday'];
					$data['password'] = $uInfo['password'];
					$data['gau'] = $uInfo['gau'];
				}
			}
		}
    	return $data;
    }
}