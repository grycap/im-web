<?php
require_once 'http.php';
require_once 'cred.php';

class IMRest
{
    static public function connect($host, $port, $path = "/")
    {
        return new self($host, $port, $path);
    }

    public function __construct($host = "localhost", $port = 8800, $path = "/")
    {
        $this->_host     = $host;
        $this->_port     = $port;
        if (substr($path, -1) == "/") {
            $path = substr($path, 0, -1);
        }
        $this->_path     = $path;
    }

    public function get_auth_data()
    {

        $fields = array("id","type","host","username","password","proxy","token_type","project","public_key",
        "private_key", "certificate", "tenant", "project", "subscription_id", "auth_version", "domain", 
        "service_region", "base_url");
        $user = $_SESSION['user'];
        $password = $_SESSION['password'];
        // esto por si usamos la autorizacion del servidor web
        //$user = $_SERVER['PHP_AUTH_USER']
        //$password = $_SERVER['PHP_AUTH_PW']
        
        // Same values as defined in IM REST API
        // Combination of chars used to separate the lines in the AUTH header
        $AUTH_LINE_SEPARATOR = '\\n';
        // Combination of chars used to separate the lines inside the auth data (i.e. in a certificate)
        $AUTH_NEW_LINE_SEPARATOR = '\\\\n';

        $auth = null;
        $creds = get_credentials($user, $password);
        if (!is_null($creds)) {
            $auth = "";
            foreach ($creds as $cred) {
                if ($cred['enabled']) {
                    if (isset($_SESSION['user_token']) and $cred['type'] == "InfrastructureManager") {
                        if (!is_null($cred['id'])) {
                            $auth = "id = " . $cred['id'] . "; ";
                        }
                        $auth = $auth . "type = InfrastructureManager; token = " . $_SESSION['user_token'] . " ;";
                    } else {
                        foreach ($fields as $field) {
                            if (!is_null($cred[$field]) && strlen(trim($cred[$field])) > 0) {
                                $value = str_replace("\n", $AUTH_NEW_LINE_SEPARATOR, $cred[$field]);
                                if ($field == "certificate") {
                                    $auth = $auth . "password = " . $value . "; ";
                                } elseif ($field == "proxy" and $cred['type'] == "FogBow"){
                                    $auth = $auth . "token = " . $value . "; ";
                                } elseif ($field == "service_region" and $cred['type'] == "Orange"){
                                    $auth = $auth . "region = " . $value . "; ";
                                } else {
                                    $auth = $auth . $field ." = " . $value . "; ";
                                }
                            }
                        }
                        if (isset($_SESSION['user_token']) and $cred['type'] == "OpenNebula") {
                            $auth = $auth . "token = " . $_SESSION['user_token'] . "; ";
                        } elseif (isset($_SESSION['user_token']) and $cred['type'] == "OpenStack") {
                            if (is_null($cred['password']) || strlen(trim($cred['password'])) == 0) {
                                $auth = $auth . "password = " . $_SESSION['user_token'] . "; ";
                            }
                        }
                    }
                    $auth = substr($auth, 0, strlen($auth)-2) . $AUTH_LINE_SEPARATOR;
                }
            }
        }

        return $auth;
    }

    public function GetErrorMessage($output)
    {
        return $output;
    }

    public function BasicRESTCall($verb, $path, $extra_headers=array(), $params=array())
    {
        include 'config.php';
        $auth = $this->get_auth_data();
        $headers = array("Authorization:" . $auth);
        $headers = array_merge($headers, $extra_headers);

        if ($im_use_ssl) {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }
        
        try {
            $res = Http::connect($this->_host, $this->_port, $protocol)
            ->setHeaders($headers)
            ->exec($verb, $this->_path . $path, $params);
                
            $status = $res->getStatus();
            $output = $res->getOutput();
        } catch (Exception $e) {
            $status = 600;
            $output = "Exception: " . $e->getMessage();
        }

        $res = $output;
        if ($status != 200) {
            $res = 'Error: Code: ' . strval($status) . '. ' . $this->GetErrorMessage($output);
        }

        return new Http_response($status, $res);
    }

    public function GetInfrastructureList()
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("GET", '/infrastructures', $headers);

        if ($res->getStatus() != 200) {
            return $res->getOutput();
        } else {
            $inf_urls = explode("\n", $res->getOutput());
            $inf_ids = array();
            foreach ($inf_urls as $inf_url) {
                $inf_id = trim(basename(parse_url($inf_url, PHP_URL_PATH)));
                if (strlen($inf_id) > 0) {
                    $inf_ids[] = $inf_id;
                }
            }
            return $inf_ids;
        }
    }

    public function GetInfrastructureInfo($id)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("GET", '/infrastructures/'.$id, $headers);

        if ($res->getStatus() != 200) {
            return 'Error: Code: ' . strval($res->getStatus()) . '. ' . GetErrorMessage($output);
        } else {
            $vm_urls = explode("\n", $res->getOutput());
            $vm_ids = array();
            foreach ($vm_urls as $vm_url) {
                $vm_id = trim(basename(parse_url($vm_url, PHP_URL_PATH)));
                if (strlen($vm_id) > 0) {
                    $vm_ids[] = $vm_id;
                }
            }
            return $vm_ids;
        }
    }

    public function GetInfrastructureState($id)
    {
        $headers = array('Accept: application/json');
        $res = $this->BasicRESTCall("GET", '/infrastructures/'.$id.'/state', $headers);

        if ($res->getStatus() != 200) {
            return $res->getOutput();
        } else {
            return json_decode($res->getOutput(), true)["state"];
        }
    }

    public function DestroyInfrastructure($id, $async)
    {
        $headers = array('Accept: text/*');
        if ($async) {
            $params = array("async" => "yes");
            $res = $this->BasicRESTCall("DELETE", '/infrastructures/'.$id, $headers, $params);
        } else {
            $res = $this->BasicRESTCall("DELETE", '/infrastructures/'.$id, $headers);
        }        
        
        if ($res->getStatus() != 200) {
            return $res->getOutput();
        } else {
            return "";
        }
    }

    public function GetVMInfo($inf_id, $vm_id)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("GET", '/infrastructures/' . $inf_id . '/vms/' . $vm_id, $headers);
        return $res->getOutput();
    }

    public function GetInfrastructureContMsg($id)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("GET", '/infrastructures/'.$id.'/contmsg', $headers);
        return $res->getOutput();
    }

    public function GetVMProperty($inf_id, $vm_id, $property)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("GET", '/infrastructures/' . $inf_id . '/vms/' . $vm_id . "/" . $property, $headers);
        return $res->getOutput();
    }

    public function GetVMContMsg($inf_id, $vm_id)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("GET", '/infrastructures/' . $inf_id . '/vms/' . $vm_id . "/contmsg", $headers);
        return $res->getOutput();
    }

    public function GetContentType($content)
    {
        if (strpos($content, "tosca_definitions_version") !== false) {
            return 'text/yaml';
        } elseif (substr(trim($content), 0, 1) == "[") {
            return 'application/json';
        } else {
            return 'text/plain';
        }
    }

    public function CreateInfrastructure($radl, $async)
    {
        $headers = array('Accept: text/*', 'Content-Length: ' . strlen($radl), 'Content-Type: ' . $this->GetContentType($radl));
        if ($async) {
            $res = $this->BasicRESTCall("POST", '/infrastructures?async=yes', $headers, $radl);
        } else {
            $res = $this->BasicRESTCall("POST", '/infrastructures', $headers, $radl);
        }
        return $res->getOutput();
    }

    public function StartVM($inf_id, $vm_id)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("PUT", '/infrastructures/' . $inf_id . '/vms/' . $vm_id . "/start", $headers);
        return $res->getOutput();
    }

    public function StopVM($inf_id, $vm_id)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("PUT", '/infrastructures/' . $inf_id . '/vms/' . $vm_id . "/stop", $headers);
        return $res->getOutput();
    }

    public function RebootVM($inf_id, $vm_id)
    {
    	$headers = array('Accept: text/*');
    	$res = $this->BasicRESTCall("PUT", '/infrastructures/' . $inf_id . '/vms/' . $vm_id . "/reboot", $headers);
    	return $res->getOutput();
    }

    public function AddResource($inf_id, $radl)
    {
        $headers = array('Accept: text/*', 'Content-Length: ' . strlen($radl), 'Content-Type: ' . $this->GetContentType($radl));
        $res = $this->BasicRESTCall("POST", '/infrastructures/' . $inf_id, $headers, $radl);
        return $res->getOutput();
    }

    public function RemoveResource($inf_id, $vm_id)
    {
        $headers = array('Accept: text/*');
        $res = $this->BasicRESTCall("DELETE", '/infrastructures/' . $inf_id . '/vms/' . $vm_id, $headers);
        return $res->getOutput();
    }

    public function Reconfigure($inf_id, $radl)
    {
        $headers = array('Accept: text/*', 'Content-Type: text/plain', 'Content-Length: ' . strlen($radl));
        $res = $this->BasicRESTCall("PUT", '/infrastructures/' . $inf_id . '/reconfigure', $headers, $radl);
        return $res->getOutput();
    }

    public function GetOutputs($inf_id)
    {
        $headers = array('Accept: application/json');
        $res = $this->BasicRESTCall("GET", '/infrastructures/' . $inf_id . '/outputs', $headers);
        return json_decode($res->getOutput(), true)["outputs"];
    }

}
?>
