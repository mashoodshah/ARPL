<?php

class AdminModel extends Model
{

    public function register()
    {
        // Sanitize POST
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $password = md5($post['password']);

        if ($post['submit']) {
            if ($post['name'] == '' || $post['email'] == '' || $post['password'] == '') {
                Messages::setMsg('Please Fill In All Fields', 'error');
                return;
            } else {
                if (filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                    //Valid email!
                    if ($this->isUnique($post['email'])) {
                        // Insert into MySQL
                        $this->query('INSERT INTO Admin (name, email, password) VALUES(:name, :email, :password)');
                        $this->bind(':name', $post['name']);
                        $this->bind(':email', strtolower($post['email']));
                        $this->bind(':password', $password);
                        $this->execute();
                        Messages::setMsg(strtoupper($post['name']) . ' Registered as User!', 'success');
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

    public function isUnique($email)
    {
        $this->query('SELECT email FROM Admin');
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
            $this->query('SELECT * FROM Admin WHERE email = :email AND password = :password');
            $this->bind(':email', strtolower($post['email']));
            $this->bind(':password', $password);

            $row = $this->single();

            if ($row) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_data'] = array(
                    "admin" => 'zx2cv',
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

    public function Registered()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($post['adminDel'])) {
            $this->deleteUser($post['adminDel']);
        }
        $this->query('SELECT * FROM Admin');
        $rows = $this->resultSet();
        return $rows;
    }

    public function Index()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($post['deleteT'])) {
            $this->deleteTeacher($post['deleteT']);
        } elseif (isset($post['approveT'])) {
            $this->approveTeacher($post['approveT'], "approved");
        }
        $this->query('SELECT * FROM Teachers');
        $rows = $this->resultSet();
        return $rows;
    }

    public function deleteUser($id)
    {
        if (!empty($id)) {
            $this->query('DELETE FROM Admin WHERE admin_id = :id');
            $this->bind(':id', $id);
            $this->execute();
            Messages::setMsg('Deleted Successfully', 'success');
        } else {
            Messages::setMsg('Error in Deleting', 'error');
        }
    }

    public function deleteTeacher($id)
    {
        if (!empty($id)) {
            $this->query('DELETE FROM Teachers WHERE teacher_id = :id');
            $this->bind(':id', $id);
            $this->execute();
            Messages::setMsg('Deleted Successfully', 'success');
        } else {
            Messages::setMsg('Error in Deleting', 'error');
        }
    }

    public function approveTeacher($id, $approved)
    {
        if (!empty($id)) {
            $this->query('UPDATE Teachers SET approved = :approved WHERE teacher_id = :id');
            $this->bind(':id', $id);
            $this->bind(':approved', $approved);
            $this->execute();
            Messages::setMsg('Approved Successfully', 'success');
        } else {
            Messages::setMsg('Error in Approving', 'error');
        }
    }

    public function loadTeacher()
    {
        $this->query('SELECT * FROM Teachers');
        $rows = $this->resultSet();
        return $rows;
    }

    public function redirect($urlStr)
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
    public function Admins()
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

    public function region()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if ($post['submit'] == 'X') {
            $this->deleteRegion($post['region']);
        }
    }

    public function deleteRegion($region)
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