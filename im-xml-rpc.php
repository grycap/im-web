<?php
require_once 'xmlrpc.inc';
require_once 'xmlrpcs.inc';
require_once 'xmlrpc_wrappers.inc';
require_once 'cred.php';

class IMXML
{

    static public function connect($host, $port, $method)
    {
        return new self($host, $port, $method);
    }

    public function __construct($host = "localhost", $port = 8800, $method = "http")
    {
        $this->_host     = $host;
        $this->_port     = $port;
        $this->_method     = $method;
    }

    public function get_auth_data()
    {

        $user = $_SESSION['user'];
        $password = $_SESSION['password'];
        $auth = null;
        $creds = get_credentials($user, $password);
        if (!is_null($creds)) {
            $auth = array();
            foreach ($creds as $cred) {
                if ($cred['enabled']) {
                    $auth_cloud = array();
                    $auth_cloud['type'] = new xmlrpcval($cred['type']);
                    if (isset($_SESSION['user_token']) and $cred['type'] == "InfrastructureManager") {
                        $auth_cloud['token'] = new xmlrpcval($_SESSION['user_token']);
                    } elseif (isset($_SESSION['user_token']) and $cred['type'] == "OpenNebula") {
                        $auth_cloud['token'] = new xmlrpcval($_SESSION['user_token']);
                    } elseif (isset($_SESSION['user_token']) and $cred['type'] == "OpenStack") {
                        if (is_null($cred['password']) || strlen(trim($cred['password'])) == 0) {
                            $auth_cloud['password'] = new xmlrpcval($_SESSION['user_token']);
                        }
                    } else {
                        if (!is_null($cred['id']) && strlen(trim($cred['id'])) > 0) {
                            $auth_cloud['id'] = new xmlrpcval($cred['id']);
                        }
                        if (!is_null($cred['host']) && strlen(trim($cred['host'])) > 0) {
                            $auth_cloud['host'] = new xmlrpcval($cred['host']);
                        }
                        if (!is_null($cred['username']) && strlen(trim($cred['username'])) > 0) {
                            $auth_cloud['username'] = new xmlrpcval($cred['username']);
                        }
                        if (!is_null($cred['password']) && strlen(trim($cred['password'])) > 0) {
                            $auth_cloud['password'] = new xmlrpcval($cred['password']);
                        }
                        if (!is_null($cred['proxy']) && strlen(trim($cred['proxy'])) > 0) {
                            $auth_cloud['proxy'] = new xmlrpcval($cred['proxy']);
                        }
                        if (!is_null($cred['token_type']) && strlen(trim($cred['token_type'])) > 0) {
                            $auth_cloud['token_type'] = new xmlrpcval($cred['token_type']);
                        }
                        if (!is_null($cred['project']) && strlen(trim($cred['project'])) > 0) {
                            $auth_cloud['project'] = new xmlrpcval($cred['project']);
                        }
                        if (!is_null($cred['public_key']) && strlen(trim($cred['public_key'])) > 0) {
                            $auth_cloud['public_key'] = new xmlrpcval($cred['public_key']);
                        }
                        if (!is_null($cred['private_key']) && strlen(trim($cred['private_key'])) > 0) {
                            $auth_cloud['private_key'] = new xmlrpcval($cred['private_key']);
                        }
                        if (!is_null($cred['certificate']) && strlen(trim($cred['certificate'])) > 0) {
                            $auth_cloud['password'] = new xmlrpcval($cred['certificate']);
                        }
                        if (!is_null($cred['tenant']) && strlen(trim($cred['tenant'])) > 0) {
                            $auth_cloud['tenant'] = new xmlrpcval($cred['tenant']);
                        }
                        if (!is_null($cred['project']) && strlen(trim($cred['project'])) > 0) {
                            $auth_cloud['project'] = new xmlrpcval($cred['project']);
                        }
                        if (!is_null($cred['subscription_id']) && strlen(trim($cred['subscription_id'])) > 0) {
                            $auth_cloud['subscription_id'] = new xmlrpcval($cred['subscription_id']);
                        }
                        if (!is_null($cred['auth_version']) && strlen(trim($cred['auth_version'])) > 0) {
                            $auth_cloud['auth_version'] = new xmlrpcval($cred['auth_version']);
                        }
                        if (!is_null($cred['domain']) && strlen(trim($cred['domain'])) > 0) {
                            $auth_cloud['domain'] = new xmlrpcval($cred['domain']);
                        }
                        if (!is_null($cred['service_region']) && strlen(trim($cred['service_region'])) > 0) {
                            $auth_cloud['service_region'] = new xmlrpcval($cred['service_region']);
                        }
                        if (!is_null($cred['base_url']) && strlen(trim($cred['base_url'])) > 0) {
                            $auth_cloud['base_url'] = new xmlrpcval($cred['base_url']);
                        }
                    }
                    $auth[] = new xmlrpcval($auth_cloud, "struct");
                }
            }
        }
        
        return new xmlrpcval($auth, "array");
    }

    // helper function to make easier mocking
    public function send_xmlrpc_call($xmlrpc_msg)
    {
        $xmlrpc_client = new xmlrpc_client('/', $this->_host, $this->_port, $this->_method);
        return $xmlrpc_client->send($xmlrpc_msg);
    }

    public function GetInfrastructureList()
    {
        $auth = $this->get_auth_data();

        $xmlrpc_msg = new xmlrpcmsg('GetInfrastructureList', array($auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);
        
        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
            $success = $res[0];
            $list = $res[1];
            
        if ($success) {
            return $list;
        } else {
            return 'Error: ' . $list;
        }
    }

    public function CreateInfrastructure($radl, $async)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('CreateInfrastructure', array(new xmlrpcval($radl, "string"), new xmlrpcval($async, "boolean"), $auth));
        
        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);
        
        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
            $success = $res[0];
            $inf_id = $res[1];
            
        if ($success) {
            return $inf_id;
        } else {
            return 'Error: ' . $inf_id;
        }
    }

    public function DestroyInfrastructure($id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('DestroyInfrastructure', array(new xmlrpcval($id, "string"), $auth));
        
        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);
        
        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
            $success = $res[0];
            $inf_id = $res[1];
            
        if ($success) {
            return "";
        } else {
            return 'Error: ' . $inf_id;
        }
    }

    public function GetInfrastructureInfo($id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('GetInfrastructureInfo', array(new xmlrpcval($id, "string"), $auth));
        
        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);
        
        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
            $success = $res[0];
            $inf_info = $res[1];
            
        if ($success) {
            return $inf_info;
        } else {
            return 'Error';
        }
    }

    public function GetInfrastructureContMsg($id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('GetInfrastructureContMsg', array(new xmlrpcval($id, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $cont_msg = $res[1];

        if ($success) {
            return $cont_msg;
        } else {
            return 'Error';
        }
    }

    public function GetVMInfo($inf_id, $vm_id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('GetVMInfo', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($vm_id, "string"), $auth));
        
        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);
        
        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
            $success = $res[0];
            $info = $res[1];
            
        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function GetVMProperty($inf_id, $vm_id, $property)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('GetVMProperty', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($vm_id, "string"), new xmlrpcval($property, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $info = $res[1];

        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function GetVMContMsg($inf_id, $vm_id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('GetVMContMsg', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($vm_id, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $info = $res[1];

        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function StartVM($inf_id, $vm_id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('StartVM', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($vm_id, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $info = $res[1];

        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function StopVM($inf_id, $vm_id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('StopVM', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($vm_id, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $info = $res[1];

        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function AddResource($inf_id, $radl)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('AddResource', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($radl, "string"), $auth));
        
        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);
        
        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
            $success = $res[0];
            $info = $res[1];
            
        if ($success) {
            return "OK";
        } else {
            return 'Error';
        }
    }

    public function RemoveResource($inf_id, $vm_list)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('RemoveResource', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($vm_list, "string"), $auth));
        
        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);
        
        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
            $success = $res[0];
            $info = $res[1];
            
        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function Reconfigure($inf_id, $radl)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('Reconfigure', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($radl, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $info = $res[1];

        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function ExportInfrastructure($inf_id, $delete)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('ExportInfrastructure', array(new xmlrpcval($inf_id, "string"), new xmlrpcval($delete, "boolean"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $info = $res[1];

        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function ImportInfrastructure($inf_str)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('ImportInfrastructure', array(new xmlrpcval($inf_str, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
            $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
        $success = $res[0];
        $info = $res[1];

        if ($success) {
            return $info;
        } else {
            return 'Error';
        }
    }

    public function GetInfrastructureState($id)
    {
        $auth = $this->get_auth_data();
        
        $xmlrpc_msg = new xmlrpcmsg('GetInfrastructureState', array(new xmlrpcval($id, "string"), $auth));

        $xmlrpc_resp = $this->send_xmlrpc_call($xmlrpc_msg);

        if ($xmlrpc_resp->faultCode()) {
            return 'Error: ' . $xmlrpc_resp->faultString();
        } else {
                $res = php_xmlrpc_decode($xmlrpc_resp->value());
        }
                $success = $res[0];
                $state = $res[1];

        if ($success) {
            return $state;
        } else {
            return 'Error';
        }
    }
}
?>
