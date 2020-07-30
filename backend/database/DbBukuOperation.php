<?php
	
require_once "DbConnection.php";

class DbBukuOperation{
	private $con;

	function __construct(){
		$dbcon = new DbConnection();
		$this->con = $dbcon->connect();
	}

	function setBuku($judulKonten, $email, $author, $tanggalDibuat, $isiKonten){
		$query = $this->con->prepare(
			"INSERT INTO buku (judul_konten, email, author, tanggal_dibuat, isi_konten) VALUES (?, ?, ?, ?, ?)"
		);
		$query->bind_param(
			"sssss",
			$judulKonten,
			$email,
			$author,
			$tanggalDibuat,
			$isiKonten
		);

		if($query->execute())
			return true; 
		return false;
	}

	function getAllBuku(){
		$query = $this->con->query(
			"SELECT * FROM buku"
		);

		$books = $query->fetch_all(MYSQLI_ASSOC);

		return $books;
	}

	function getBookByID($id){ 
		$query = $this->con->prepare(
			"SELECT * FROM buku WHERE id_konten = ?"
		);
		$query->bind_param("i", $id);
		$query->execute();
		$query->bind_result($id_result, $judulKonten, $email, $author, $tanggalDibuat, $isiKonten);

		$books = array();
		
		while($query->fetch()){
			$book = array(
				'id_konten' => $id_result,
				'judul_konten' => $judulKonten,
				'email' => $email,
				'author' => $author,
				'tanggal_dibuat' => $tanggalDibuat,
				'isi_konten' => $isiKonten
			);

			array_push($books, $book);
		}

		return $books;
	}

	function getBookByJudul($judulKonten){ 
		$query = $this->con->prepare(
			"SELECT * FROM buku WHERE judul_konten LIKE '" . $judulKonten . "%'"
		);
		$query->bind_param("s", $judulKonten);
		$query->execute();
		$query->bind_result($id, $judulKonten_result, $email, $author, $tanggalDibuat, $isiKonten);

		$books = array();
		
		while($query->fetch()){
			$book = array(
				'id_konten' => $id,
				'judul_konten' => $judulKonten_result,
				'email' => $email,
				'author' => $author,
				'tanggal_dibuat' => $tanggalDibuat,
				'isi_konten' => $isiKonten
			);

			array_push($books, $book);
		}

		return $books;
	}

	function getBookByUsername($email){ 
		$query = $this->con->prepare(
			"SELECT * FROM buku WHERE email = ?"
		);

		$query->bind_param("s", $email);
		$query->execute();
		$query->bind_result($id, $judulKonten, $email_result, $author, $tanggalDibuat, $isiKonten);

		$books = array();
		
		while($query->fetch()){
			$book = array(
				'id_konten' => $id,
				'judul_konten' => $judulKonten,
				'email' => $email_result,
				'author' => $author,
				'tanggal_dibuat' => $tanggalDibuat,
				'isi_konten' => $isiKonten
			);

			array_push($books, $book);
		}

		return $books;
	}

	function getBookByAuthor($author){ 
		$query = $this->con->query(
			"SELECT * FROM buku WHERE author LIKE '" . $author . "%'"
		);

		$books = $query->fetch_all(MYSQLI_ASSOC);

		return $books;
	}

	function updateBuku($id, $judulKonten, $email, $author, $tanggalDibuat, $isiKonten){
		$query = $this->con->prepare(
			"UPDATE buku SET judul_konten = ?, email = ?, author = ?, tanggal_dibuat = ?, isi_konten = ? WHERE id_konten = ?"
		);
		$query->bind_param("sssssi", $judulKonten, $email, $author, $tanggalDibuat, $isiKonten, $id);
		if($query->execute())
			return true; 
		return false; 
	}

	function deleteBuku($id){
		$query = $this->con->prepare("DELETE FROM buku WHERE id_konten = ?");
		$query->bind_param("i", $id);
		if($query->execute())
			return true; 
		return false; 
	}
}
?>