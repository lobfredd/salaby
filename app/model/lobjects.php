<?php

    function getUserCategories($subjectID){
        global $database;
        $sql = $database->prepare("SELECT categories.imgurl, categories.category, categories.id
                                   FROM categories
                                   JOIN subjectcategory ON subjectcategory.categoryid = categories.id
                                   JOIN subjects ON subjects.id = subjectcategory.subjectid
                                   WHERE subjects.id = :subjectid");
        $sql->execute(array(
            'subjectid' => $subjectID
        ));
        return $sql->fetchAll(PDO::FETCH_ASSOC);

    }

    function getAllLobjects($categoryID){
        global $database;
        $sql = $database->prepare("SELECT * FROM learningobjects
                                   JOIN learningobjectcategory ON learningobjectid = learningobjects.id
                                   WHERE categoryid = :categoryid");
        $sql->execute(array(
            'categoryid' => $categoryID
        ));
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getSubCategories($categoryID){
        global $database;
        $sql = $database->prepare("SELECT * FROM categories
                                   WHERE parentid = :parentid");
        $sql->execute(array(
            'parentid' => $categoryID
        ));
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getUserSubjects($user){
        global $database;
        $sql = $database->prepare("SELECT * from classsubjects
                                   JOIN subjects ON subjectid = subjects.id
                                   WHERE classid = :classid");
        $sql->execute(array(
            'classid' => $user->getClassID()
        ));
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getSubjects($classLvlRange){
        global $database;
        $sql = $database->prepare("SELECT s1.subjectname, s1.imgurl FROM subjects as s1
                                   JOIN subjects as s2 ON s1.subjectname = s2.subjectname
                                   WHERE classlevel IN (:classlvlRange)");
        $sql->execute($classLvlRange);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function &getSubjectCategories($subjects){
        foreach($subjects as $subject){
            $subjectCategories []= [$subject['id'], getUserCategories($subject['id'])];
        }
        return $subjectCategories;
    }

    function &getCategoryContents($subjectCategories){
        foreach($subjectCategories as $categories){
            foreach($categories[1] as $category){
                $categoryContents []= [$category['id'], getAllLobjects($category['id']), getSubCategories($category['id'])];
            }
        }

        for($i = 0; $i < count($categoryContents); $i++){
            for($j = 0; $j < count($categoryContents[$i][2]); $j++){
                if(isset($categoryContents[$i][2][$j]['id'])){
                    $id = $categoryContents[$i][2][$j]['id'];
                    $categoryContents []= [$id, getAllLobjects($id), getSubCategories($id)];
                }
            }
        }
        return $categoryContents;
    }