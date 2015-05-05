    <?php
    include_once 'dbInterface.php';
    function getAllSubjects(){
        $sqlString = "SELECT * FROM subjects";
        $params = array();
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function doAddSubject(){
        $sqlString = "INSERT INTO subjects VALUES(null, :subjectname, :classlevel, :imgurl)";
        $params = array(
            'subjectname' => $_POST['subjectname'],
            'classlevel' => $_POST['classlevel'],
            'imgurl' => $_POST['imgurl']
        );
        query($sqlString, $params);
    }
    function getSubjectFromID($id){
        $sqlString = "SELECT * FROM subjects WHERE id=:id";
        $params = array(
            'id' => $id
        );

        return query($sqlString, $params, DBI::FETCH_ONE);
    }
    function getCategoryFromID($categoryid){
        $sqlString = "SELECT * FROM categories
                              WHERE id = :categoryid";
        $params = array('categoryid' => $categoryid);
        return query($sqlString, $params, DBI::FETCH_ONE);
    }
    function getAllLearningObjects () {
        $sqlString = "SELECT * FROM learningobjects";
        $params = array();
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function getCategories($subjectID){
        $sqlString = "SELECT * FROM categories JOIN subjectcategory on id = categoryid WHERE subjectid=:subjectid";
        $params = array(
            'subjectid' => $subjectID
        );
        $result = query($sqlString, $params, DBI::FETCH_ALL);
        for($i = 0; $i < count($result); $i++){
            $result = array_merge($result, getSubCategories($result[$i]['id']));
        }
        return $result;
    }

    function getAllCategories(){
        $sqlString = "SELECT *, categories.imgurl as catimg, categories.id as catid FROM categories
                              JOIN subjectcategory on categories.id = categoryid
                              JOIN subjects on subjectid = subjects.id";
        $params = array();
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function getAllTheCategories(){
        $sqlString = "SELECT * FROM categories JOIN subjectcategory on id = categoryid JOIN subjects on subjectid = subjects.id";
        $params = array();
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function doAddCategory($subjectID){
        $sqlString = "INSERT INTO categories VALUES(null, :categoryname, :imgurl)";
        $params = array(
            'categoryname' => $_POST['categoryname'],
            'imgurl' => $_POST['imgurl']
        );
        $catID = query($sqlString, $params, DBI::LAST_ID);
        $sqlString = "INSERT INTO subjectcategory VALUES(:subjectid, :categoryid)";
        $params = array(
            'subjectid' => $subjectID,
            'categoryid' => $catID
        );
        query($sqlString, $params);
    }
    function doAddLObject(){
        $sqlString = "INSERT INTO learningobjects VALUES(null, :title, :url, :imgurl)";
        $params = array(
            'title' => $_POST['lobjecttitle'],
            'url' => $_POST['url'],
            'imgurl' => $_POST['imgurl']
        );
        $lobjID = query($sqlString, $params, DBI::LAST_ID);
        $sqlString = "INSERT INTO learningobjectcategory VALUES(:lobjectid, :categoryid)";
        $params = array(
            'lobjectid' => $lobjID,
            'categoryid' => $_POST['categoryid']
        );
        query($sqlString, $params);
    }
    function getSchools () {
        $sqlString = "SELECT * FROM schools";
        $params = array();
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function addSchool ($name, $fylke, $kommune) {
        $sqlString = "INSERT INTO schools (name, fylke, kommune) VALUES (:name, :fylke, :kommune)";
        $params = array(
            'name' => $name,
            'fylke' => $fylke,
            'kommune' => $kommune
        );
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Skolen ble lagt til!";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }
    function addSubject ($fagnavn, $klasseTrinn, $fileToUpload) {
        $sqlString = "INSERT INTO subjects (subjectname, classlevel, imgurl) VALUES (:fagnavn, :klasseTrinn, :fileToUpload)";
        $params = array(
            'fagnavn' => $fagnavn,
            'klasseTrinn' => $klasseTrinn,
            'fileToUpload' => $fileToUpload
        );
        query($sqlString, $params);
    }
    function addCategory ($kategori, $bildeToUpload) {
        $sqlString = "INSERT INTO categories (category, imgurl) VALUES (:kategori, :bildeToUpload)";
        $params = array(
            'kategori' => $kategori,
            'bildeToUpload' => $bildeToUpload,
        );
        query($sqlString, $params);
    }
    function addLearningObject ($lOnavn) {
        $lObjectUrl = explode(".", uploadAndExtractZIP())[0];
        $sqlString = "INSERT INTO learningobjects  VALUES (null, :lOnavn, :lObject, :icon)";
        $params = array(
            'lOnavn' => $lOnavn,
            'icon' => '/public/lobjects/'.$lObjectUrl.'/icon.png',
            'lObject' => '/public/lobjects/'.$lObjectUrl.'/index.html'
        );
        if(query($sqlString, $params, DBI::ROW_COUNT) && $lObjectUrl)
            $_SESSION['notice'] = "Læringsobjektet ble lagt til!";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }
    //Search-operations
    function searchSchools ($searchString) {
        $sqlString = "SELECT * FROM schools WHERE name LIKE :searchString";
        $params = array(
            'searchString' => '%'.$searchString.'%'
        );
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function searchLearningObjects ($searchString) {
        $sqlString = "SELECT * FROM learningobjects WHERE title LIKE :searchString";
        $params = array(
            'searchString' => '%'.$searchString.'%'
        );
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function searchCategories ($searchString) {
        $sqlString = "SELECT *, categories.imgurl as catimg FROM categories
                              JOIN subjectcategory on categories.id = categoryid
                              JOIN subjects on subjectid = subjects.id WHERE category LIKE :searchString";
        $params = array(
            'searchString' => '%'.$searchString.'%'
        );
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    function searchSubjects ($searchString) {
        $sqlString = "SELECT * FROM subjects WHERE subjectname LIKE :searchString";
        $params = array(
            'searchString' => '%'.$searchString.'%'
        );
        return query($sqlString, $params, DBI::FETCH_ALL);
    }
    //Update-operations
    function updateSchool ($schoolid, $name, $fylke, $kommune) {
        $sqlString = "UPDATE schools
                              SET name = :name,
                              fylke = :fylke,
                              kommune = :kommune
                              WHERE id = :schoolid;";
        $params = array(
            'name' => $name,
            'fylke' => $fylke,
            'kommune' => $kommune,
            'schoolid' => $schoolid
        );
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Skole oppdatert!";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }
    function updateSubject ($subjectid, $subjectName, $classLevel, $imgUrl) {
        $sqlString = "UPDATE subjects
                              SET subjectname = :subjectname,
                              imgurl = :imgurl,
                              classlevel = :classlevel
                              WHERE id = :subjectid;";
        $params = array(
            'subjectname' => $subjectName,
            'classlevel' => $classLevel,
            'imgurl' => $imgUrl,
            'subjectid' => $subjectid
        );
        query($sqlString, $params);
    }
    //        function updateCategory ($category, $imgUrl) {
    //            /*
    //            $sqlString1 =
    //
    //            $sqlString = "UPDATE categories
    //                                        SET category = :category, imgurl = :imgUrl
    //                                        WHERE category = :category;";
    //            $params = array(
    //                'category' => $category,
    //                'imgUrl' => $imgUrl
    //            );
    //            query($sqlString, $params);
    //            */
    //        }
    //        function updateLearningObject ($title, $link, $imgUrl) {
    //            $sqlString = "UPDATE learningobjects
    //                          SET title = :title, link = :link, imgurl = :imgUrl
    //                          WHERE title = :title;";
    //            $params = array(
    //                'title' => $title,
    //                'link' => $link,
    //                'imgUrl' => $imgUrl
    //            );
    //            query($sqlString, $params);
    //
    //        }
    //Delete-operations
    function deleteSchool ($schoolId) {
        $sqlString = "DELETE FROM schools WHERE id = :schoolId";
        $params = array(
            'schoolId' => $schoolId
        );
        query($sqlString, $params);
    }

    function deleteSubject ($subjectID) {
        $sqlString = "DELETE FROM subjects WHERE id = :subjectId";
        $params = array(
            'subjectId' => $subjectID
        );
        query($sqlString, $params);
    }

    function deleteCategory ($categoryId) {
        $sqlString = "DELETE FROM categories WHERE id = :categoryId";
        $params = array(
            'categoryId' => $categoryId
        );
        query($sqlString, $params);
    }

    function deleteLearningObject ($learningObjectId) {
        $sqlString = "DELETE FROM learningobjects WHERE id = :learningObjectId";
        $params = array(
            'learningObjectId' => $learningObjectId
        );
        query($sqlString, $params);
    }

    function deletelObjectRelation($catID, $lObjectID){
        $sqlStrring = "DELETE FROM learningobjectcategory WHERE learningobjectid = :lobjectid AND categoryid = :catid";
        $params = array(
            'lobjectid' => $lObjectID,
            'catid' => $catID
        );
        query($sqlStrring, $params);
    }

    function deleteCategoryRelation($subjectID, $categoryID){
        $sqlString = "DELETE FROM subjectcategory WHERE categoryid = :categoryid AND subjectid = :subjectid";
        $params = array(
            'categoryid' => $categoryID,
            'subjectid' => $subjectID
        );
        query($sqlString, $params);
    }

    function deleteParentCategoryRelation($categoryid){
        $sqlString = "UPDATE categories SET parentid = 0 WHERE id = :categoryid";
        $params = array('categoryid' => $categoryid);
        query($sqlString, $params);
    }

    function deleteUser($username){
        $sqlString = "DELETE FROM users WHERE username = :username";
        $params = array('username' => $username);
        query($sqlString, $params);
    }

    //Edit-operation
    function getlObjectRelation($lObjectID){
        $sqlString = "SELECT *, categories.id as catid FROM learningobjectcategory
                              JOIN categories ON categoryid = categories.id
                              LEFT JOIN subjectcategory ON subjectcategory.categoryid = categories.id
                              LEFT JOIN subjects ON subjects.id = subjectid
                              WHERE learningobjectid = :lobjectid";
        $params = array('lobjectid' => $lObjectID);
        return query($sqlString, $params, DBI::FETCH_ALL);
    }

    function addlObjectRelation($lObjectID, $categoryID){
        $sqlString = "INSERT INTO learningobjectcategory VALUES(:lobjectid, :categoryid)";
        $params = array(
            'lobjectid' => $lObjectID,
            'categoryid' => $categoryID
        );
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Læringsobjektrelasjonen ble lagt til!";
        else
            $_SESSION['error'] = "Relasjonen finnes allerede";
    }

    function updateLObject($lObjectID, $title){
        if($_FILES["zip_file"]["name"]){
            $lObjectUrl = explode(".", uploadAndExtractZIP())[0];

            $sqlString = "UPDATE learningobjects SET title = :title, imgurl = :icon, link = :link WHERE id = :lobjectid";
            $params = array(
                'title' => $title,
                'icon' => '/public/lobjects/'.$lObjectUrl.'/icon.png',
                'link' => '/public/lobjects/'.$lObjectUrl.'/index.html',
                'lobjectid' => $lObjectID
            );
        }
        else {
            $sqlString = "UPDATE learningobjects SET title = :title WHERE id = :lobjectid";
            $params = array('title' => $title, 'lobjectid' => $lObjectID);
        }

        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Læringsobjektet ble oppdatert!";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }

    function getCategoryRelations($categoryID){
        $sqlString = "SELECT * FROM subjectcategory
                              JOIN subjects ON id = subjectid
                              WHERE categoryid = :categoryid";
        $params = array('categoryid' => $categoryID);
        return query($sqlString, $params, DBI::FETCH_ALL);
    }

    function updateCategory($categoryID, $title, $icon){
        $sqlString = "UPDATE categories SET category = :title, imgurl = :icon WHERE id = :categoryid";
        $params = array(
            'title' => $title,
            'icon' => $icon,
            'categoryid' => $categoryID
        );
        query($sqlString, $params);
    }

    function addCategoryRelation($categoryID, $subjectID, $parentCategoryID){
        if(isset($parentCategoryID)){
            $sqlString = "UPDATE categories SET parentid = :parentid WHERE id = :categoryid";
            $params = array('parentid' => $parentCategoryID, 'categoryid' => $categoryID);
        }
        else{
            $sqlString = "INSERT INTO subjectcategory VALUES(:subjectid, :categoryid)";
            $params = array(
                'categoryid' => $categoryID,
                'subjectid' => $subjectID
            );
        }
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Kategorirelasjon ble lagt til!";
        else
            $_SESSION['error'] = "Relasjonen finnes fra før";
    }

    function getSchoolUsers($schoolID){
        $sqlString = "SELECT * FROM users WHERE role = 'school' AND schoolid = :schoolid";
        $params = array('schoolid' => $schoolID);
        return query($sqlString, $params, DBI::FETCH_ALL);
    }

    function addSchoolUser($schoolid, $username,$password, $email){
        $sqlString = "INSERT INTO users VALUES (:username, :password, null, null, :email, 'school', null, :schoolid)
                      ON DUPLICATE KEY UPDATE username = username";
        $params = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'schoolid' => $schoolid
        );
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Skolebruker opprettet!";
        else
            $_SESSION['error'] = "Brukernavnet er opptatt";
    }

    function newYear () {
        $sqlString = "SELECT * FROM :classes";
        $params = array('classes' => "classes");
        $classes = query($sqlString, $params, DBI::FETCH_ALL);
        foreach ($classes as $class) {
            if (isset($class['classlevel']) && ($class['classlevel'] < 7) && ($class['classlevel'] > 0)) {
                $class['classlevel'] += 1;
                updatePupilClassLevel($class);
            } elseif ($class == 7) {
                deletePupil($class['username']);
            }
        }
    }

    function deletePupil ($username) {
        $sqlString = "DELETE FROM users
                                          WHERE username = :username";
        $params = array('username' => $username);
        query($sqlString, $params);
    }

    function updatePupilClassLevel ($pupil) {
        $sqlString = "UPDATE users SET classId = :classId";
        $params = array('classes' => $pupil['classid']);
        query($sqlString, $params);
    }

    function getAdmin ($username) {
        $sqlString = "SELECT * FROM users WHERE username = :username";
        $params = array('username' => $username);
        return query($sqlString, $params, DBI::FETCH_ONE);
    }

    function picUpload($root){
        if(!empty($_FILES["pic"]["name"])){
            echo 'Filen er ikke tom';
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp = explode(".", $_FILES["pic"]["name"]);
            $extension = end($temp);
            echo $_FILES["pic"]["type"];
            if ((($_FILES["pic"]["type"] == "image/gif")
                    || ($_FILES["pic"]["type"] == "image/jpeg")
                    || ($_FILES["pic"]["type"] == "image/jpg")
                    || ($_FILES["pic"]["type"] == "image/png"))
                && ($_FILES["pic"]["size"] < 20000000)
                && in_array($extension, $allowedExts))
            {
                if ($_FILES["pic"]["error"] > 0)
                {
//                    echo 'Det er en error!';
                }
                else
                {

                    if (file_exists($root . "/public/img/" . $_FILES["pic"]["name"]))
                    {
//                        echo 'Filen eksisterer!';
                    }
                    else
                    {
//                        echo 'Filen eksisterer ikke';
                        move_uploaded_file($_FILES["pic"]["tmp_name"],
                            $root . "/public/img/" . $_FILES["pic"]["name"]);
                        return $_FILES["pic"]["name"];

                    }
                }
            }
            else
            {
//                echo 'Dette med filtype osv slår ut.';
            }
        }
        else{
//            echo 'tom fil';
            return false;
        }
    }

    function addNewSubject ($subjectName, $classLevel, $fileName) {
        $imgUrl = generateImgUrl($fileName);

        $sqlString = "INSERT INTO subjects (subjectname, classlevel, imgurl)
                      VALUES (:subjectName, :classLevel, :imgUrl)";
        $params = array(
            'subjectName' => $subjectName,
            'classLevel' => $classLevel,
            'imgUrl' => $imgUrl
        );
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Faget ble lagt til!";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }

    function addNewCategory ($categoryName, $fileName) {
        $imgUrl = generateImgUrl($fileName);

        $sqlString = "INSERT INTO categories (category, imgurl)
                      VALUES (:categoryName, :imgUrl)";
        $params = array(
            'categoryName' => $categoryName,
            'imgUrl' => $imgUrl
        );
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Kategorien ble lagt til!";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }

    function editSubject ($subjectId, $subjectName, $classLevel, $fileName) {
        if (isset($fileName)) {
            $imgUrl = generateImgUrl($fileName);

            $sqlString = "UPDATE subjects
                      SET subjectname = :subjectName,
                      classlevel = :classLevel,
                      imgurl = :imgUrl
                      WHERE id = :subjectId";
            $params = array(
                'subjectName' => $subjectName,
                'classLevel' => $classLevel,
                'imgUrl' => $imgUrl,
                'subjectId' => $subjectId
            );
        } else {
            $sqlString = "UPDATE subjects
                      SET subjectname = :subjectName,
                      classlevel = :classLevel
                      WHERE id = :subjectId";
            $params = array(
                'subjectName' => $subjectName,
                'classLevel' => $classLevel,
                'subjectId' => $subjectId
            );
        }
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Fag oppdatert!";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }

    function editCategory ($categoryId, $categoryName, $fileName) {
        if (isset($fileName)) {
            $imgUrl = generateImgUrl($fileName);

            $sqlString = "UPDATE categories
                      SET category = :categoryName,
                      imgurl = :imgUrl
                      WHERE id = :categoryId";
            $params = array(
                'categoryName' => $categoryName,
                'imgUrl' => $imgUrl,
                'categoryId' => $categoryId
            );
        } else {
            $sqlString = "UPDATE categories
                      SET category = :categoryName,
                      WHERE id = :categoryId";
            $params = array(
                'categoryName' => $categoryName,
                'categoryId' => $categoryId
            );
        }
        if(query($sqlString, $params, DBI::ROW_COUNT))
            $_SESSION['notice'] = "Kategorien ble oppdatert";
        else
            $_SESSION['error'] = "En feil har oppstått";
    }

    function generateImgUrl ($fileName) {
        return '/public/img/' . $fileName;
    }

//http://bavotasan.com/2010/how-to-upload-zip-file-using-php/

    function uploadAndExtractZIP(){
        if($_FILES["zip_file"]["name"]) {
            $filename = $_FILES["zip_file"]["name"];
            $source = $_FILES["zip_file"]["tmp_name"];
            $type = $_FILES["zip_file"]["type"];

            $name = explode(".", $filename);
            $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
            foreach($accepted_types as $mime_type) {
                if($mime_type == $type) {
                    $okay = true;
                    break;
                }
            }

            $continue = strtolower($name[1]) == 'zip' ? true : false;
            if(!$continue) {
                return false;
            }

            $target_path = "/var/www/public/lobjects/".$filename;
            delete(substr($target_path, 0, -4));
            if(move_uploaded_file($source, $target_path)) {
                $zip = new ZipArchive();
                $x = $zip->open($target_path);
                if ($x === true) {
                    $zip->extractTo("/var/www/public/lobjects/");
                    $zip->close();

                    unlink($target_path);
                }
            } else {
                return false;
            }
            return $_FILES["zip_file"]["name"];
        }
        else return false;
    }

//http://stackoverflow.com/questions/1334398/how-to-delete-a-folder-with-contents-using-php
    function delete($path){
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                delete(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        }

        else if (is_file($path) === true) {
            return unlink($path);
        }
        return false;
    }