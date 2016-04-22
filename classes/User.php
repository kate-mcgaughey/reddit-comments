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

	
}