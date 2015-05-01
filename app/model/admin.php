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
           return  query($sqlString, $params, DBI::FETCH_ONE);

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
            return query($sqlString, $params, DBI::FETCH_ALL);

        }

        function getAllCategories(){
            $sqlString = "SELECT *, categories.imgurl as catimg, categories.id as catid FROM categories
                          LEFT JOIN subjectcategory on categories.id = categoryid
                          LEFT JOIN subjects on subjectid = subjects.id";
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
            query($sqlString, $params);

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

        function addLearningObject ($lOnavn, $lOIconToUpload, $lOToUpload) {
            $sqlString = "INSERT INTO learningobjects (title, link, imgurl) VALUES (:lOnavn, :lOIconToUpload, :lOToUpload)";
            $params = array(
                'lOnavn' => $lOnavn,
                'lOIconToUpload' => $lOIconToUpload,
                'lOToUpload' => $lOToUpload
            );
            query($sqlString, $params);
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
        function updateSchool ($name, $fylke, $kommune) {
            $sqlString = "UPDATE schools
                          SET name = :name,
                          fylke = :fylke,
                          kommune = :kommune
                          WHERE name = :name;";
            $params = array(
                'name' => $name,
                'fylke' => $fylke,
                'kommune' => $kommune
            );
            query($sqlString, $params);

        }

        function updateSubject ($subjectName, $classLevel, $imgUrl) {
            $sqlString = "UPDATE subjects
                                        SET subjectname = :subjectName,
                                         imgurl = :imgUrl
                                        WHERE subjectname = :subjectName;";
            $params = array(
                'subjectName' => $subjectName,
                'classLevel' => $classLevel,
                'imgUrl' => $imgUrl
            );
            query($sqlString, $params);

        }

        function updateCategory ($category, $imgUrl) {
            /*
            $sqlString1 =

            $sqlString = "UPDATE categories
                                        SET category = :category, imgurl = :imgUrl
                                        WHERE category = :category;";
            $params = array(
                'category' => $category,
                'imgUrl' => $imgUrl
            );
            query($sqlString, $params);
            */
        }

        function updateLearningObject ($title, $link, $imgUrl) {
            $sqlString = "UPDATE learningobjects
                          SET title = :title, link = :link, imgurl = :imgUrl
                          WHERE title = :title;";
            $params = array(
                'title' => $title,
                'link' => $link,
                'imgUrl' => $imgUrl
            );
            query($sqlString, $params);

        }

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

        function deleteRelation($catID, $lObjectID){
            $sqlStrring = "DELETE FROM learningobjectcategory WHERE learningobjectid = :lobjectid AND categoryid = :catid";
            $params = array(
                'lobjectid' => $lObjectID,
                'catid' => $catID
            );
            query($sqlStrring, $params);
        }

        //Edit-operation
        function getCategoryRelation($lObjectID){
            $sqlString = "SELECT *, categories.id as catid FROM learningobjectcategory
                          JOIN categories ON categoryid = categories.id
                          JOIN subjectcategory ON subjectcategory.categoryid = categories.id
                          JOIN subjects ON subjects.id = subjectid
                          WHERE learningobjectid = :lobjectid";
            $params = array('lobjectid' => $lObjectID);
            return query($sqlString, $params, DBI::FETCH_ALL);
        }

        function addCategoryRelation($lObjectID, $categoryID){
            $sqlString = "INSERT INTO learningobjectcategory VALUES(:lobjectid, :categoryid)";
            $params = array(
              'lobjectid' => $lObjectID,
                'categoryid' => $categoryID
            );
            query($sqlString, $params);
        }

        function updateLObject($lObjectID, $title, $icon, $link){
            $sqlString = "UPDATE learningobjects SET title = :title, imgurl = :icon, link = :link WHERE id = :lobjectid";
            $params = array(
                'title' => $title,
                'icon' => $icon,
                'link' => $link,
                'lobjectid' => $lObjectID
            );
            query($sqlString, $params);
        }


