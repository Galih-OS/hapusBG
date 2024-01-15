<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>gos remover</title>
	
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="shortcut icon" href="images/bookmark.png">
	</head>

	<body>
		<nav class="navbar bg-body-tertiary navbar-expand-lg">
			<div class="container">
				<a class="navbar-brand" href="index.php">
					<img src="images/logo-dark.png" alt="Bootstrap" width="80" height="25"> |
				</a>
				
				<div class="container-fluid">
					<a type="button" class="btn btn-outline-danger" href="index.php">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eraser" viewBox="0 0 16 16">
						<path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828zm2.121.707a1 1 0 0 0-1.414 0L4.16 7.547l5.293 5.293 4.633-4.633a1 1 0 0 0 0-1.414zM8.746 13.547 3.453 8.254 1.914 9.793a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293z"></path>
						</svg> Remove BG
					</a>					
					
					<a type="button" class="btn btn-outline-success" href="editbg.php">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
						<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
						<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
						</svg> Edit BG Colour
					</a>
				</div>
						
		</nav> <!--Header Logo-->
		<br/>
		<div class="container"><b><u class="text-danger">Hapus Latar Belakang Gambar</u>, mendukung Jenis File : jpg, png, jpeg, tif, tiff, jfif, arw | File Maksimal 5 Mb
			</b>
		</div>

		<!-- container -->
		<div class="container">
		
				<!-- row -->
				<div class="row">
					<div class="col-md-6 col-xs-6">
						<br>
						<form method="post" name="image_upload_form" id="image_upload_form" enctype="multipart/form-data">
							<input class="btn btn-outline-danger" style="font-size:11.5px" type="file" name="files[]" id="image_upload">
							<input class="btn btn-success" style="font-size:11.5px" type="submit"id="uploadFile" name="submit" value="Upload">
						</form>
					</div>
				</div> <!-- /upload foto -->
		
			<?php

			require_once 'vendor/autoload.php';
			$upload_dir = 'upload/';
			$api_key = 'sPegLYjfihKMMzDUihigkqBB';



			if(isset($_POST['submit'])) {


			// Configure upload directory and allowed file types
			$upload_dir = 'upload'.DIRECTORY_SEPARATOR;
			$allowed_types = array('jpg', 'png', 'jpeg','tif','tiff','jfif', 'arw');


			// Define maxsize for files i.e 2MB
			$maxsize = 5 * 1024 * 1024;

			$totalsize = 0;
			$i=0;


			// Checks if user sent an empty form
			if(!empty(array_filter($_FILES['files']['name']))) {


			$index=1;
			// Loop through each file in files[] array
			foreach ($_FILES['files']['tmp_name'] as $key => $value) {

			$file_tmpname = $_FILES['files']['tmp_name'][$key];
			$file_name = $_FILES['files']['name'][$key];
			$file_size = $_FILES['files']['size'][$key];
			$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
			// $est_time = $total/ 2097152;

			$totalsize = $totalsize + $file_size;
			$i++;

			$filepath = $upload_dir.$file_name;

			// Check file type is allowed or not
			if(in_array(strtolower($file_ext), $allowed_types)) {


			// If file with name already exist then append time in
			// front of name of the file to avoid overwriting of file
			if(file_exists($filepath)) {
			$filepath = $upload_dir.time().$file_name;

			move_uploaded_file($file_tmpname, $filepath);

			}
			else {

			move_uploaded_file($file_tmpname, $filepath);


			}

			// Start IBR

			$path_info = pathinfo($filepath);
			// $path_size = pathinfo($file_size);
			$client = new GuzzleHttp\Client();

			$res = $client->post('https://api.remove.bg/v1.0/removebg', [
			'multipart' => [
			[
			'name'     => 'image_file',
			'contents' => fopen( $filepath, 'r'),
			],
			[
			'name'     => 'size',
			'contents' => 'auto',
			// 'contents' => fopen( $file_size,'r'),
			],
			[
			'name'     => 'bg_color',
			'contents' => 'fff',
			],
			],
			'headers' => [
			'X-Api-Key' => $api_key,
			],
			]);

			echo "<script type=\"text/javascript\">

			var e = document.getElementById('percent'); 
			e.innerHTML ='" . $index . "';

			</script>";

			$index=$index+10;
			$fp = fopen("{$upload_dir}{$path_info['filename']}-no-bg.png", "wb");

			fwrite($fp, $res->getBody());
			fclose($fp);

			echo 	"<br/><hr/><div class='row'>
						<div class='col-md-4 col-xs-6'>
							<a href='{$upload_dir}{$path_info['filename']}-no-bg.png' class='btn btn-success' download>Download</a>
							<img src='{$upload_dir}{$path_info['filename']}-no-bg.png' style='padding-top:5%'>
						</div>
					</div>";


			// End IBR

			}
			else {

			// If file extension not valid
			echo "Error uploading {$file_name} ";
			echo "({$file_ext} file type is not allowed)<br/>";
			}
			}

			$size = $totalsize/ 1000000;
			$est_time = (5 * $i) + ($size/ 2.3);

			$est_time_min= "00";$est_time_hr="00";


			if($est_time > 60)
			$est_time_min= round($est_time/60,2);

			$est_time_sec= round($est_time - (60 * $est_time_min));


			if($est_time_min > 60)
			$est_time_hr =round ($est_time_min / 60,2);

			}
			else {

			// If no files selected
			echo "No files selected.";
			}
			}


			?>
		
		<br/>
		<hr/>
		<footer class="bg-white text-center">
		
			<!-- Grid container -->
			<div class="container col-md-12">
			
				<!-- Section: Social media -->
				<section class="col-md-12"> <h4 text-center>Terhubung :</h>
				<a href="https://github.com/Galih-OS" target="_blank"><i class="bi bi-github" style="font-size:25px"></i></a> | 
				<a href="https://t.me/galihos" target="_blank"><i class="fa fa-telegram" style="font-size:25px"></i></a> | 
				<a href="https://www.linkedin.com/in/galih-okta-siwi-8a356910a/" target="_blank"><i class="fa fa-linkedin" style="font-size:25px"></i></a> | 
				<a href="https://www.instagram.com/galihoktas/" target="_blank"><i class="fa fa-instagram" style="font-size:25px"></i></a> | 
				<a href="https://www.youtube.com/channel/UCy5QyqbTWXQJEtA72iHwZ2Q?sub_confirmation=1" target="_blank"><i class="fa fa-youtube" style="font-size:25px"></i></a>


				</section>
				<!-- Section: Social media -->
			</div>
			<!-- Grid container -->
		</footer>
		
		<hr/>
		</div>
		<!-- container -->

		<!--Bundle-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		
		<!--Separate-->
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		
		<script>
			$('#image_upload').on('change', function() {
			var fileCount = document.getElementById('image_upload').files.length;
			var files = $('#image_upload')[0].files;
			var totalSize = 0;

			for (var i = 0; i < files.length; i++) {
			// calculate total size of all files        
			totalSize += files[i].size;
			}
			//   alert(totalSize)

			//----------------

			var sizeInMB = totalSize/ 1000000;
			var est_time = (5 * fileCount) + (sizeInMB/ 2.3);

			var est_time_min = "00",est_time_hr="00";


			if(est_time > 60)
			est_time_min= ($est_time/60);


			var est_time_sec= (est_time - (60 * est_time_min));
			est_time_sec_cut= est_time_sec.toFixed(2);


			if(est_time_min > 60)
			est_time_hr = (est_time_min / 60);


			//-------------
			$("#estimated_timeinsec").text(est_time_sec_cut);
			$("#estimated_timeinmin").text(est_time_min);
			$("#estimated_timeinhr").text(est_time_hr);

			});

			/////
		</script>
		
	</body>
</html>
<!--MR. OS--2023>