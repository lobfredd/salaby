<?php
    class SchooladminController extends SuperController{

        public function __construct($register){
            parent::__construct($register);
            include $this->root.'/app/model/schooladmin.php';
            include $this->root.'/app/model/teacher.php';
        }

        public function index(){
            $schoolID = getSchoolID($this->user->username);
            $school = getSchool($schoolID);
            $this->view->setViewPath('school/schooladmin.php');
            $this->view->schoolName = $school['name'];
            if (isset($_POST['searchBoxSchoolsClasses'])) $this->view->classes = searchClasses($schoolID, $_POST['searchBoxSchoolsClasses']);
            else $this->view->classes = getSchoolClasses($schoolID);

            $this->view->showPage();
        }

        public function showAddClass()
        {
            $this->view->setViewPath("/school/partialviews/addClass.php");
            $this->view->showPartialView();
        }

        public function getClasses(){
            $schoolID = getSchoolID($this->user->username);
            $classLevel = $this->urlElements[2];
            $this->view->setViewPath("school/partialviews/schoolClassesInLevel.php");
            $this->view->schoolClasses = getClassesInLevel($schoolID, $classLevel);
            $this->view->showPartialView();
        }

        public function getClassPupils(){
            $schoolID = getSchoolID($this->user->username);
            $classid = $this->urlElements[2];
            $this->view->setViewPath("school/partialviews/classPupils.php");
            $this->view->mainTeacher = getMainTeacher($classid);
            $this->view->classPupils = getClassPupils($classid);
            $this->view->classID = $classid;
            $this->view->schoolTeachers = getSchoolTeachers($schoolID);
            $this->view->showPartialView();
        }


        public function registerNewTeacher(){
            $schoolId = getSchoolID($this->user->username);
            registerTeacher($_POST['firstname'],$_POST['lastname'], $_POST['email'], $_POST['username'], $_POST['password'], $schoolId);
            header("Location: /schooladmin");
            exit;
        }

        public function addNewSchoolClass(){
            $schoolId = getSchoolID($this->user->username);
            addNewSchoolClass($schoolId, $_POST['className'], $_POST['classLevel']);
            header("Location: /schooladmin");
            exit;
        }

        public function deleteSchoolClass(){
            deleteSchoolClass($this->urlElements[2]);
        }

        public function editSchoolClass(){
            $schoolID = getSchoolID($this->user->username);
            $school = getSchool($schoolID);
            $this->view->setViewPath("school/editClass.php");
            $this->view->mainTeacher = getMainTeacher($this->urlElements[2]);
            $this->view->class = getSchoolClass($this->urlElements[2]);
            $this->view->pupils = getClassPupils($this->urlElements[2]);
            $this->view->schoolTeachers = getSchoolTeachers($schoolID);
            $this->view->schoolName = $school['name'];
            $this->view->showPage();
        }

        public function addNewPupilToClass(){
            $schoolId = getSchoolID($this->user->username);
            addNewPupilToClass($schoolId, $_POST['classId'], $_POST['firstName'], $_POST['lastName']);
            header("Location: /schooladmin/editSchoolClass/".$_POST['classId']);
            exit;
        }

        public function updateMainTeacher(){
            updateMainTeacher($_POST['classId'], $_POST['mainTeacher']);
            header("Location: /schooladmin/editSchoolClass/".$_POST['classId']);
            exit;
        }

        public function deleteUser(){
            $schoolId = getSchoolID($this->user->username);
            deletePupilUser($this->urlElements[2], $schoolId);
        }

        protected function checkUserAccess(){
            $user = $this->user;
            if(!isset($user) || !$user->isSchool()){
                header("Location: /login");
                exit;
            }
        }

        public function schoolPersonalPage () {
            $this->view->setViewPath('school/schoolPersonalPage.php');
            $this->view->school = getSchoolUser($this->user->username);
            $this->view->showPage();
        }

        public function doChangeEmail () {
            changeEmail($this->user->username, $_POST['email']);
            header("Location: /schooladmin/schoolPersonalPage");
            exit;
        }

        public function doChangePassword () {
            changePassword($this->user->username, $_POST['currentPassword'], $_POST['newPassword1'], $_POST['newPassword2']);
            header("Location: /schooladmin/schoolPersonalPage");
            exit;
        }

    }