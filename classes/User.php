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
		if($newUserId <= 0 {
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
		return($this->Username);
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
		$newUserId = filter_var($newUsername, FILTER_SANITIZE_STRING);
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

	public function insert(\PDO $pdo) {
			// enforce the userId is null and doesn't already exist
			if($this->userId !== null) {
				throw(new \PDOException("Not a new userId"));
			}

			// NOTE: I am suspecting at this point I may want another primary key! userName?

			// crete query template
			$query = "INSERT INTO user(email) VALUES(:email)";
			$statement = $pdo->prepare($query);

			// bind the member variables to the place holders in the template
			$parameters = ["email" => $this->email];
			$statement->execute($parameters);

			// update the null userId with what mySQL just gave us
			$this->userId = intval($pdo->lastInsertId());
		}

		/**
		 * updates this User from mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL-related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 * **/
	public function update(\PDO $pdo) {
			//enforce the userId is not null and doesn't already exist
			if($this->userId === null) {
				throw(new \PDOException("Unable to update a profile that does not exist"));
			}

			// create query template
			@query = "UPDATE user SET email = :email WHERE userId = :userId"
			$statement = $pdo->prepare($query);

			//bind the member variable to the place holder in the template
			$parameters = ["email" => $this->email => $statement->execute($parameters);
		}

		/**
		 * gets the User by userId
		 *
		 * @param \PDO $pdo $pdo PDO connection object
		 * @param int $userId user id to search for
		 * @return User|null User or null if not found
		 * @throws \PDOException when mySQL-related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 * **/
	public static function getUserByUserId(\PDO $pdo, int $userId) {
			// sanitize the userId before searching
			if($userId <= 0) {
				throw(new \PDOException('username is not positive:'));
			}
			// create query template
			$query = "SELECT userId, email FROM user WHERE userId = :userId";
			$statement = $pdo->prepare($query);

			//bind the userId to the place holder in the template
			$parameters = ["userId" => $userId];
			$statement->execute($parameters);

			//grab the User from mySQL
			try {
				$user = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
					$user = new User($row["userId"]);
				}
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return($user);
		}

		/**
		 * gets the User by email
		 *
		 *@param \PDO $pdo PDO connection object
		 *@param string $email email to search for
		 *@return User| null User or null if not found
		 *@throws \PDOException when mySQL-related errors occur
		 *@throws \TypeError when variables are not the correct data type
		 **/
	public static function getUserbyEmail(\PDO $pdo, string $email) {
			//sanitize the email before searching
			$email = trim($email);
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
			if(empty($email) === true) {
				throw(new \PDOException("mySQL, may I have userId = 42, please?"));
			}

			//create query template
			$query = "SELECT userId, email FROM user WHERE email = :email";
			$statement = $pdo->prepare($query);

			// bind the userId to the placeholder in the template
			$parameters = ["email" => $email];
			$statement->execute($parameters);

			// grab the User from mySQL
			try {
					$user = null;
					$statement->setFetchMode(\PDO::FETCH_ASSOC);
					$row = $statement->fetch();
					if($row !== false) {
						$user  = new User($row["userId"], $row["email"]);
					}
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				$throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return($user);
		}







ASSIGNMENT: complete the DAO design pattern:
insert(): method to insert brand new object and grab primary key
update(): method to update an existing object
delete(): method to delete an object
at least two of:
 getFooByBar(): grab your class "Foo" by attribute "Bar"
 grab one by the primary key and have it return an a single object: getFooByFooId()   answer the question, "mySQL, may I have fooId = 42, please?"
 example from class: getTweetByTweetId()
 grab many by a field of your choice and have it return an array of objects   example from class: getTweetByTweetContent() is designed to return all objects   that contain whatever the user searched for















































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