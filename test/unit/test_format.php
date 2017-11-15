<?php

use PHPUnit\Framework\TestCase;

final class FormatTest extends TestCase
{

    public function testFormatState()
    {
        $res = formatState("running");
        $this->assertEquals($res, "<span style='color:green'>configuring</span>");
        $res = formatState("unknown");
        $this->assertEquals($res, "<span style='color:orange'>unknown</span>");
        $res = formatState("failed");
        $this->assertEquals($res, "<span style='color:red'>failed</span>");
        $res = formatState("configured");
        $this->assertEquals($res, "<span style='color:green'>configured</span>");
    }

    public function testFormatCloud()
    {
        $tokens = array("provider.type"=>"EC2","provider.host"=>"host");
        $res = formatCloud($tokens);
        $this->assertEquals($res, "EC2");

        $tokens = array("provider.type"=>"OpenNebula","provider.host"=>"host","provider.port"=>"2633");
        $res = formatCloud($tokens);
        $this->assertEquals($res, "OpenNebula<br>host:2633<br>");
    }

    public function testFormatIPs()
    {
        $tokens = array("net_interface.0.ip"=>"10.0.0.1", "net_interface.1.ip"=>"10.0.0.2");
        $res = formatIPs($tokens);
        $this->assertEquals("0 => 10.0.0.1<br>1 => 10.0.0.2<br>", $res);
    }

    public function testFormatApps()
    {
        $res = formatAplication("(name = 'tomcat' and version = '7.0' and path = '/var/lib/tomcat')");
        $this->assertEquals("tomcat v. 7.0 (/var/lib/tomcat)", $res);
    }

    public function testFormatOutPorts()
    {
        $radl = "network publica (outbound = 'yes' and outports = '8080-8080,22-22')\n";
        $outports = getOutPorts($radl);
        $this->assertEquals(array("8080"=>"8080","22"=>"22"),$outports);
        $res = formatOutPorts($outports);
        $this->assertEquals("8080 => 8080<br>\n22 => 22<br>\n", $res);
    }

    public function testFormatRADL()
    {
        $radl = "" .
        "network publica (outbound = 'yes') " .
        "network privada ( ) " .
        "system front ( " .
        "cpu.arch='x86_64' and " .
        "cpu.count>=1 and " .
        "memory.size>=512m and " .
        "net_interface.1.connection = 'publica' and " .
        "net_interface.0.connection = 'privada' and " .
        "net_interface.0.dns_name = 'front' and " .
        "disk.0.os.flavour='centos' and " .
        "disk.0.os.version>='7' and " .
        "disk.0.os.name = 'linux' and " .
        "disk.0.applications contains (name = 'ansible.modules.grycap.octave') and " .
        "disk.0.applications contains (name = 'gmetad') and " .
        "disk.0.os.credentials.private_key = 'priv' and " .
        "disk.1.size=1GB and " .
        "disk.1.device='hdb' and " .
        "disk.1.fstype='ext4' and " .
        "disk.1.mount_path='/mnt/disk'" .
        ")";

        $expected_res = "<tr>
<td>cpu.arch='x86_64'</td>
<td><pre></pre></td>
</tr>
<tr>
<td>cpu.count>=1</td>
<td><pre></pre></td>
</tr>
<tr>
<td>memory.size>=512m</td>
<td><pre></pre></td>
</tr>
<tr>
<td>disk.0.os.flavour='centos'</td>
<td><pre></pre></td>
</tr>
<tr>
<td>disk.0.os.version>='7'</td>
<td><pre></pre></td>
</tr>
<tr>
<td>disk.0.os.name</td>
<td><pre>linux</pre></td>
</tr>
<tr>
<td>disk.0.applications</td>
<td><pre>gmetad</pre></td>
</tr>
<tr>
<td>disk.0.os.credentials.private_key</td>
<td><textarea id='private_key_value' name='private_key_value' style='display:none;'>priv</textarea><a id='export' class='download' href='#'>Download</a><script>
    function createDownloadLink(anchorSelector, str, fileName){
        anchor = document.getElementById(anchorSelector)
        if(window.navigator.msSaveOrOpenBlob) {
            var fileData = [str];
            blobObject = new Blob(fileData);
            anchor.onclick = function(){
                window.navigator.msSaveOrOpenBlob(blobObject, fileName);
            }
        } else {
			var url = \"data:Application/octet-stream,\" + encodeURIComponent(str);
            anchor.download = fileName;
            anchor.href = url;
        }
    }
                    	
    var dataToDownload = document.getElementById(\"private_key_value\").value;
    createDownloadLink(\"export\",dataToDownload,\"key.pem\");
                    	
</script></td>
</tr>
<tr>
<td>disk.1.size=1GB</td>
<td><pre></pre></td>
</tr>
<tr>
<td>disk.1.dev</td>
<td><pre></pre></td>
</tr>
";

        $res = parseRADL($radl);
        $res = formatRADL($res);
        $this->assertEquals($expected_res, $res);
    }
    
    
}
?>