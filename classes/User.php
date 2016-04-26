<?php
namespace Edu\Cnm\Kmcgaughey\RedditComments;

/**
 * This is a breakdown of Reddit comments. Comments are posted about submissions, then voted on by users, affecting their sort ordrer. Submissions and comments are structured as a thread.
 *
 * @author Kate McGaughey <therealmcgaughey@gmail.com>
 * **/

class User implements \JsonSerializable {

	/**
	 * id for this User; this is the primary key
	 * @var int $userId
	 */
	private $userId;

	/**
	 * Username or at-handle; this is a unique index
	 * @var string $username
	 */
	private $username;

	/**
	 * Has for the profile; (this is a unique index?)
	 * @var string $passwordHash
	 */
	private $passwordHash;

	/**
	 * Constructor method for User <--Do I really mean User here?
	 *
	 * @param int|null $newUserId if of this User or null if a new User
	 * @param string $newUsername string containing newUsername of the User
	 * @param string $newPasswordHash Hash of the profile
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurrs
	 */
	public function __construct(int $newUserId = null, string $newUsername, string $newPasswordHash) {
		try {
				$this->setUserId($newUserId);
				$this->setUsername($newUsername);
				$this->setPasswordHash($newPasswordHash);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}catch(\RangeException $range) {
			// rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		}catch(\TypeError $typeError) {
			//rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		}catch(\Exception $exception)	{
			//rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}
/**
 * Accessor method for userId
 *
 * @return int|null value of user id (or null if new User)
 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * Mutator method for userId
	 *
	 * @param int|null $newUserId value of new User ID
	 * @throws \RangeException if $newUserId is not positive
	 * @throws  \TypeError if $newUserId is not an integer
	 **/
	public function setUserId(int $newUserId = null) {
		// Base Case: if the userId is null, this is a new User without a mySQL assigned id (yet)
		if($newUserId === null) {
			$this->userId = null;
			return;
		}

		// Verify the new User ID is positive
		if($newUserId <= 0) {
			throw(new \RangeException("profile id is not positive"));
		}

		// Convert and store the User id
		$this->userId = intval($newUserId);
	}

	/**
	 * Accessor method for username
	 *
	 * @return string value of username
	 **/
	public function getUsername() {
		return($this->username);
	}

	/**
	 * Mutator method for username
	 *
	 * @param string $newUsername value of new username
	 * @throws \InvalidArgumentException if $newUsername is not a string or insecure
	 * @throws \RangeException if $newUsername is > 20 characters
	 * @throws \TypeError if $newUsername is not a string
	 **/
	public function setUsername(string $newUsername) {
		// Verify the username is secure
		$newUsername = trim($newUsername);
		$newUsername = filter_var($newUsername, FILTER_SANITIZE_STRING);
		if(empty($newUsername) === true) {
			throw (new \InvalidArgumentException("username is empty or insecure"));
		}
		// Verify the username will fit in the database
		if(strlen($newUsername) > 20) {
			throw (new \RangeException("username is limited to 20 characters"));
		}
		// Store the username
		$this->username = $newUsername;
	}

	/**
	 * Accessor method for the hash
	 *
	 * @return string value of hash
	 *
	 */
	public function getPasswordHash() {
		return $this->passwordHash;
	}

	/** Mutator for the hash
	 *
	 * @param string $newPasswordHash hash of the profile
	 */
	public function setPasswordHash($newPasswordHash) {
		// Verify the hash is secure
		$newPasswordHash = trim($newPasswordHash);
		$newPasswordHash = filter_var($newPasswordHash, FILTER_SANITIZE_STRING);
		if(empty($newPasswordHash) === true) {
			throw (new \InvalidArgumentException("Hash is empty or insecure"));
		}

		// Verify the hash will fit in the database
		if(strlen($newPasswordHash) > 64) {
			throw (new \RangeException("Hash too large"));
		}
		// Store the new hash
		$this->passwordHash = $newPasswordHash;
	}

		/**
		 *
		 * Inserts this User into mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL-related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 * **/
	public function insert(\PDO &$pdo) {
			// Make sure this is a new user
			if($this->userId !== null) {
				throw(new \PDOException("Not a new user"));
			}

			// Crete query template
			$query = "INSERT INTO user(username, passwordHash) VALUES(:username, passwordHash)";
			$statement = $pdo->prepare($query);

			// Bind the member variables to the place holders in the template
			$parameters = array(["username" => $this->getUsername(), "passwordHash" => $this->getPasswordHash()]);
			$statement->execute($parameters);

			// Update the null user id with what mySQL generated
			$this->setUserId(intval($pdo->lastInsertId()));
		}

		/**
		 * Deletes this user from mySQL
		 *
		 * @param PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL-related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 **/
	public function delete(PDO &$pdo) {
		// Make sure this user exists
		if($this->userId === null) {
			throw (new \PDOException("Unable to delete a user that does not exist"));
		}

		// Create query template
		$query = "DELETE FROM user WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		// Bind the member variables to the placeholders in the templates
		$parameters = array("userId" => $this->getUserId());
		$statement->execute($parameters);
		}
		/**
		 * Updates this User in mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL-related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 * **/
	public function update(\PDO $pdo) {
			// Make sure this user exists
			if($this->userId === null) {
				throw(new \PDOException("Unable to update a profile that does not exist"));
			}

			// Create query template
			$query = "UPDATE user SET username = :username, passwordHash = :passwordHash WHERE userId = :userId";
			$statement = $pdo->prepare($query);

			//Bind the member variables to the placeholders in the templates
			$parameters = array("username" => $this->getUsername(), "passwordHash" => $this->getPasswordHash(), "userId" => $this->getUserId());
				$statement->execute($parameters);
		}

		/**
		 * Gets the User by userId
		 *
		 * @param \PDO $pdo $pdo PDO connection object
		 * @param int $userId User id to search for
		 * @return User|null User or null if not found
		 * @throws \PDOException when mySQL-related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 * **/
	public static function getUserByUserId(\PDO $pdo, int $userId) {
			// Sanitize the userId before searching
			$userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
		/**
		 * Note to self: This is the part I was way confused about. Here are Skyler's helpful comments:
		 * "Actually, all of the _____Ids that you have in each of your entities are generated by MySQL as integers (this is because we represent the Ids in MySQL as `INT UNSIGNED NOT NULL AUTO_INCREMENT`). The username/profileName/etc would be stored in its own username attribute."
		 */
			if($userId === false) {
				throw(new \PDOException("User ID is not an integer"));
			}
			if($userId <= 0) {
				throw(new \PDOException('username is not positive:'));
			}
			// Create query template
			$query = "SELECT userId, username, passwordHash FROM user WHERE userId = :userId";
			$statement = $pdo->prepare($query);

			//Bind the userId to the place holder in the template
			$parameters = array("userId" => $userId);
			$statement->execute($parameters);

			//Grab the User from mySQL
			try {
				$user = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
					$user = new User($row["userId"], $row["username"], $row[$this->"passwordHash"]);
				}
			} catch(\Exception $exception) {
				// If the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return($user);
		}

		/**
		 * Gets the user by username
		 *
		 *@param \PDO $pdo PDO connection object
		 *@param  $username Username to search for
		 *@return \SplFixedArray of users found
		 *@throws \PDOException when mySQL-related errors occur
		 **/
	public static function getUserByUsername(\PDO &$pdo, $username) {
			//sanitize the email before searching
			$username = trim($username);
			$username = filter_var($username, FILTER_SANITIZE_STRING);
			if(empty($username) === true) {
				throw(new \PDOException("Username is invalid"));
			}

			//Create query template
			$query = "SELECT userId, username, passwordHash FROM user WHERE username = :username";
			$statement = $pdo->prepare($query);

			// Bind the userId to the placeholder in the template
			$username = "%$username%";
			$parameters = array("username" => $username);
			$statement->execute($parameters);

			// Build an array of users
			$users = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$user = new User($row["userId"], $row["username"], $row["passwordHash"]);
					$statement->setFetchMode(\PDO::FETCH_ASSOC);
					while(($row = $statement->fetch()) !== false) {
						try {
							$user = new User($row["userId"], $row["username"], $row["passwordHash"]);
							$users[$users->key()] = $user;
							$users->next();
						} catch(\Exception $exception) {
							// If the row couldn't be converted, rethrow it
							throw(new \PDOException($exception->getMessage(), 0, $exception));
						}
					}

					return ($users);
				}

				/**
				 * Gets all users
				 *
				 * @param PDO $pdo PDO connection object
				 * @returm SplFixedArray of users found
				 * @throws \PDOException when mySQL related errors occur
				 */
				public static function getAllUsers(PDO &$pdo) {
				// Create query template
				$query = "SELECT userId, username, passwordHash FROM user";
				$statement = $pdo->prepare($query);
				$statement->execute();

				// Build an array of users
				$users = new \SplFixedArray($statement->rowCount());
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				while(($row = $statement->fetch()) !== false) {
					try {
						$user = new \SplFixedArray($statement->rowCount());
						$user->setFetchMode(\PDO::FETCH_ASSOC);
						$user->next();
					} catch(\Exception $exception) {
						// If the row couldn't be converted, rethrow it
						throw(new \PDOException($exception->getMessage(), 0, $exception));
					}
				}

				return ($users);
				}
}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	function jsonSerialize() {
		// TODO: Implement jsonSerialize() method.
	}
}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	function jsonSerialize() {
		// TODO: Implement jsonSerialize() method.
	}

	private function setUserEmail($userEmail) {
	}
	private function setPasswordHash($passwordHash) {
}