<?php

    class Student
    {
        private $id;
        private $name;
        private $enrollment_date;

        function __construct($id = null, $name, $enrollment_date)
        {
            $this->id = $id;
            $this->name = $name;
            $this->enrollment_date = $enrollment_date;
        }

        function getId()
        {
            return $this->id;
        }

        function getName()
        {
            return $this->name;
        }

        function getEnrollmentDate()
        {
            return $this->enrollment_date;
        }

        function getCourses()
        {
            $query = $GLOBALS['DB']->query("SELECT course_id FROM students_courses WHERE student_id = {$this->getId()};");
            $course_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $courses = array();
            foreach ($course_ids as $id) {
                $course_id = $id['course_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$course_id};");
                $returned_course = $result->fetchAll(PDO::FETCH_ASSOC);

                $id = $returned_course[0]['id'];
                $name = $returned_course[0]['name'];
                $number = $returned_course[0]['number'];
                $new_course = new Course($id, $name, $number);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function setEnrollmentDate($new_enrollment_date)
        {
            $this->enrollment_date = $new_enrollment_date;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO students (name, enrollment_date) VALUES ('{$this->getName()}', '{$this->getEnrollmentDate()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE students SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM students WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE student_id = {$this->getId()};");
        }

        //No test for this yet
        function addCourse($course)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$this->getId()}, {$course->getId()});");
        }

        static function getAll()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students ORDER BY name");
            $students = array();
            foreach ($returned_students as $student) {
                $id = $student['id'];
                $name = $student['name'];
                $enrollment_date = $student['enrollment_date'];
                $new_student = new Student($id, $name, $enrollment_date);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;");
        }

        static function find($search_id)
        {
            $found_student = null;
            $students = Student::getAll();
            foreach ($students as $student) {
                $student_id = $student->getId();
                if ($student_id == $search_id) {
                    $found_student = $student;
                }
            }
            return $found_student;
        }


        //Still need: getCourses
    }

?>
