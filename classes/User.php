<?php
namespace Edu\Cnm\Kmcgaughey\Reddit-Comments;

/**
 * Deconstruction of Reddit Comments
 * DOC BLOCK
 */

class User implements \JsonSerializable {
	/**
	 * id for this User; this is the primary key
	 * * @var int $userId
	 **/
	private $userId;
	**/
	* email for thie User; this is a unique index
	*@var string $userEmail
	**/
	private $userEmail;
	/**

	 /**
	 * constructor for this User
	 *
	 * @param int|null $newUserId of this User or null if a new User
	 * @param string $newUserEmail string containing newUserEmail
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurrs
	 **/

	public function __construct(int $newUserId = null, string $newUserEmail) {
		try {
			$this->setUserId($newUserId);
			$this->setUserEmail($newUserEmail);
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
		 * inserts this User into MySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL-related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 * **/

		public_function insert(\PDO $pdo) {
			// enforce the userId is null and doesn't already exist
			if($this->userId !== null) {
				throw(new \PDOException("Not a new userId"));
			}

			// crete query template   NOTE: I am suspecting at this point I may want another primary key, UserName
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
			
		}









		public_function getUserById($id);

























































































	}
}