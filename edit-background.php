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
