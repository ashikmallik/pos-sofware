<?php

class Controller
{

//Start DB Connection for Local

    private $host = "localhost";
    private $user = "bsdbdxyz_user";
    private $pass = "bsdbdxyz_user";
    private $db = "bsdbdxyz_afrinEnt";
    private $permission;


    
    
    
    
    public  $companyname = "TE";
    public  $emailfrom = "";


    // function smsSend($phone,$smsbody)
    // {
    //   $user = "";
    //   $pass = "";
    //   $sender = "";


    //   $curl = curl_init();

    //   curl_setopt_array($curl, array(
    //       CURLOPT_URL => "http://api.icombd.com/api/v1/campaigns/sendsms/plain",
    //       CURLOPT_RETURNTRANSFER => true,
    //       CURLOPT_ENCODING => "",
    //       CURLOPT_MAXREDIRS => 10,
    //       CURLOPT_TIMEOUT => 0,
    //       CURLOPT_FOLLOWLOCATION => true,
    //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //       CURLOPT_CUSTOMREQUEST => "POST",
    //       CURLOPT_POSTFIELDS => "user=$user&password=$pass&sender=$sender&SMSText=$smsbody&GSM=88$phone",
    //       CURLOPT_HTTPHEADER => array(
    //           "Content-Type: application/x-www-form-urlencoded"
    //       ),
    //   ));

    //   $response = curl_exec($curl);

    //   curl_close($curl);

    //     $status = " SMS Sent to $phone & ";
    //   $status = "Sent";
    //     return $status;
    // }
  
  
  
    
            // One to Many                     
    function smsSend($number,$message)
    {
            
         $url = "https://bulksmsbd.net/api/smsapi";
     //    $url = "http://new.bulksmsbd.com/api/smsapimany";
         $api_key = "EmFZsexL37KI8CvJaqIQ";
        $senderid = "TalukdarEnt";
        $data = [
        "api_key" => $api_key,
        "senderid" => $senderid,
        "number" => $number,
        "message" => $message
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
        }
    
    // Many to Many 
    
    function sms_send($smsArray) {
        // $url = "https://bulksmsbd.net/api/smsapimany";
        $url = "http://new.bulksmsbd.com/api/smsapimany";
        $api_key = "EmFZsexL37KI8CvJaqIQ";
        $senderid = "TalukdarEnt";
        $messages = json_encode($smsArray);
        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "messages" => $messages
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        $json_array=json_decode($response,true);
        $status=$json_array['response_code'];
        $error=$json_array['error_message'];
        // echo $httpCode.'  '.$status.' '.$error;
        return $response;
    }
        
  
  
  
    function emailSend($email,$body,$sub)
    {
        $to =  $email;
        $subject = $sub?$sub: $this->companyname;
        $message = $body;

        // 

        $header = "From:" .$this->emailfrom . " \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";

        $retval = mail($to, $subject, $message, $header);

        if ($retval == true) {
            $notification = " Email Sent to $email Successfully...";
        } else {
            $notification = "Something is wrong Email not  sent...";
        }

        return $notification;
    }

    public function __construct()
    {
        $this->con = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->user, $this->pass);
        $this->permission = $this->getPermissonArray();
    }

//End DB Connection for Local

    public function login_check($table_name, $where_cond)
    {
        $sql_login = "SELECT * FROM " . $table_name . " WHERE $where_cond";
        $login = $this->con->prepare($sql_login);
        $login->execute();
        $total = $login->rowCount();

        if ($total == 1) {

            $data = $login->fetch(PDO::FETCH_ASSOC);
            return isset($data) ? $data : NULL;

        } else {
            return $total;
        }

    }


    public function sessionData($data)
    {

        if (isset($_SESSION["$data"]) && !empty($_SESSION["$data"])) {
            echo $_SESSION["$data"];
            unset($_SESSION["$data"]);
        }
    }

    public function notificationStore($text, $alert = 'danger')
    {
        $_SESSION['count'] = 0; // require for notificationShowRedirect() method

        if (isset($_SESSION["notification"]) && !empty($_SESSION["notification"])) {

            $_SESSION["notification"] .= '<div class="text-center alert alert-' . $alert . ' alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' . $text . '</b></div>';

        } else {

            $_SESSION["notification"] = '<div class="text-center alert alert-' . $alert . ' alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' . $text . '</b></div>';

        }
    }


    public function notificationShow()
    {
        $this->sessionData('notification');
    }


    /*
     *  this method is for while redirect with javascript
     *  the php code already load so before redirect the session is unset.
     *  so using count this code ensure after two times echo the session will unset
     */
    public function notificationShowRedirect()
    {

        if (isset($_SESSION['count'])) {
            $_SESSION['count']++;
        } else {
            $_SESSION['count'] = 0;
        }

        if (isset($_SESSION["notification"]) && !empty($_SESSION["notification"])) {
            echo $_SESSION["notification"];

            if ($_SESSION['count'] > 1) {
                unset($_SESSION["notification"]);
            }
        }
    }

    function printSidebarElement($userType, $permissionUrl,  $getUrl, $menuName, $icon = "th-list")
    {
        if ($this->hasPermission($userType, $permissionUrl)) {
            echo '<li class="list">&nbsp;<span class="glyphicon glyphicon-' . $icon . '"></span>&nbsp;<a class="texta" href="?q=' . $getUrl . '">' . $menuName . '</a></li>';
        } else {
            echo '';
        }
    }

    function getPermissonArray()
    {
        $emptyArray = array();

        if (isset($_SESSION['UserId'])) {
            $permissionArr = $this->details_selected_field_by_cond('_useraccess', 'MenuPermission', 'UserId=' . $_SESSION['UserId']);
            if (isset($permissionArr['MenuPermission']) && !empty($permissionArr['MenuPermission'])) {
                return unserialize($permissionArr['MenuPermission']);
            } else {
                return $emptyArray;
            }
        } else {
            return $emptyArray;
        }
    }

    // check this user's permission by existing in menu permission array
    function hasPermission($userType, $permissionArr)
    {
        if (!empty($permissionArr)) {

            if ($userType == 'SA') {
                return true;
            } else {
                return boolval(in_array($permissionArr, $this->permission));
            }
        }
        return false;
    }

    // check this user's permission by existing in menu permission array needed only edit user
    function userHasPermission($getUrl, $userId)
    {
        if (!empty($getUrl) && !empty($userId)) {

            $userAccess = $this->details_selected_field_by_cond('vw_user_info', 'MenuPermission, UserType', 'UserId=' . $userId);
            if ($userAccess['UserType'] == 'SA') {
                return true;
            } else {

                if (isset($userAccess['MenuPermission']) && !empty($userAccess['MenuPermission'])) {
                    return in_array($getUrl, unserialize($userAccess['MenuPermission']));
                } else {
                    return false;
                }

            }
        }
        return false;
    }


// Career Registration Function

    public function insert_by_condition($table_name, $form_data, $where_cond)
    {
        $fields = array_keys($form_data);

        $sql_login = "SELECT * FROM " . $table_name . " WHERE $where_cond";
        $login = $this->con->prepare($sql_login);
        $login->execute(array());
        $total = $login->rowCount();

        if ($total == '0') {

            $sql = "INSERT INTO " . $table_name . "
    (`" . implode('`,`', $fields) . "`)
    VALUES('" . implode("','", $form_data) . "')";
            $q = $this->con->prepare($sql);
            $q->execute() or die(print_r($q->errorInfo()));

            return $this->con->lastInsertId();
        }
    }


    // Data Insert Function
    public function Insert_data($table_name, $form_data)
    {
        $fields = array_keys($form_data);

        $sql = "INSERT INTO " . $table_name . "
    (`" . implode('`,`', $fields) . "`)
    VALUES('" . implode("','", $form_data) . "')";

        $q = $this->con->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));

        return $this->con->lastInsertId();
    }


// View All Data Function
    public function view_all($table_name)
    {
        $data = array();
        $sql = "SELECT * FROM $table_name";
        $q = $this->con->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

// View All Data Function
    public function get_all_expense($dateform, $dateto)
    {
        $data = array();
        $sql = "SELECT (SELECT acc_name FROM tbl_accounts_head Where acc_id=acc_head ) as name,acc_head,SUM(acc_amount) as payments FROM tbl_account WHERE acc_type='1' AND MONTH(entry_date)='$dateform' AND YEAR(entry_date)='$dateto' GROUP BY acc_head ORDER BY entry_date ASC";
        //echo $sql;
        $q = $this->con->prepare($sql);
        $q->execute();
        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

//Sum of Expense
    public function get_sum_expense($dateform, $dateto)
    {
        $data = array();
        $sql = "SELECT SUM(acc_amount) as amount FROM tbl_account WHERE acc_type='1' AND MONTH(entry_date)='$dateform' AND YEAR(entry_date)='$dateto'";
        $q = $this->con->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

// sum of all income
    public function get_all_income($dateform, $dateto)
    {
        $data = array();
        $sql = "SELECT SUM(amount) as amount FROM vw_all_income WHERE MONTH(entry_date)='$dateform' AND YEAR(entry_date)='$dateto'";
        $q = $this->con->prepare($sql);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function get_avg_data($table_name, $avg_of_field, $where_cond)
    {

        $sql = "SELECT AVG($avg_of_field) as total FROM $table_name WHERE $where_cond";
        $q = $this->con->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));
        $data = $q->fetch(PDO::FETCH_ASSOC);

        return isset($data['total']) ? $data['total'] : 0;
    }

// View All Data Function
    public function ex_row($dateform, $dateto)
    {
        $data = array();
        $sql = "SELECT (SELECT acc_name FROM tbl_accounts_head Where acc_id=acc_head ) as name, acc_head,SUM(acc_amount) as payments FROM tbl_account WHERE acc_type='1' AND MONTH(entry_date)='$dateform' AND YEAR(entry_date)='$dateto' GROUP BY acc_head ORDER BY entry_date ASC";
        $q = $this->con->prepare($sql);
        $q->execute();
        return $q->rowCount();
    }


// View All Ordered By  Data Function
    public function view_all_ordered_by($table_name, $order)
    {
        $data = array();
        $sql = "SELECT * FROM $table_name ORDER BY $order";
        $q = $this->con->prepare($sql);
        $q->execute();
        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

// View All Data Condition wise Function
    public function view_all_by_cond($table_name, $where_cond)
    {
        $data = array();
        $sql = "SELECT * FROM $table_name WHERE $where_cond";
        $q = $this->con->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


// Details Data View Condition Wise Function
    public function details_by_cond($table_name, $where_cond)
    {
        $sql = "SELECT * FROM $table_name WHERE $where_cond";
        $q = $this->con->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }


// Update Data Function

    function Update_data($table_name, $form_data, $where_clause = '')
    {

        $whereSQL = '';
        if (!empty($where_clause)) {

            if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {

                $whereSQL = " WHERE " . $where_clause;
            } else {
                $whereSQL = " " . trim($where_clause);
            }
        }

        $sql = "UPDATE " . $table_name . " SET ";

        $sets = array();
        foreach ($form_data as $column => $value) {
            $sets[] = "`" . $column . "` = '" . $value . "'";
        }
        $sql .= implode(', ', $sets);

        $sql .= $whereSQL;
        $q = $this->con->prepare($sql);

        return $q->execute() or die(print_r($q->errorInfo()));
    }

    //only get total qty of a column.
    public function get_sum_data($table_name, $sum_of_field, $where_cond)
    {

        $sql = "SELECT SUM($sum_of_field) as total FROM $table_name WHERE $where_cond";
        $q = $this->con->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));
        $data = $q->fetch(PDO::FETCH_ASSOC);

        return isset($data['total']) ? $data['total'] : 0;
    }


// Delete Data Function
    function Delete_data($table_name, $where_cond)
    {

        $sql = "DELETE FROM $table_name WHERE $where_cond";
        $q = $this->con->prepare($sql);

        return $q->execute();
    }


// Mail Send with Attach file
    function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message)
    {
        $file = $path . $filename;
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $name = basename($file);
        $header = "From: " . $from_name . " <" . $from_mail . ">\r\n";
        $header .= "Reply-To: " . $replyto . "\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n";
        $header .= "--" . $uid . "\r\n";
        $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message . "\r\n\r\n";
        $header .= "--" . $uid . "\r\n";
        $header .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
        $header .= $content . "\r\n\r\n";
        $header .= "--" . $uid . "--";
        if (mail($mailto, $subject, "", $header)) {
            echo "mail send ... OK"; // or use booleans here
        } else {
            echo "mail send ... ERROR!";
        }
    }


// Mail send without attach file

    function mail_send($to, $subject, $message, $from)
    {

        if (mail($to, $subject, $message, "From: $from\n")) {
            //echo "mail send ... OK"; // or use booleans here
        } else {
            echo "mail send ... ERROR!";
        }
    }

// End Mailing Function

    public function Total_Count($table_name, $where_cond)
    {
        $sql_login = "SELECT * FROM " . $table_name . " WHERE $where_cond";
        $login = $this->con->prepare($sql_login);
        $login->execute();
        $total = $login->rowCount();

        return $total;

    }

//Start Randome Code

    function Random_Code($chars, $length)
    {

        srand((double)microtime() * 1000000);
        $i = 0;
        $code = '';

        while ($i <= ($length - 1)) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $code = $code . $tmp;
            $i++;


        }
        return $code;
    }

    function searchInDatabase($selectField, $tableName = '', $keyword = '', $searchField = '', $where_clause = '')
    {
        if (!empty($keyword)) {
            $rawKeyword = trim($keyword);
            $filteredKeyword = preg_replace('#[^A-Za-z- 0-9?!.]#i', '', $rawKeyword);
            $splitArrayInSpaceKeyword = preg_split('/[\s]+/', $filteredKeyword);

            $keywordCount = count($splitArrayInSpaceKeyword);

            if ($keywordCount == 1) {

                if (isset($where_clause) && !empty($where_clause)) {
                    $query = "SELECT $selectField FROM `$tableName` WHERE $where_clause AND `$searchField` LIKE 
                    '%$splitArrayInSpaceKeyword[0]%'";
                } else {
                    $query = "SELECT $selectField FROM `$tableName` WHERE `$searchField` LIKE '%$splitArrayInSpaceKeyword[0]%'";
                }
            } else {
                if (isset($where_clause) && !empty($where_clause)) {
                    $query = "SELECT $selectField FROM `$tableName` WHERE $where_clause AND `$searchField` LIKE '%$splitArrayInSpaceKeyword[0]%'";
                } else {
                    $query = "SELECT $selectField FROM `$tableName` WHERE `$searchField` LIKE '%$splitArrayInSpaceKeyword[0]%'";
                }

                for ($i = 1; $i < $keywordCount; $i++) {
                    $query .= " AND `$searchField` LIKE '%$splitArrayInSpaceKeyword[$i]%'";
                }
            }

            $rowData = array();
            $sql = $this->con->prepare($query);
            $sql->execute();

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $rowData[] = $row;
            }

            return $rowData;
        }// if keyword is not empty
    }// searchInDatabase finished here

//End Randome Code

    public function raw_sql($raw_sql)
    {
        $data = array();
        $sql = "SELECT $raw_sql";
        $q = $this->con->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function raw_sql_single($raw_sql)
    {
        $sql = "SELECT $raw_sql";
        $q = $this->con->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    // View Selected Field Data Condition wise Function

    public function view_selected_field_by_cond($table_name, $selected_field, $where_cond)
    {
        $data = array();
        $sql = "SELECT $selected_field FROM $table_name WHERE $where_cond";
        $q = $this->con->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function details_selected_field_by_cond($table_name, $selected_field, $where_cond)
    {
        $sql = "SELECT $selected_field FROM $table_name WHERE $where_cond";
        $q = $this->con->prepare($sql);
        $q->execute() or die(print_r($q->errorInfo()));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    
// View All Data Condition wise Function with left join table
    public function view_all_by_cond_left_join($table_name1, $table_name2, $table1_col_matched, $table2_col_matched, $table2_col, $where_cond = null)
    {
        $data = array();
        if (!empty($where_cond)) {
            $sql = "SELECT $table_name1.*,$table_name2.$table2_col FROM $table_name1 LEFT JOIN $table_name2 ON $table_name1.$table1_col_matched = $table_name2.$table2_col_matched WHERE $where_cond";
        } else {
            $sql = "SELECT $table_name1.*,$table_name2.$table2_col FROM $table_name1 LEFT JOIN $table_name2 ON $table_name1.$table1_col_matched = $table_name2.$table2_col_matched";
        }
        $q = $this->con->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    
// View All Data Condition wise Function with left join table
    public function view_selected_field_by_cond_left_join($table_name1, $table_name2, $table1_col_matched, $table2_col_matched, $table1_col, $table2_col, $where_cond = null)
    {
        $data = array();
        if (!empty($where_cond)) {
            $sql = "SELECT $table1_col, $table_name2.$table2_col FROM $table_name1 LEFT JOIN $table_name2 ON $table_name1.$table1_col_matched = $table_name2.$table2_col_matched WHERE $where_cond";
        } else {
            $sql = "SELECT $table1_col, $table_name2.$table2_col FROM $table_name1 LEFT JOIN $table_name2 ON $table_name1.$table1_col_matched = $table_name2.$table2_col_matched";
        }
        $q = $this->con->prepare($sql);
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


    public function getAccTypeId($accNameKey){

        $accTypeData = $this->details_by_cond('acc_type_list', 'name_key = "'.$accNameKey.'"');
        if(!empty($accTypeData) && isset($accTypeData)){
            return $accTypeData['id'];
        }else{

        }
    }
    
      // get single row
  public function get_single($select)
  {
      $data = array();
      $sql = "$select";
      $q = $this->con->prepare($sql);
      $q->execute();
      $data = $q->fetch(PDO::FETCH_ASSOC);
      return $data;
  }
  
  /// /Get Data 
public function get_data($select, $tableName, $whereclause)
{
    $data = array();
    $sql = "SELECT $select FROM $tableName WHERE $whereclause ";
    $q = $this->con->prepare($sql);
    $q->execute();
    $data = $q->fetch(PDO::FETCH_ASSOC);
    return $data;
}


    
}// class Controller

?>
