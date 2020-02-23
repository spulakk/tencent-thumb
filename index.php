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
</head>

<body>
    <div class="container">
        <h1 class="text-center my-5">Tencent Thumbnail Maker</h1>

        <div class="text-center">
            <p>Click the field and choose an image, then click "Submit" to get a 220x150 version of it which can be used as a thumbnail in Tencent competitions.</p>

            <p>In case of any issues, please contact <span class="text-primary">Pure Electricity#1796</span> on Discord.</p>
        </div>

        <form method="post" action="" enctype="multipart/form-data" id="image-form" class="my-5">
            <div class="form-group p-4" style="background-color: #f2f2f2;">
                <input type="file" name="image" id="image">
            </div>

            <input type="submit" value="Submit" class="btn btn-block btn-primary">
        </form>
    </div>

    <?php
        if(!empty($_FILES['image']['name']))
        {
            
        }
    ?>
</body>
</html>