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

        //Getters
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
            $found_courses = $GLOBALS['DB']->query(
            "SELECT courses.* FROM
            students JOIN students_courses ON (students.id = students_courses.student_id)
                     JOIN courses ON (students_courses.course_id = courses.id)
            WHERE students.id = {$this->getId()};");

            $courses = array();
            foreach ($found_courses as $course) {
                $id = $course['id'];
                $name = $course['name'];
                $number = $course['number'];
                $new_course = new Course($id, $name, $number);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        //Setters
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

        //Static functions
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

    }

?>
