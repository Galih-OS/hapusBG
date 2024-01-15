<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>gos remover</title>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
				<!-- Your existing HTML code -->

			<form action="edit-background.php" method="post" enctype="multipart/form-data">
				<input type="file" name="files[]" id="files" multiple>
				<input type="color" name="bg_color" id="bg_color" value="#fff">
				<button type="submit" name="submit">Edit Background</button>
			</form>


			<?php
				require_once 'vendor/autoload.php';

				$api_key = 't1LghFrFpi9ZW4ahNWkxzRrd';

				if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
					// Configure upload directory and allowed file types
					$upload_dir = 'upload' . DIRECTORY_SEPARATOR;
					$allowed_types = array('jpg', 'png', 'jpeg', 'tif', 'tiff', 'jfif');

					// Define maxsize for files i.e 2MB
					$maxsize = 2 * 1024 * 1024;

					$totalsize = 0;
					$i = 0;

					// Checks if user sent an empty form
					if (!empty(array_filter($_FILES['files']['name']))) {
						$index = 1;
						// Loop through each file in files[] array
						foreach ($_FILES['files']['tmp_name'] as $key => $value) {
							$file_tmpname = $_FILES['files']['tmp_name'][$key];
							$file_name = $_FILES['files']['name'][$key];
							$file_size = $_FILES['files']['size'][$key];
							$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
							$totalsize = $totalsize + $file_size;
							$i++;
							$filepath = $upload_dir . $file_name;

							// Check file type is allowed or not
							if (in_array(strtolower($file_ext), $allowed_types)) {
								// If file with name already exists, append time in front of the name to avoid overwriting
								if (file_exists($filepath)) {
									$filepath = $upload_dir . time() . $file_name;
									move_uploaded_file($file_tmpname, $filepath);
								} else {
									move_uploaded_file($file_tmpname, $filepath);
								}

								// Start IBR
								$path_info = pathinfo($filepath);
								$client = new GuzzleHttp\Client();
								$bg_color = $_POST['bg_color'];

								$res = $client->post('https://api.remove.bg/v1.0/removebg', [
									'multipart' => [
										[
											'name'     => 'image_file',
											'contents' => fopen($filepath, 'r'),
										],
										[
											'name'     => 'size',
											'contents' => 'auto',
										],
										[
											'name'     => 'bg_color',
											'contents' => $bg_color,
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

								$index = $index + 10;
								$output_filename = "{$path_info['filename']}-no-bg.png";
								$output_filepath = "{$upload_dir}{$output_filename}";

								$fp = fopen($output_filepath, "wb");
								fwrite($fp, $res->getBody());
								fclose($fp);

								echo "<div class='row'><div class='col-md-4 col-xs-6'> <img src='{$output_filepath}' style='padding-top:5%'> <br>{$output_filename} </div></div>";
								// End IBR
							} else {
								// If file extension not valid
								echo "Error uploading {$file_name} ";
								echo "({$file_ext} file type is not allowed)<br / >";
							}
						}

						$size = $totalsize / 1000000;
						$est_time = (5 * $i) + ($size / 2.3);

						$est_time_min = "00";
						$est_time_hr = "00";

						if ($est_time > 60)
							$est_time_min = round($est_time / 60, 2);

						$est_time_sec = round($est_time - (60 * $est_time_min));

						if ($est_time_min > 60)
							$est_time_hr = round($est_time_min / 60, 2);

						echo "Estimated Time : {$est_time_hr} : {$est_time_min} :{$est_time_sec}";
					} else {
						// If no files selected
						echo "No files selected.";
					}
				}
			?>



		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

	</body>

</html>
