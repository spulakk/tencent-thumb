<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tencent Thumbnail Maker</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/ico" href="/favicon.ico">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        .modal {
            display: block; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 200px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        /* The Close Button */
        #close {
            color: #aaaaaa;
            font-size: 32px;
            font-weight: bold;
        }

        #close:hover,
        #close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-5">Tencent Image Maker</h1>

        <div class="text-center">
            <p>Click the field and choose an image, then click "Submit" to resize it for use in Tencent competitions.</p>
            <p>Supported image formats: JPG, JPEG, PNG</p>
            <p>In case of any issues, please contact <span class="text-primary">Pure Electricity#1796</span> on Discord.</p>
        </div>

        <form method="post" action="" enctype="multipart/form-data" id="image-form" class="my-5">
            <div class="form-group p-4 mb-4" style="background-color: #f2f2f2;">
                <input type="file" name="image" id="image">
            </div>

            <div class="mb-4">
                <h6><label for="name" class="m-0">Output name (letters and numbers only):</label></h6>
                <input type="text" id="name" class="form-control" name="name" maxlength="50" pattern="[a-zA-Z0-9]+">
            </div>

            <div class="mb-4">
                <h6>Output size:</h6>
                <input type="radio" id="full" name="size" value="full" checked="checked">
                <label for="full" class="m-0"><b>1920x1080</b></label>
                <br>
                <input type="radio" id="thumb" name="size" value="thumb">
                <label for="thumb" class="m-0"><b>220x150</b></label>
            </div>

            <div class="mb-4">
                <h6>Resize type:</h6>
                <input type="radio" id="crop" name="type" value="crop" checked="checked">
                <label for="crop" class="m-0"><b>Crop</b> (recommended, cuts off the edges if aspect ratio is not correct)</label>
                <br>
                <input type="radio" id="fit" name="type" value="fit">
                <label for="fit" class="m-0"><b>Fit</b> (stretches the image vertically or horizontally if aspect ratio is not correct)</label>
            </div>

            <input type="submit" value="Submit" class="btn btn-block btn-primary">
        </form>
    </div>

    <?php
        require __DIR__ . '/vendor/autoload.php';
var_dump($_SERVER['REMOTE_ADDR']);die;
        $error = null;

        if(!empty($_FILES['image']['name']))
        {
            if($_POST['size'] == 'full')
            {
                $targetWidth = 1920;
                $targetHeight = 1080;
                $ratio = 1.78;
                $name = 'img';
            }
            else
            {
                $targetWidth = 220;
                $targetHeight = 150;
                $ratio = 1.47;
                $name = 'thumb';
            }

            if(!empty($_POST['name']))
            {
                $name = $_POST['name'];
            }

            $img = $_FILES['image'];

            $validExtensionArray = ['jpeg', 'jpg', 'png'];

            $fileNameArray = explode('.', $img['name']);
            $fileExtension = end($fileNameArray);
            $fileName = __DIR__ . DIRECTORY_SEPARATOR . $name . '.' . $fileExtension;

            if(!in_array($fileExtension, $validExtensionArray))
            {
                $error = 'Only jpg, jpeg and png formats are supported.';
            }
            else
            {
                move_uploaded_file($img['tmp_name'], $fileName);
            }

            if(!isset($error))
            {
                try
                {
                    $imagick = new \Imagick($fileName);
                }
                catch(ImagickException $e)
                {
                    $error = $e->getMessage();
                    unlink($fileName);
                }
            }

            if(!isset($error))
            {
                if($_POST['type'] == 'crop')
                {
                    $width = $imagick->getImageGeometry()['width'];
                    $height = $imagick->getImageGeometry()['height'];

                    if($width / $height < $ratio)
                    {
                        $imagick->resizeImage($targetWidth, null, null, 1);
                    }
                    else
                    {
                        $imagick->resizeImage(null, $targetHeight, null, 1);
                    }

                    $width = $imagick->getImageGeometry()['width'];
                    $height = $imagick->getImageGeometry()['height'];

                    $imagick->cropImage($targetWidth, $targetHeight, ($width - $targetWidth) / 2, ($height - $targetHeight) / 2);
                }
                elseif($_POST['type'] == 'fit')
                {
                    $imagick->resizeImage($targetWidth, $targetHeight, null, 1);
                }

                $imagick->writeImage();

                header("Content-Type: application/octet-stream");
                header("Content-Transfer-Encoding: Binary");
                header("Content-disposition: attachment; filename=\"" . $name . "." . $fileExtension . "\"");

                ob_clean();
                flush();

                if(in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1']))
                {
                    readfile('http://localhost/tencent_thumb/img.' . $fileExtension);
                }
                else
                {
                    readfile('https://tencent-thumb.herokuapp.com/' . $name . '.' . $fileExtension);
                }

                unlink($fileName);

                exit;
            }
            else
            {
                echo '
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <div class="text-right">
                                <span id="close">&times;</span>
                            </div>
                            <h3 class="text-danger">Error</h3>
                            <div>' . $error .'</div>
                        </div>
                    </div>
                ';

                unset($error);
            }
        }
    ?>

    <script>
        var modal = document.getElementById("myModal");
        var span = document.getElementById("close");

        if(span)
        {
            span.onclick = function() {
                modal.style.display = "none";
            };
        }

        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
    </script>
</body>
</html>