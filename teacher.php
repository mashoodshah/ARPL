<?php

class TeacherModel extends Model
{

    public function register()
    {
        // Sanitize POST
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $password = md5($post['password']);

        if ($post['submit']) {
            if ($post['name'] == '' || $post['email'] == '' || $post['password'] == '' || $post['school'] == '') {
                Messages::setMsg('Please Fill In All Fields', 'error');
                return;
            } else {
                if (filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                    //Valid email!
                    if ($this->isUnique($post['email'])) {
                        // Insert into MySQL
                        $this->query('INSERT INTO Teachers (name, email, password, school) VALUES(:name, :email, :password, :school)');
                        $this->bind(':name', $post['name']);
                        $this->bind(':email', strtolower($post['email']));
                        $this->bind(':password', $password);
                        $this->bind(':school', $post['school']);
                        $this->execute();
                        Messages::setMsg(strtoupper($post['name']) . ' Registered as Teacher!', 'success');
                    } else {
                        Messages::setMsg('Email already exists!', 'error');
                    }
                } else {
                    Messages::setMsg('Please enter valid email', 'error');
                }
            }
        }
        return;
    }

    //  True if email does not exist in database else false [used in register]
    public function isUnique($email)
    {
        $this->query('SELECT email FROM Teachers');
        $rows = $this->resultSet();
        foreach ($rows as $item) {
            if (!strcasecmp($email, $item['email'])) {
                return false;
            }
        }
        return true;
    }

    public function login()
    {
        // Sanitize POST
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $password = md5($post['password']);

        if ($post['submit']) {
            // Compare Login
            $this->query('SELECT * FROM Teachers WHERE email = :email AND password = :password');
            $this->bind(':email', strtolower($post['email']));
            $this->bind(':password', $password);

            $row = $this->single();

            if ($row) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_data'] = array(
                    "teacher" => 'teacher',
                    "teacher_id" => $row['teacher_id'],
                    "name" => $row['name'],
                    "email" => $row['email']
                );
                header('Location: ' . ROOT_URL . 'admin');
            } else {
                Messages::setMsg('Incorrect Login', 'error');
            }
        }
        return;
    }

    public function Index()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($post['deleteC'])) {
            $this->deleteClassroom($post['deleteC']);
        }
        $this->query('SELECT * FROM Classrooms');
        $rows = $this->resultSet();
        return $rows;
    }

    public function Students()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($post['deleteS'])) {
            $this->deleteUser($post['deleteS']);
        } elseif (isset($post['approve'])) {
            $this->deleteUser($post['approve']);
        }
        $id = $_SESSION['user_data']['teacher_id'];
//        $this->query('SELECT * FROM Students WHERE class_code IN (SELECT class_code FROM Classrooms WHERE teacher_id = :id)');
        $this->query('SELECT * FROM Classrooms WHERE teacher_id = :id');
        $this->bind(':id', $id);
        $rows = $this->resultSet();
        return $rows;
    }

    public function returnStudent()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($post["classCode"])) {
            $classCode = $post['classCode'];
            $this->query('SELECT * FROM Students WHERE class_code = :classCode');
            $this->bind(':classCode', $classCode);
            $rows = $this->resultSet();
            return $rows;
        } else {
            return false;
        }
    }

    public function evaluateStudents()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($post['delete'])) {
            $this->deleteUser($post['delete']);
        } elseif (isset($post['approve'])) {
            $this->deleteUser($post['approve']);
        }
        $id = $_SESSION['user_data']['teacher_id'];
        $this->query('SELECT * FROM Students WHERE class_code = (SELECT class_code FROM Classrooms WHERE teacher_id = :id)');
        $this->bind(':id', $id);
        $rows = $this->resultSet();
        return $rows;
    }

    public function addClassroom()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if ($post['submit']) {
            if ($post['name'] == '' || $post['code'] == '') {
                Messages::setMsg('Please Fill In All Fields', 'error');
                return;
            } else if (strlen($post['code']) < 5) {
                var_dump($post['code']);
                echo strlen($post['code']);
                Messages::setMsg('Class Code should have at least 5 characters!', 'error');
                return;
            } else if (isset($_SESSION['user_data']['teacher_id'])) {
                $id = $_SESSION['user_data']['teacher_id'];
                $this->query('INSERT INTO Classrooms (class_code, class_name, teacher_id) VALUES(:code, :name, :id)');
                $this->bind(':code', $post['code']);
                $this->bind(':name', $post['name']);
                $this->bind(':id', $id);
                $this->execute();
                Messages::setMsg(strtoupper($post['name']) . ' Classroom Added!', 'success');
                return;
            }
        }
    }


    public
    function deleteUser($id)
    {
        if (!empty($id)) {
            $this->query('DELETE FROM Students WHERE student_id = :id');
            $this->bind(':id', $id);
            $this->execute();
            Messages::setMsg('Deleted Successfully', 'success');
        } else {
            Messages::setMsg('Error in Deleting', 'error');
        }
    }

    public
    function deleteClassroom($classCode)
    {
        if (!empty($classCode)) {
            $this->query('DELETE FROM Classrooms WHERE class_code = :classCode');
            $this->bind(':classCode', $classCode);
            $this->execute();
            Messages::setMsg('Deleted Successfully', 'success');
        } else {
            Messages::setMsg('Error in Deleting', 'error');
        }
    }

    public
    function loadTeacher()
    {
        $this->query('SELECT * FROM Teachers');
        $rows = $this->resultSet();
        return $rows;
    }

    public
    function redirect($urlStr)
    {
//        header('Location: ' . ROOT_URL . 'admin/'+ $urlStr);
        ?>
        <script>
            $url = "<?php echo ROOT_URL; ?>";
            location.replace($url + 'admin/<?php echo $urlStr; ?>');
        </script>
        <?php
    }

////////////////////////////////////////////////
    public
    function Admins()
    {
        $get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        print_r($_GET);
        if (isset($get['submit'])) {
            $this->deleteUser($get['adminDel']);
        }
        $this->query('SELECT * FROM Admin');
        $rows = $this->resultSet();
        return $rows;
    }

    public
    function region()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if ($post['submit'] == 'X') {
            $this->deleteRegion($post['region']);
        }
    }

    public
    function deleteRegion($region)
    {
        if (!empty($region)) {
            $this->query('DELETE FROM regions WHERE region = :region');
            $this->bind(':region', $region);
            $this->execute();
            Messages::setMsg('Deleted Successfully', 'success');
        } else {
            Messages::setMsg('Error in Deleting', 'error');
        }
    }

}