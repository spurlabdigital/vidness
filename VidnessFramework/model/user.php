<?php

class User extends Model {
	private $id;
    private $userType;
	private $status;
	private $firstname;
	private $lastname;
	private $email;
	private $password;

	private $createdOn;
	private $updatedOn;

    public function getAll($userType = '') {
        $checkCondition = '1';
        if ($userType == USER_ADMIN) {
            $checkCondition = 'user_type = 2';
        } else if ($userType == USER_GUEST) {
            $checkCondition = 'user_type = 1';
        } else if ($userType == USER_DEFAULT) {
            $checkCondition = 'user_type = 0';
        }

        $records = $this->fetch('select * from user where ' . $checkCondition);
        $returnUsers = array();
        foreach ($records as $record) {
            $singleUser = new User();
            $singleUser->setId($record['id'])
                ->setUserType($record['user_type'])
                ->setFirstname($record['firstname'])
                ->setLastname($record['lastname'])
                ->setPassword($record['password'])
                ->setStatus($record['status'])
                ->setEmail($record['email'])
                ->setCreatedOn($record['created_on'])
                ->setUpdatedOn($record['updated_on']);

            $returnUsers[] = $singleUser;
        }

        return $returnUsers;
    }

    public function changeUserType($newType) {
        $typeId = 0;
        if ($newType == USER_ADMIN) {
            $typeId = 2;
        } else if ($newType == USER_GUEST) {
            $typeId = 1;
        }
        
        $data = array( 
            ':id' => $this->id, 
            ':type' => $typeId
        );
        
        $this->execute("update user set user_type = :type where id = :id ;", $data);
        return $this;
    }
	
	public function save() {
		if($this->id > 0 ){
			$data = array( 
				':id' => $this->id, 
				':status' =>  $this->status,
				':firstname' =>  $this->firstname,
				':lastname' =>   $this->lastname,
				':email' => $this->email,
				':password' => $this->password
			);
			$this->execute("update user set status = :status, firstname = :firstname, lastname = :lastname, email = :email, password = :password where id = :id ;", $data);
			return $this;
		}
		else{		
			$data = array( ':status' =>  $this->status,
				':firstname' =>  $this->firstname,
				':lastname' =>   $this->lastname,
				':email' => $this->email,
				':password' => $this->password);
			$this->execute("insert into user  (status, firstname, lastname, email, password) values (:status, :firstname, :lastname, :email, :password);", $data);
			$users = $this->fetch("select id from user");
			$user = $users[sizeof($users)-1];
			$this->id = intval($user['id']);
			return $this;
		}
	}

	public function load() {
		$users = $this->fetch("select * from user where ( id= :id or email = :email)", array(':email' => $this->getEmail(), ':id' => $this->getId() ));
		if( sizeof($users) > 0 ){
			$record = $users[sizeof($users)-1];
			$this->setId($record['id'])
                ->setUserType($record['user_type'])
				->setFirstname($record['firstname'])
				->setLastname($record['lastname'])
				->setPassword($record['password'])
				->setStatus($record['status'])
				->setEmail($record['email'])
				->setCreatedOn($record['created_on'])
				->setUpdatedOn($record['updated_on']);
		}	
		else{
			$this->setId(0);
		}
		return $this;
	}

	public function loadById() {
		$users = $this->fetch("select * from user where id= :id and status = 1", array(':id' => $this->getId()));
		if( sizeof($users) > 0 ){
			$record = $users[sizeof($users)-1];
			$this->setId($record['id'])
                ->setUserType($record['user_type'])
				->setFirstname($record['firstname'])
				->setLastname($record['lastname'])
				->setPassword($record['password'])
				->setStatus($record['status'])
				->setEmail($record['email'])
				->setCreatedOn($record['created_on'])
				->setUpdatedOn($record['updated_on']);
		}	
		else{
			$this->setId(0);
		}
		return $this;
	}
	
	public function loadByLoginInformation() {
		$record = $this->fetch("
			select * from user
			where email = :email 
			and password = :password", 
			array(':email' => $this->email, ':password' => md5($this->password)));

		if(sizeof($record)>0){
			$record = $record[0];
			$this->setId(intval($record['id']));
			if ($this->getId() > 0) {
				$this
                    ->setUserType($record['user_type'])
                    ->setFirstname($record['firstname'])
					->setLastname($record['lastname'])
					->setEmail($record['email'])
					->setCreatedOn($record['created_on'])
					->setUpdatedOn($record['updated_on']);
			}
		}
	}

	public function loadByEmail() {
		$record = $this->fetch("select * from user
			where email = :email", 
			array(':email'=>$this->email));

		if(sizeof($record)>0){
			$record = $record[0];
			$this->setId(intval($record['id']));
			if ($this->getId() > 0) {
				$this->setId($record['id'])
                    ->setUserType($record['user_type'])
					->setFirstname($record['firstname'])
					->setLastname($record['lastname'])
					->setPassword($record['password'])
					->setStatus($record['status'])
					->setEmail($record['email'])
					->setCreatedOn($record['created_on'])
					->setUpdatedOn($record['updated_on']);
			}
		}
	}

	public function delete() {
		$this->execute("delete from user where id = :id", array('id' => $this->id));
		return $this;
	}

	public function updatePassword() {
		$this->execute("update user set password= :password, updated_on = now() where id = :id",
			 array(':id' => $this->id, ':password' => md5($this->password)));
		return $this;
	}

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of status.
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the value of status.
     *
     * @param mixed $status the status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the value of firstname.
     *
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets the value of firstname.
     *
     * @param mixed $firstname the firstname
     *
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Gets the value of lastname.
     *
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets the value of lastname.
     *
     * @param mixed $lastname the lastname
     *
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Gets the value of email.
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the value of email.
     *
     * @param mixed $email the email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the value of password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the value of password.
     *
     * @param mixed $password the password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Gets the value of createdOn.
     *
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Sets the value of createdOn.
     *
     * @param mixed $createdOn the created on
     *
     * @return self
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Gets the value of updatedOn.
     *
     * @return mixed
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Sets the value of updatedOn.
     *
     * @param mixed $updatedOn the updated on
     *
     * @return self
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     *
     * @return self
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }
}
?>