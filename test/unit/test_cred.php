<?php

use PHPUnit\Framework\TestCase;

final class CredTest extends TestCase
{

    public function testManageCreds()
    {
        $user = uniqid();
        $res = insert_credential($user, "cred", "InfrastructureManager", "host", "user", "impass",
                                "token_type", "project", "proxy", "public_key", "private_key", "certificate",
                                "tenant", "subscription_id", "auth_version", "domain", "service_region", "base_url");
        $this->assertEquals($res, "");

        $res = get_credentials($user);
        $this->assertEquals("cred", $res[0]["id"]);

        $rowid = $res[0]["rowid"];
        $res = get_credential($rowid);
        $this->assertEquals("InfrastructureManager", $res["type"]);

        $res = edit_credential($rowid, "crednew", "InfrastructureManager", "host", "user", "impass",
            "token_type", "project", "proxy", "public_key", "private_key", "certificate",
            "tenant", "subscription_id", "auth_version", "domain", "service_region", "base_url");
        $this->assertEquals($res, "");

        $res = get_credential($rowid);
        $this->assertEquals("InfrastructureManager", $res["type"]);
        $this->assertEquals("crednew", $res["id"]);
        $this->assertEquals(true, $res["enabled"]);

        $res = enable_credential($rowid, 0);
        $this->assertEquals($res, "");
        $res = get_credential($rowid);
        $this->assertEquals(false, $res["enabled"]);

        $res = insert_credential($user, "cred2", "InfrastructureManager", "host", "user", "impass",
            "token_type", "project", "proxy", "public_key", "private_key", "certificate",
            "tenant", "subscription_id", "auth_version", "domain", "service_region", "base_url");
        $this->assertEquals($res, "");
        $res = change_order($rowid, $user, 0, 1);
        $this->assertEquals($res, "");
        $res = get_credential($rowid);
        $this->assertEquals(1, $res["ord"]);

        $res = delete_credential($rowid);
        $this->assertEquals($res, "");
        $res = get_credential($rowid);
        $this->assertEquals(NULL, $res);
    }
}
?>