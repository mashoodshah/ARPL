<?php

class StudentModel extends Model
{

    public function register()
    {
        // Sanitize POST
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if ($post['submit']) {
            if ($post['name'] == '' || $post['rollNo'] == '' || $post['password'] == '' || $post['classCode'] == '') {
                Messages::setMsg('Please Fill In All Fields', 'error');
                return;
            } else {
                //  Validate Class Code
                if ($this->isClassCode($post['classCode'])) {
                    //  Validate Roll No
                    if ($this->isUnique($post['rollNo'])) {
                        //  Encrypt Password
                        $password = md5($post['password']);
                        //  Insert into MySQL
                        $this->query('INSERT INTO Students (name, roll_no, password, class_code) VALUES(:name, :rollNo, :password, :classCode)');
                        $this->bind(':name', $post['name']);
                        $this->bind(':rollNo', strtolower($post['rollNo']));
                        $this->bind(':password', $password);
                        $this->bind(':classCode', $post['classCode']);
                        $this->execute();
                        Messages::setMsg(strtoupper($post['name']) . ' Registered as Student!', 'success');
                        echo "success";
                        return;
                    } else {
                        Messages::setMsg('Roll No already exists!', 'error');
                        echo("Roll No already exists!");
                        return;
                    }
                } else {
                    Messages::setMsg('Invalid Class Code!', 'error');
                    echo("Invalid Class Code!");
                    return;
                }
            }
        }
        return;
    }

    public function isClassCode($classCode)
    {
        $this->query('SELECT * FROM Classrooms WHERE class_code=' . ':classCode');
        $this->bind(':classCode', $classCode);
        $rows = $this->resultSet();
        if (empty($rows)) {
            return false;
        } else {
            if ($rows[0] == null) {
                return false;
            }
        }
        return true;
    }

    public function isUnique($rollNo)
    {
        $this->query('SELECT roll_no FROM Students');
        $rows = $this->resultSet();
        foreach ($rows as $item) {
            if (!strcasecmp($rollNo, $item['roll_no'])) {
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
            $this->query('SELECT * FROM Students WHERE roll_no = :rollNo AND password = :password');
            $this->bind(':rollNo', strtolower($post['rollNo']));
            $this->bind(':password', $password);

            $row = $this->single();
            if ($row) {
                echo("success");
                return;
            } else {
                echo("Invalid Roll Number/ Password");
                return;
            }

            if ($row) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_data'] = array(
                    "teacher" => 'teacher',
                    "name" => $row['name'],
                    "teacher_id" => $row['teacher_id'],
                    "email" => $row['email']
                );
                header('Location: ' . ROOT_URL . 'admin');
            } else {
                Messages::setMsg('Incorrect Login', 'error');
            }
        }
        return;
    }

}