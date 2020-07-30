<?php
	require_once('../database/DbBukuOperation.php');
	require_once('../utility/filetotext.php');

	if(isset($_GET['apicall']))
	{
		switch($_GET['apicall']) {
			case 'fetchall':
				fetchAllBook();
				break;

			case 'fetchbytitle':
				fetchBooksByTitle();
				break;

			case 'fetchbyauthor':
				fetchBooksByAuthor();
				break;

			case 'addbook':
				addNewBook();
				break;

			default:
				$response['success'] = false; 
				$response['message'] = 'Invalid API Call';

				echo json_encode($response);
				break;
		}
	}
	else 
	{
		$response['success'] = false; 
		$response['message'] = 'Invalid API Call';

		echo json_encode($response);
	}

	function fetchAllBook(){
			$db = new DbBukuOperation();

			$books = $db->getAllBuku();

			if(!empty($books)){
				for ($idx = 0; $idx < count($books); $idx++) { 
					$path = $books[$idx]['isi_konten'];
					$books[$idx]['isi_konten'] = handleFileRetrieve($path);
				}

				$response['success'] = true;
				$response['message'] = 'All Books Fetched Successfully';
				$response['data'] = $books;
			} 
			else {
				$response['success'] = true;
				$response['message'] = 'You Have No Books to Fetch';
			}

			echo json_encode($response);
		}

	function fetchBooksByTitle(){
		$db = new DbBukuOperation();

		$title = $_POST['judul_konten'];
		$books = $db->getBookByJudul($title);

		if(!empty($books)){
			for ($idx = 0; $idx < count($books); $idx++) { 
				$path = $books[$idx]['isi_konten'];
				$books[$idx]['isi_konten'] = handleFileRetrieve($path);
			}

			$response['success'] = true;
			$response['message'] = 'All Books Fetched Successfully';
			$response['data'] = $books;
		} 
		else {
			$response['success'] = true;
			$response['message'] = 'You Have No Books to Fetch';
		}

		echo json_encode($response);
	}

	function fetchBooksByAuthor(){
		$db = new DbBukuOperation();

		$author = $_POST['author'];
		$books = $db->getBookByAuthor($author);

		if(!empty($books)){
			for ($idx = 0; $idx < count($books); $idx++) { 
				$path = $books[$idx]['isi_konten'];
				$books[$idx]['isi_konten'] = handleFileRetrieve($path);
			}

			$response['success'] = true;
			$response['message'] = 'All Books Fetched Successfully';
			$response['data'] = $books;
		} 
		else {
			$response['success'] = true;
			$response['message'] = 'You Have No Books to Fetch';
		}

		echo json_encode($response);
	}

	function addNewBook(){
		$db = new DbBukuOperation();

		$judulKonten = $_POST['judul_konten'];
		$author = $_POST['author'];
		$email = $_POST['email'];
		$tanggalDibuat = $_POST['tanggal_dibuat'];
		$file = $_FILES['isi_konten'];
		$filepath = handleFileAppend($file);

		if($filepath !== "Failed Upload File") {
			
			if($db->setBuku($judulKonten, $email, $author, $tanggalDibuat, $filepath)){
				fetchAllBook();
			}
			else {
				$response['success'] = false;
				$response['message'] = 'Adding Book Failed';

				echo json_encode($response);
			}
		}
		else {
				$response['success'] = false;
				$response['message'] = 'Uploading Book Failed';

				echo json_encode($response);
		}
	}

	function handleFileAppend($file){
		$filename = pathinfo($file['name'], PATHINFO_FILENAME);
		$filetype = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

		$targetdir = APP_PATH . "\\resource\buku\\";
		$targetfile = $targetdir . $filename . ".txt";

		$doc_dir =  APP_PATH . "\\resource\_doc\\";
		$doc_file = $doc_dir . basename($file['name']);

		if (file_exists($targetfile) || file_exists($doc_file)) {
			$count = 1;

			do {
				$suffix = "-" . $count;
				$targetfile = $targetdir . $filename . $suffix . ".txt";
				$doc_file = $doc_dir . $filename . $suffix . "." . $filetype;

				$count++;
			} while (file_exists($targetfile) || file_exists($doc_file));
		}

		if ($filetype == "doc" ||  $filetype == "docx" || $filetype == "pdf") {
			move_uploaded_file($file["tmp_name"], $doc_file);

			$convertion = new Filetotext($doc_file);
			$text = $convertion->convertToText();

			if(!$text || $text === "File Not exists" || $text === "Invalid File Type") {
				return "Failed Upload File";
			} 
			else 
				file_put_contents($targetfile, $text);
		}
		else if ($filetype == "txt") {
			move_uploaded_file($file["tmp_name"], $targetfile);
		}

		if (file_exists($targetfile)) {
			return $targetfile;
		} else {
			return "Failed Upload File";
		}
	}

	function handleFileRetrieve($pathfile) {
		return file_get_contents($pathfile);
	}
?>