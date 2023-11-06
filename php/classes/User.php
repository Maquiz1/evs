<?php
class User
{
    private $_db,
        $_data,
        $_getdata,
        $_sessionName,
        $_sessionTableName,
        $_sessionTable,
        $_cookieName,
        $_override;
    public $isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_override = new OverideData();
        $this->_sessionName = config::get('session/session_name');
        $this->_sessionTable = config::get('session/session_table');
        $this->_cookieName = config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                $this->_sessionTableName = Session::getTable($this->_sessionTable);
                if ($this->findUser($user, $this->_sessionTableName)) {
                    $this->isLoggedIn = true;
                } else {
                }
            }
        } else {
            $this->find($user);
        }
    }
    public function getSessionTable()
    {
        return $this->_sessionTableName;
    }
    public function validateBundle($message, $noUser)
    {
        $noWords = $this->countWords($message, $noUser);
        if ($noWords <= $this->checkBundle()[0]['sms']) {
            return true;
        }
    }
    public function countWords($message, $noUser)
    {
        return ceil((mb_strlen($message)) / 160) * $noUser;
    }

    function dateDiff($startDate, $endDate)
    {
        $date = strtotime($endDate) - strtotime($startDate);
        return number_format($date / 86400);
    }

    function dateDiffYears($startDate, $endDate)
    {
        $date = abs(strtotime($endDate) - strtotime($startDate));
        return number_format($date / (365 * 60 * 60 * 24));
    }

    public function getOS()
    {

        global $user_agent;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os_platform  = "Unknown OS Platform";

        $os_array     = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;

        return $os_platform;
    }

    function visit_delete($client_id)
    {
        $this->deleteRecord('visit', 'client_id', $client_id);
    }

    function visit($client_id, $seq)
    {
        // $this->deleteRecord('visit', 'client_id', $client_id);

        if ($this->_override->getCount('visit', 'client_id', $client_id) == 1) {
            $sq = $seq;
            foreach ($this->_override->getData('schedule') as $schedule) {
                if ($sq < 14) {
                    $sq += 7;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                } else if ($sq >= 14 && $sq < 30) {
                    $sq += 16;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                } else if ($sq >= 30 && $sq < 120) {
                    $sq += 30;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                } else if ($sq >= 120 && $sq < 121) {
                    $sq += 1;
                    $visit_name = 'STUDY TERMINATION';
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                } else if ($sq >= 121) {
                    $visit_name = 'ADVERSE EVENT';
                    $sq = 120;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                }
            }
        }

        return $this->_override->getData('schedule');
    }

    function visit2($client_id, $seq, $study_id)
    {
        // $this->deleteRecord('visit', 'client_id', $client_id);
        if ($this->_override->getCount('visit', 'client_id', $client_id) == 1) {
            $sq = $seq;
            foreach ($this->_override->getData('schedule') as $schedule) {
                if ($sq < 29) {
                    $sq += 28;
                    $visit_name = 'Day ' . $sq;
                    // $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 1 days'));
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['visit_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        // 'site_id' => $user->data()->site_id,

                    ));
                } else if ($sq >= 29 && $sq < 57) {
                    $sq += 28;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                } else if ($sq >= 57 && $sq < 215) {
                    $sq += 158;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                } else if ($sq >= 215) {
                    $visit_name = 'END STUDY';
                    $sq = 215;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                    ));
                }
            }
        }

        return $this->_override->getData('schedule');
    }

    function visit3($client_id, $seq, $study_id, $site_id, $project_id, $staff_id)
    {
        if ($this->_override->getCount('visit', 'client_id', $client_id) == 1) {
            $sq = $seq;
            foreach ($this->_override->getData('schedule') as $schedule) {
                if ($sq >= 1 && $sq < 2) {
                    $sq += 1;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                } else if ($sq >= 2 && $sq < 3) {
                    $sq += 1;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                } else if ($sq >= 3 && $sq < 5) {
                    $sq += 2;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                } else if ($sq >= 5 && $sq < 8) {
                    $sq += 3;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                } else if ($sq >= 8 && $sq < 15) {
                    $sq += 7;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                } else if ($sq >= 15 && $sq < 22) {
                    $sq += 7;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                } else if ($sq >= 22 && $sq < 30) {
                    $sq += 8;
                    $visit_name = 'Day ' . $sq;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                } else if ($sq >= 30) {
                    $visit_name = 'Day ' . $sq;
                    $sq = 30;
                    $last_visit_date = $this->_override->getlastRow('visit', 'client_id', $client_id, 'id')[0]['expected_date'];
                    $nxt_visit = date('Y-m-d', strtotime($last_visit_date . ' + ' . $schedule['days'] . ' days'));
                    $this->createRecord('visit', array(
                        'visit_name' => $visit_name,
                        'visit_code' => $schedule['visit'],
                        'study_id' => $study_id,
                        'expected_date' => $nxt_visit,
                        'visit_window' => $schedule['window'],
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'client_id' => $client_id,
                        'seq_no' => $sq,
                        'status' => 0,
                        'site_id' => $site_id,
                        'project_name' => $project_id,
                        'staff_id' => $staff_id,

                    ));
                }
            }
        }

        return $this->_override->getData('schedule');
    }

    function getBrowser()
    {

        global $user_agent;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser        = "Unknown Browser";

        $browser_array = array(
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $browser = $value;

        return $browser;
    }

    function getIp()
    {
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    public function renameFile($file, $name)
    {
        rename($file, $name);
        return $name;
    }
    public function download($path)
    {
        $file = $path;
        $filename = 'PRST Constitution.pdf';
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        header('Accept-Ranges: bytes');
        @readfile($file);
    }
    public function readPdf($path)
    {
        $file = $path;
        $filename = 'Document.pdf';
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        header('Accept-Ranges: bytes');
        @readfile($file);
    }
    function customStringLength($x, $length)
    {
        if (strlen($x) <= $length) {
            return $x;
        } else {
            $y = substr($x, 0, $length) . '...';
            return $y;
        }
    }
    function removeSpecialChar($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    function excelRow($x, $y)
    {
        $arr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        if ($x > 26) {
            if ($x % 26 == 0) {
                $v = abs($x / 26 - $x / 26);
            } else {
                $v = abs(floor($x / 26) - 1);
            }
            return $arr[$v] . '' . $arr[$y];
        } else {
            return $arr[$y];
        }
    }

    function exportData($data, $file)
    {
        $timestamp = time();
        $filename = $file . '_' . $timestamp . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $isPrintHeader = false;
        foreach ($data as $row) {
            if (!$isPrintHeader) {
                echo implode("\t", array_keys($row)) . "\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
        exit();
    }

    public function update($fields = array(), $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }
        if (!$this->_db->update('user', $id, $fields)) {
            throw new Exception('There is problem updating');
        }
    }
    public function updateRecord($table, $fields = array(), $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }
        if (!$this->_db->update($table, $id, $fields)) {
            throw new Exception('There is problem updating');
        }
    }
    public function deleteRecord($table, $field, $value)
    {
        if (!$this->_db->delete($table, array($field, '=', $value))) {
            throw new Exception('There is problem deleting');
        }
    }

    public function createRecord($table, $fields = array())
    {
        if (!$this->_db->insert($table, $fields)) {
            throw new Exception('There is a problem creating Account');
        }
        return true;
    }


    public function createTable($table)
    {
        if (!$this->_db->mycreate($table)) {
            throw new Exception('There is a problem creating Table');
        }
        return true;
    }

    public function find($user = null)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'email';
            $data = $this->_db->get('staff', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
    }
    public function findUser($user = null, $table)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get($table, array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
    }

    public function loginUser($username = null, $password = null, $table)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->findUser($username, $table);
            if ($user) {
                if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    Session::putSession($this->_sessionTable, $table);
                    return true;
                }
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username);
            if ($user) {
                if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('user_session', array('user_id', '=', $this->data()->id));
                        if (!$hashCheck->count()) {
                            $this->_db->insert('user_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookieName, $hash, config::get('remember/cookie_expiry'));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }
    public function logout()
    {
        $this->_db->delete('user_session', array('user_id', '=', $this->data()->id));
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }
    public function data()
    {
        return $this->_data;
    }
    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }
    function report($value)
    {
        $men = 0;
        $women = 0;
        $elders = 0;
        $children = 0;
        $dependant = 0;
        $citizen = 0;
        $men = $this->_override->getCount('citizen', 'gender', 'Male');
        $women = $this->_override->getCount('citizen', 'gender', 'Female');
        $elders = $this->_override->getSumD('citizen', 'no_elder');
        $children = $this->_override->getSumD('citizen', 'no_children');
        $dependant = $this->_override->getSumD('citizen', 'no_dependant');
        $citizen = $men + $women + $elders[0]['SUM(no_elder)'] + $children[0]['SUM(no_children)'] + $dependant[0]['SUM(no_dependant)'];
        if ($citizen > 0) {
            if ($value == 'men') {
                $result = ($men / $citizen) * 100;
            } elseif ($value == 'women') {
                $result = ($women / $citizen) * 100;
            } elseif ($value == 'elders') {
                $result = ($elders[0]['SUM(no_elder)'] / $citizen) * 100;
            } elseif ($value == 'children') {
                $result = ($children[0]['SUM(no_children)'] / $citizen) * 100;
            } elseif ($value == 'dependant') {
                $result = ($dependant[0]['SUM(no_dependant)'] / $citizen) * 100;
            }
            if (!is_nan($result)) {
                return $result;
            } else {
                return 0;
            }
        }
    }

    function generateScheduleCEPI($study_id, $project_id, $cid, $enrollment_date, $v_point, $status, $comments)
    {
        $x = $v_point;
        $arr = array();
        $y = 0;
        $vg = 'V1';
        $nxt_visit = $enrollment_date;
        $vty = 'Clinic';
        $vc = 'S1';
        $vm = 'Screening';
        $lw = 0;
        $hw = 0;
        $schedule = 'Scheduled';
        $status = 'c';
        $sq = 0;

        if ($x == 1) {
            $nxt_visit = date('Y-m-d', strtotime($nxt_visit));
            $this->createRecord('visit', array('study_id' => $study_id, 'project_id' => $project_id, 'visit_code' => $vc, 'visit_name' => $vm, 'visit_date' => $nxt_visit, 'expected_date' => $nxt_visit, 'comments' => $comments, 'visit_type' => $vty, 'schedule' => $schedule, 'window1' => $lw, 'window2' => $hw, 'patient_id' => $cid, 'seq_no' => 0, 'status' => 1, 'visit_status' => 0, 'staff_id' => $this->data()->id, 'update_id' => $this->data()->id, 'site_id' => $this->data()->site_id, 'create_on' => date('Y-m-d H:i:s'), 'update_on' => date('Y-m-d H:i:s')));
        }
        while ($x <= 30) {
            $sq++;
            if ($x <= 30) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 1 days'));
                $y++;
                $vc = 'D' . $y;
                $vm = 'Day ' . $y;
                if ($y == 1) {
                    $vty = 'Clinic';
                    $lw = 0;
                    $hw = 0;
                } else {
                    $vty = 'Call';
                    $lw = 0;
                    $hw = 0;
                }
                $this->createRecord('visit', array('study_id' => $study_id, 'project_id' => $project_id, 'visit_code' => $vc, 'visit_name' => $vm, 'visit_date' => $nxt_visit, 'expected_date' => '', 'comments' => $comments, 'visit_type' => $vty, 'schedule' => $schedule, 'window1' => $lw, 'window2' => $hw, 'patient_id' => $cid, 'seq_no' => $sq, 'visit_status' => 0, 'status' => 1, 'staff_id' => $this->data()->id, 'update_id' => $this->data()->id, 'site_id' => $this->data()->site_id, 'create_on' => date('Y-m-d H:i:s'), 'update_on' => date('Y-m-d H:i:s')));
                $x++;
            } elseif ($x == 5) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 2 days'));
                $x += 3;
                $y = 5;
                $vc = 'D' . $y;
                $vm = 'Day ' . $y;
                $vty = 'Clinic';
                $lw = 0;
                $hw = 0;
                $this->createRecord('visit', array('study_id' => $study_id, 'project_id' => $project_id, 'visit_code' => $vc, 'visit_name' => $vm, 'visit_date' => $nxt_visit, 'expected_date' => '', 'comments' => $comments, 'visit_type' => $vty, 'schedule' => $schedule, 'window1' => $lw, 'window2' => $hw, 'patient_id' => $cid, 'seq_no' => $sq, 'visit_status' => 0, 'status' => 1, 'staff_id' => $this->data()->id, 'update_id' => $this->data()->id, 'site_id' => $this->data()->site_id, 'create_on' => date('Y-m-d H:i:s'), 'update_on' => date('Y-m-d H:i:s')));
            } elseif ($x == 8) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 3 days'));
                $x += 3;
                $y = 5;
                $vc = 'D' . $y;
                $vm = 'Day ' . $y;
                $vty = 'Clinic';
                $lw = 0;
                $hw = 0;
                $this->createRecord('visit', array('study_id' => $study_id, 'project_id' => $project_id, 'visit_code' => $vc, 'visit_name' => $vm, 'visit_date' => $nxt_visit, 'expected_date' => '', 'comments' => $comments, 'visit_type' => $vty, 'schedule' => $schedule, 'window1' => $lw, 'window2' => $hw, 'patient_id' => $cid, 'seq_no' => $sq, 'visit_status' => 0, 'status' => 1, 'staff_id' => $this->data()->id, 'update_id' => $this->data()->id, 'site_id' => $this->data()->site_id, 'create_on' => date('Y-m-d H:i:s'), 'update_on' => date('Y-m-d H:i:s')));
            } elseif ($x == 15) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 7 days'));
                $x += 7;
                $y = 15;
                $vc = 'D' . $y;
                $vm = 'Day ' . $y;
                $vty = 'Clinic';
                $lw = 0;
                $hw = 0;
                $this->createRecord('visit', array('study_id' => $study_id, 'project_id' => $project_id, 'visit_code' => $vc, 'visit_name' => $vm, 'visit_date' => $nxt_visit, 'expected_date' => '', 'comments' => $comments, 'visit_type' => $vty, 'schedule' => $schedule, 'window1' => $lw, 'window2' => $hw, 'patient_id' => $cid, 'seq_no' => $sq, 'visit_status' => 0, 'status' => 1, 'staff_id' => $this->data()->id, 'update_id' => $this->data()->id, 'site_id' => $this->data()->site_id, 'create_on' => date('Y-m-d H:i:s'), 'update_on' => date('Y-m-d H:i:s')));
            } elseif ($x == 22) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 8 days'));
                $x += 8;
                $y = 22;
                $vc = 'D' . $y;
                $vm = 'Day ' . $y;
                $vty = 'Clinic';
                $lw = 0;
                $hw = 0;
                $this->createRecord('visit', array('study_id' => $study_id, 'project_id' => $project_id, 'visit_code' => $vc, 'visit_name' => $vm, 'visit_date' => $nxt_visit, 'expected_date' => '', 'comments' => $comments, 'visit_type' => $vty, 'schedule' => $schedule, 'window1' => $lw, 'window2' => $hw, 'patient_id' => $cid, 'seq_no' => $sq, 'visit_status' => 0, 'status' => 1, 'staff_id' => $this->data()->id, 'update_id' => $this->data()->id, 'site_id' => $this->data()->site_id, 'create_on' => date('Y-m-d H:i:s'), 'update_on' => date('Y-m-d H:i:s')));
            } elseif ($x == 30) {
                break;
            }
        }
    }

    function updateScheduleCEPI($study_id, $project_id, $cid, $enrollment_date, $v_point, $status, $comments)
    {

        if ($v_point == 1) {
            $visit = 'V' . $v_point;
            $this->deleteRecord('visit', 'client_id', $cid);
            $this->generateScheduleCEPI($study_id, $project_id, $cid, $enrollment_date, $v_point, $status, $comments);
        } elseif ($v_point == 2) {
            $visit = 'V2';
            $v_p = 14;
            foreach ($this->_override->getNews('visit', 'client_id', $cid, 'visit_group', $visit) as $vst) {
                $this->deleteRecord('visit', 'id', $vst['id']);
            }
            $visit = 'V3';
            foreach ($this->_override->getNews('visit', 'client_id', $cid, 'visit_group', $visit) as $vst) {
                $this->deleteRecord('visit', 'id', $vst['id']);
            }
            $this->generateScheduleCEPI($study_id, $project_id, $cid, $enrollment_date, $v_point, $status, $comments);
        } elseif ($cid == 3) {
            $visit = 'V3';
            $v_p = 42;
            foreach ($this->_override->getNews('visit', 'client_id', $cid, 'visit_group', $visit) as $vst) {
                $this->deleteRecord('visit', 'id', $vst['id']);
            }
            $this->generateScheduleCEPI($study_id, $project_id, $cid, $enrollment_date, $v_point, $status, $comments);
        }
        $this->updateRecord('clients', array('pt_group' => $project_id), $cid);
    }

    function generateScheduleNotDelayedVac080($study_name, $pid, $date, $v_point, $status, $study_group)
    {
        // $this->updateRecord('clients', array('pt_group' => $study_group), $pid);
        $x = $v_point;
        $arr = array();
        $y = 0;
        $vg = 'V1';
        $nxt_visit = $date;
        $vty = 'Clinic';
        $vc = 'V1';
        $lw = 0;
        $hw = 0;
        if ($x == 1) {
            $nxt_visit = date('Y-m-d', strtotime($nxt_visit));
            $pre_vac1 = date('Y-m-d', strtotime($nxt_visit . ' - 1 days'));
            $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => 'Pre-Vac1', 'visit_group' => $vg, 'visit_date' => $pre_vac1, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
        }
        while ($x < 730) {
            if ($x <= 7) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 1 days'));
                $y++;
                $vc = 'V1 + ' . $y;
                if ($y == 2 || $y == 7) {
                    $vty = 'Clinic';
                    $lw = 1;
                    $hw = 1;
                } else {
                    $vty = 'Home';
                    $lw = 0;
                    $hw = 0;
                }
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $x++;
            } elseif ($x == 8) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 7 days'));
                $x += 6;
                $y = 14;
                $vc = 'V1 + ' . $y;
                $vty = 'Clinic';
                $lw = 1;
                $hw = 3;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $y = 14;
                $vg = 'V2';
            } elseif ($x == 14) {
                $y = 14;
                $vg = 'V2';
                if ($status == 'c') {
                    $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 14 days'));
                } elseif ($status == 'u') {
                    $status = 'c';
                    $nxt_visit = date('Y-m-d', strtotime($date));
                }
                $x += 14;
                $vty = 'Clinic';
                if ($y == 14) {
                    $vc = 'V2';
                    $lw = 1;
                    $hw = 3;
                } else {
                    $vc = 'V2 + ' . $y;
                    $lw = 0;
                    $hw = 0;
                }
                $pre_vac2 = date('Y-m-d', strtotime($nxt_visit . ' - 1 days'));
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => 'Pre-Vac2', 'visit_group' => $vg, 'visit_date' => $pre_vac2, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'schedule' => 'Scheduled', 'visit_type' => $vty, 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $y = 1;
            } elseif ($x >= 28 && $x < 35) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 1 days'));
                $x++;
                if ($x == 28) {
                    $vc = 'V2';
                    $y = 0;
                    $vty = 'Clinic';
                    $lw = 7;
                    $hw = 14;
                } else {
                    $vc = 'V2 + ' . $y;
                    $vty = 'Home';
                    $lw = 0;
                    $hw = 0;
                }
                if ($y == 2 || $y == 7) {
                    $vty = 'Clinic';
                }
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $y++;
            } elseif ($x == 35) {
                $y = 14;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 7 days'));
                $x += 7;
                $vc = 'V2 + ' . $y;
                $vty = 'Clinic';
                $lw = 0;
                $hw = 0;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            } elseif ($x == 42) {
                $y = 1;
                $vg = 'V3';
                if ($status == 'c') {
                    $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 14 days'));
                } elseif ($status == 'u') {
                    $status = 'c';
                    $nxt_visit = date('Y-m-d', strtotime($date));
                }
                $x += 14;
                $vc = 'V3';
                $vty = 'Clinic';
                $lw = 7;
                $hw = 14;
                $pre_vac3 = date('Y-m-d', strtotime($nxt_visit . ' - 1 days'));
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => 'Pre-Vac3', 'visit_group' => $vg, 'visit_date' => $pre_vac3, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            } elseif ($x >= 56 && $x < 63) {
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 1 days'));
                $x++;
                if ($y == 56) {
                    $vc = 'V3';
                    $y = 0;
                } else {
                    $vc = 'V3 + ' . $y;
                }
                if ($y == 2 || $y == 7) {
                    $vty = 'Clinic';
                } else {
                    $vty = 'Home';
                }
                $lw = 0;
                $hw = 0;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $y++;
            } elseif ($x == 63) {
                $y = 14;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 7 days'));
                $x += 7;
                $vc = 'V3 + ' . $y;
                $vty = 'Clinic';
                $lw = 1;
                $hw = 3;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
                $y++;
            } elseif ($x == 70) {
                $y = 28;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 14 days'));
                $x += 14;
                $vc = 'V3 + ' . $y;
                $vty = 'Clinic';
                $lw = 7;
                $hw = 2;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            } elseif ($x == 84) {
                $y = 84;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 56 days'));
                $x += 56;
                $vc = 'V3 + ' . $y;
                $vty = 'Clinic';
                $lw = 7;
                $hw = 7;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            } elseif ($x == 140) {
                $y = 112;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 28 days'));
                $x += 28;
                $vc = 'V3 + ' . $y;
                $vty = 'Clinic';
                $lw = 28;
                $hw = 28;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            } elseif ($x == 168) {
                $y = 309;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 197 days'));
                $x += 197;
                $vc = 'V3 + ' . $y;
                $vty = 'Clinic';
                $lw = 28;
                $hw = 28;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            } elseif ($x == 365) {
                $y = 674;
                $nxt_visit = date('Y-m-d', strtotime($nxt_visit . ' + 365 days'));
                $x += 365;
                $vc = 'V3 + ' . $y;
                $vty = 'Clinic';
                $lw = 28;
                $hw = 28;
                $this->createRecord('visit', array('project_id' => $study_name, 'visit_code' => $vc, 'visit_group' => $vg, 'visit_date' => $nxt_visit, 'visit_type' => $vty, 'schedule' => 'Scheduled', 'l_window' => $lw, 'h_window' => $hw, 'client_id' => $pid, 'status' => 0, 'staff_id' => $this->data()->id));
            } elseif ($x == 730) {
                break;
            }
        }
    }

    function updateScheduleNotDelayedVac080($study_name, $pid, $date, $day, $study_group)
    {

        if ($day == 1) {
            $visit = 'V' . $day;
            $this->deleteRecord('visit', 'client_id', $pid);
            $this->generateScheduleNotDelayedVac080($study_name, $pid, $date, $day, 'c', $study_group);
        } elseif ($day == 2) {
            $visit = 'V2';
            $v_p = 14;
            foreach ($this->_override->getNews('visit', 'client_id', $pid, 'visit_group', $visit) as $vst) {
                $this->deleteRecord('visit', 'id', $vst['id']);
            }
            $visit = 'V3';
            foreach ($this->_override->getNews('visit', 'client_id', $pid, 'visit_group', $visit) as $vst) {
                $this->deleteRecord('visit', 'id', $vst['id']);
            }
            $this->generateScheduleNotDelayedVac080($study_name, $pid, $date, $v_p, 'u', $study_group);
        } elseif ($day == 3) {
            $visit = 'V3';
            $v_p = 42;
            foreach ($this->_override->getNews('visit', 'client_id', $pid, 'visit_group', $visit) as $vst) {
                $this->deleteRecord('visit', 'id', $vst['id']);
            }
            $this->generateScheduleNotDelayedVac080($study_name, $pid, $date, $v_p, 'u', $study_group);
        }
        $this->updateRecord('clients', array('pt_group' => $study_group), $pid);
    }
}
