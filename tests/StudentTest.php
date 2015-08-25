<?php

    /**
    * @backupGlobals disabled
    * @backupStatic Attributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";

    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StudentTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Student::deleteAll();
            Course::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);

            //Act
            $result = $test_student->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function testSetName()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);

            //Act
            $test_student->setName("Phil");
            $result = $test_student->getName();

            //Assert
            $this->assertEquals("Phil", $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Micah";
            $id = 1;
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);

            //Act
            $result = $test_student->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testGetEnrollmentDate()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);

            //Act
            $result = $test_student->getEnrollmentDate();

            //Assert
            $this->assertEquals($enrollment_date, $result);
        }

        function testSetEnrollmentDate()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);

            //Act
            $test_student->setEnrollmentDate("2014-08-10");
            $result = $test_student->getEnrollmentDate();

            //Assert
            $this->assertEquals("2014-08-10", $result);
        }

        function testSave()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);

            //Act
            $test_student->save();

            //Assert
            $result = Student::getAll();
            $this->assertEquals([$test_student], $result);
        }

        function testGetAll()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);
            $test_student->save();

            $name2 = "Phil";
            $enrollment_date2 = "2015-04-01";
            $test_student2 = new Student($id, $name2, $enrollment_date2);
            $test_student2->save();

            //Act
            $result = Student::getAll();

            //Assert
            $this->assertEquals([$test_student, $test_student2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);
            $test_student->save();

            $name2 = "Phil";
            $enrollment_date2 = "2015-04-01";
            $test_student2 = new Student($id, $name2, $enrollment_date2);
            $test_student2->save();

            //Act
            Student::deleteAll();
            $result = Student::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);
            $test_student->save();

            $name2 = "Phil";
            $enrollment_date2 = "2015-04-01";
            $test_student2 = new Student($id, $name2, $enrollment_date2);
            $test_student2->save();

            //Act
            $result = Student::find($test_student->getId());

            //Assert
            $this->assertEquals($test_student, $result);
        }

        function testUpdate()
        {
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-30";
            $test_student = new Student($id, $name, $enrollment_date);
            $test_student->save();
            $new_name = "Phil";

            //Act
            $test_student->update($new_name);

            //Assert
            $this->assertEquals("Phil", $test_student->getName());
        }

        function testDeleteStudent()
        {
            //Arrange
            $id = null;
            $name = "Micah";
            $enrollment_date = "2015-08-28";
            $test_student = new Student($id, $name, $enrollment_date);
            $test_student->save();

            $name2 = "Intro to History";
            $number = "HIST100";
            $test_course = new Course($id, $name2, $number);
            $test_course->save();

            //Act
            $test_student->addCourse($test_course);
            $test_student->delete();

            //Act
            $this->assertEquals([], $test_course->getStudents());
        }

        


    }
?>
