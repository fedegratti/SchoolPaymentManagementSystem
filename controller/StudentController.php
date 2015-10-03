<?php

class StudentController
{
    public static function addStudentView()
    {
        AuthController::checkPermission();
        $view = new AddStudentView();
        $view->show();
    }

    public static function addStudentAction()
    {
        AuthController::checkPermission();
        $studentRepository = new StudentModel();
        $studentID = $studentRepository->createStudent($_POST);

       	if($_POST['guardianType'] == 'createGuardian')
       	{

    		GuardianController::addGuardianView($studentID);
       	}
       	else
       	{
            GuardianController::listGuardiansView($studentID);
       	}
    }

    public  static function listStudentsView()
    {
        AuthController::checkPermission();
        if(isset($_POST["studentName"]))
        {
            StudentController::listStudentsWithNameView($_POST["studentName"]);
        }
        else
        {
            $view = new ListStudentsView();
            $view ->show();
        }

    }
    public  static function listStudentsWithNameView($studentName)
    {
        AuthController::checkPermission();
        $view = new ListStudentsView();
        $studentData = (new StudentModel())->getStudentsByName($studentName);
        $view ->show($studentData);
    }
    public static function updateStudentView($studentID)
    {
        AuthController::checkPermission();
        $view = new UpdateStudentView();
        $studentData = (new StudentModel())->getStudent($studentID);
        $view->show($studentData);
    }

    public static function updateStudentAction ()
    {
        AuthController::checkPermission();
        $studentRepository = new StudentModel();
        $studentRepository->updateStudent($_POST);
        echo "alumno actualizado";
    }

    public  static function deleteStudentAction($studentID)
    {
        AuthController::checkPermission();
        //(new StudentRepository())->deleteStudent($studentID);
        echo "alumno eliminado";
    }
}