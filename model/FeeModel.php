<?php 

class FeeModel extends PDORepository
{
	public function createFee($feeData)
	{
		$query="INSERT INTO fee (year,month,number,amount,kind,collectorPayment,createDate, expirationDate) VALUES (?,?,?,?,?,?,CURRENT_DATE,?)";

		$stmnt = $this->executeQuery($query,array($feeData["year"],$feeData["month"], $feeData["number"],
		 										  $feeData["amount"],$feeData["kind"], $feeData["collectorPayment"],$feeData["expirationDate"]));
	}

	public function updateFee($feeData)
	{
		print_r($feeData);
		$query="UPDATE fee SET year=?,month=?,number=?,amount=?,kind=?,collectorPayment=?, expirationDate=? WHERE id=?";

		$stmnt = $this->executeQuery($query,array($feeData["year"],$feeData["month"], $feeData["number"],
			$feeData["amount"],$feeData["kind"], $feeData["collectorPayment"],$feeData["expirationDate"], $feeData["id"]));
	}

	public function deleteFee($idFee)
	{
		$query= "UPDATE fee set deleted=true where id=?";

		$stmnt = $this->executeQuery($query,array($idFee));
	}

	public function getFee($feeId)
	{
		$query = "select * from fee where id= ? and deleted=false";
		$stmnt = $this->executeQuery($query,array($feeId));
		return $stmnt ->fetch();
	}

	public function getPaidFeesOfStudent($studentID)
	{

		$query= "SELECT f.id,f.number, f.year, f.month, f.amount, f.kind, f.collectorPayment, f.createDate,f.expirationDate
                 FROM payment as p
                         inner join fee as f on (p.feeId = f.id)
                  WHERE p.studentId = ? and f.deleted=false
                  order by f.year, f.month";

		return $this->executeQuery($query,array($studentID))->fetchAll();
	}

	public function getPaidFeesOfStudentInYear($studentID, $year)
	{

		$query= "SELECT f.id,f.number, f.year, f.month, f.amount, f.kind, f.collectorPayment, p.createDate as 'fechaDePago'
                 FROM payment as p
                         inner join fee as f on (p.feeId = f.id)
                  WHERE p.studentId = ?
                  		and f.deleted=false
                  		and f.year = ?
                  order by f.month";

		return $this->executeQuery($query,array($studentID, $year))->fetchAll(PDO::FETCH_ASSOC);
	}


	// devuelve las cuotas que no fueron pagadas y excluye las cuotas vencidas, para que no puedas pagar algo que se te vencio.
	public function getUnPaidFeesOfStudent($studentID)
	{

		$query= "SELECT f.id,f.number, f.year, f.month, f.amount, f.kind, f.collectorPayment, f.createDate, f.expirationDate
                 FROM fee as f
                 WHERE f.id not in (select fe.id
                                     FROM payment as p
                         				  inner join fee as fe on (p.feeId = fe.id)
                  					WHERE p.studentId = ?  )
                  	   and f.deleted=false  and CURRENT_DATE < f.expirationDate
                  order by f.year, f.month";

		return $this->executeQuery($query,array($studentID))->fetchAll();
	}


	public function getToBePaidFeesOfStudentInYear($studentID, $year)
	{

		$query= "SELECT f.id,f.number, f.year, f.month, f.amount, f.kind, f.collectorPayment, f.expirationDate
                 FROM fee as f
                 WHERE f.id not in (select fe.id
                                     FROM payment as p
                         				  inner join fee as fe on (p.feeId = fe.id)
                  					WHERE p.studentId = ?  )
                  	   and f.deleted=false
                  	   and f.kind = 2
                  	   and f.year = ?

                  order by f.year, f.month";

		return $this->executeQuery($query,array($studentID, $year))->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getExpiredFeesOfStudent($studentID)
	{

		$query= "SELECT f.id,f.number, f.year, f.month, f.amount, f.kind, f.collectorPayment, f.createDate, f.expirationDate
                 FROM fee as f
                 WHERE f.id not in (select fe.id
                                     FROM payment as p
                         				  inner join fee as fe on (p.feeId = fe.id)
                  					 WHERE p.studentId = ?) and CURRENT_DATE > f.expirationDate and f.deleted=false
                  order by f.year, f.month";

		return $this->executeQuery($query,array($studentID))->fetchAll();
	}

	public function listFees()
	{
		$query = "select * from fee where deleted=false";
		$stmnt = $this->executeQuery($query,array());
		return $stmnt ->fetchAll();
	}


}