<?php
namespace Edu\Cnm\Kmcgaughey\RedditComments;

/**
 * This is a breakdown of Reddit comments. Comments are posted about submissions, then voted on by users, affecting their sort ordrer. Submissions and comments are structured as a thread.
 *
 * @author Kate McGaughey <therealmcgaughey@gmail.com>
 * **/

class User implements \JsonSerializable {
	/**
	 * ID for this User; this is the primary key
	 * @var int $userId
	 */
	private $userId;

	/**
	* Email associated with account; this is a unique index
	* @var string $userEmail
	**/
	private $userEmail;

	/**
	 * Has for the profile
	 * @var string $passwordHash
	 */
	private $passwordHash;

	 /**
	 * Constructor for a User
	 *
	 * @param int|null $newUserId of this User or null if a new User
	 * @param string $newUserEmail string containing newUserEmail
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurrs
	 **/

	public function __construct($newUserId, $newUserEmail, $newPasswordHash) {
		try {
			$this->setUserId($newUserId);
			$this->setUserEmail($newUserEmail);
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
 * accessor method for userId
 *
 * @return int|null value of user id (or null if new Profile)
 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * mutator method for userId
	 *
	 *@param int|null $newProfileId value of new profile Id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newUserId is not an integer
	 **/
	public function setUserId(int $newUserId = null) {
		// base case: if the userId is null, this is a new user without a mySQL assigned id (yet)
		if($newUserId === null) {
			$this->userId = null;
			return;
		}

		// verify the email is secure
		$newEmail = trim(newEmail);
		$newEmail = filter_var($newEmail, FILTER_SANITIZE_STRING);
		if(empty($newEmail) === true) {
			throw(new \InvalidArgumentException("Email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newEmail) > 254) {
			throw(new \RangeException("Email is too large"));
		}






		/**
		 * Temporary for 4/21/16 - Skipping ahead to homework assignment on DAO design pattern
		 *
		 * inserts this User into mySQL
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
}