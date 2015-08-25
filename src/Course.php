<?php

    class Course
    {
        private $id;
        private $name;
        private $number;

        function __construct($id = null, $name, $number)
        {
            $this->id = $id;
            $this->name = $name;
            $this->number = $number;
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

        function getNumber()
        {
            return $this->number;
        }

        function getStudents()
        {
            $found_students = $GLOBALS['DB']->query(
            "SELECT students.* FROM
            courses JOIN students_courses ON (courses.id = students_courses.course_id)
                    JOIN students ON (students_courses.student_id = students.id)
            WHERE course_id = {$this->getId()};"
            );

            $students = array();
            foreach ($found_students as $student) {
                $id = $student['id'];
                $name = $student['name'];
                $enrollment_date = $student['enrollment_date'];
                $new_student = new Student($id, $name, $enrollment_date);
                array_push($students, $new_student);
            }
            return $students;
        }

        //Setters
        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function setNumber($new_number)
        {
            $this->number = $new_number;
        }

        //Save function
        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO courses (name, number) VALUES ('{$this->getName()}', '{$this->getNumber()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        //Add function
        function addStudent($student)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$student->getId()}, {$this->getId()});");
        }

        //Update function
        function update($new_name, $new_number)
        {
            $GLOBALS['DB']->exec("UPDATE courses SET name = '{$new_name}', number = '{$new_number}' WHERE id = {$this->getID()};");
            $this->setName($new_name);
            $this->setNumber($new_number);
        }

        //Delete function
        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE course_id = {$this->getId()};");
        }

        //Static functions
        static function getAll()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses");
            $courses = array();
            foreach ($returned_courses as $course) {
                $id = $course['id'];
                $name = $course['name'];
                $number = $course['number'];
                $new_course = new Course($id, $name, $number);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
        }
    }


?>
