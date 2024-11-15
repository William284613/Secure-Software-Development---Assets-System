<?php

$name=$_POST['filename'];
//Ekta pdf directory thakte hobe. pdf folder htdocs er vitore

$pathname="C:/xampp/htdocs/InventorySystem_PHP/pdf".$name;




$uploadOk = 1;
$imageFileType = strtolower(pathinfo($pathname,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($pathname)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

  


 $sql="insert into pdf_table (pdf_name,pdf_link)values('$name','$pathname');";



 $result=$conn->query($sql);

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
}

else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $pathname)) {
        if($result){

        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

        }



    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>