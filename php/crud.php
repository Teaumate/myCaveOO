<?php
if(isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
    if(isset($_POST['create']) || isset($_POST['create_xs'])){                // <-------------------------- CREATE
        if (!empty($_FILES["picture"]["name"])) {               // gestion de l'image si download
            $pictureF = $_FILES["picture"];

            if ($pictureF["error"] !== UPLOAD_ERR_OK) {
                echo "<p>An error occurred.</p>";
                exit;
            }

            // verify the file type
            $fileType = exif_imagetype($_FILES["picture"]["tmp_name"]);
            $allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
            if (!in_array($fileType, $allowed)) {
                echo "<p>File type is not permitted.</p>";
                exit;
            }

            // ensure a safe filename
            $namePic = preg_replace("/[^A-Z0-9._-]/i", "_", $pictureF["name"]);

            // don't overwrite an existing file
            $i = 0;
            $parts = pathinfo($namePic);
            while (file_exists(UPLOAD_DIR . $namePic)) {
                $i++;
                $namePic = $parts["filename"] . "-" . $i . "." . $parts["extension"];
            }

            // preserve file from temporary directory
            $success = move_uploaded_file($pictureF["tmp_name"],
                UPLOAD_DIR . $namePic);
            if (!$success) { 
                echo "<p>Unable to save file.</p>";
                exit;
            }

            // set proper permissions on the new file
            chmod(UPLOAD_DIR . $namePic, 0644);
        }else{
            $namePic = "generic.jpg";
        }
        $bottle_create = $_POST;
        $bottle_create['picture'] = $namePic;
        $bouteille = new Bottle($bottle_create);
        $manager->add($bouteille);
        $cache = 'off';
        $bottleIdCreated = $bouteille->id();
        if(isset($_POST['create_xs'])) {$directionCreated = 'center';}
        unset($_POST);
    }                                   // <------------------------------------------------------- DELETE
    if(isset($_POST['delete'])){ 
        $bouteille = new Bottle(['id'=>$_POST['Del_id']]);
        $manager->delete($bouteille);
        unset($_POST);
        $cache = 'off';
    }
    if(isset($_POST['update']) || isset($_POST['update_xs'])){  // <------------------------------------- UPDATE
        if (!empty($_FILES["picture-file"]["name"])) {
            $pictureF = $_FILES["picture-file"];

            if ($pictureF["error"] !== UPLOAD_ERR_OK) {
                echo "<p>An error occurred.</p>";
                exit;
            }

            // verify the file type
            $fileType = exif_imagetype($_FILES["picture-file"]["tmp_name"]);
            $allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
            if (!in_array($fileType, $allowed)) {
                echo "<p>File type is not permitted.</p>";
                exit;
            }

            // ensure a safe filename
            $namePic = preg_replace("/[^A-Z0-9._-]/i", "_", $pictureF["name"]);

            // don't overwrite an existing file
            // preserve file from temporary directory
            if(!file_exists(UPLOAD_DIR . $namePic)){
                $success = move_uploaded_file($pictureF["tmp_name"],
                    UPLOAD_DIR . $namePic);
                if (!$success) { 
                    echo "<p>Unable to save file.</p>";
                    exit;
                }
            }

            // set proper permissions on the new file
            chmod(UPLOAD_DIR . $namePic, 0644);
        }

        $picture=(isset($namePic))?$namePic:$_POST['picture'];
        $bottle_update = $_POST;
        $bottle_update['picture'] = $picture;
        $bouteille = new Bottle($bottle_update);
        $manager->update($bouteille);
        $bottleIdCreated = $bouteille->id();
        if(isset($_POST['update_xs'])) {$directionCreated = 'center'; $bottleIdCreated = $bouteille->id();}
        unset($_POST);
        $cache = 'off';
    }
}
phpFastCache::$storage = "auto";
$ListNames = phpFastCache::get("products_page");   // mise en cache 
if(is_null($ListNames) || isset($cache)) {
    $ListNames = $manager->getList();// <------------------------------------------------------- READ
    phpFastCache::set("products_page",$ListNames,0);
    unset($cache);
}