#!/usr/bin/env python

#
#  Copyright 2016 EGI Foundation
#
#  Licensed under the Apache License, Version 2.0 (the "License");
#  you may not use this file except in compliance with the License.
#  You may obtain a copy of the License at
#
#      http://www.apache.org/licenses/LICENSE-2.0
#
#  Unless required by applicable law or agreed to in writing, software
#  distributed under the License is distributed on an "AS IS" BASIS,
#  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#  See the License for the specific language governing permissions and
#  limitations under the License.
#
#

import sys
import httplib
import xmltodict
from urlparse import urlparse

__copyright__ = "Copyright (c) 2016 EGI Foundation"
__license__ = "Apache Licence v2.0"

def get_vo_list():
    vos = []
    data = appdb_call('/rest/1.0/vos')
    for vo in data['appdb:appdb']['vo:vo']:
        vos.append(vo['@name'])
    return vos

def check_supported_VOs(id):
    try:
        data = appdb_call('/rest/1.0/va_providers/%s' %id)
        value = 0
        for os_tpl in data['appdb:appdb']['virtualization:provider']['provider:image']:
            try:
                if vo in os_tpl['@voname']:
                    value = 1
            except:
                pass

        return value
    except:
        return 0

def appdb_call(c):
    conn = httplib.HTTPSConnection('appdb.egi.eu')
    conn.request("GET", c)
    data = conn.getresponse().read()
    conn.close()
    data.replace('\n', '')
    return xmltodict.parse(data)

def get_sites(vo):
    data = appdb_call('/rest/1.0/sites?flt=%%2B%%3Dvo.name:%s&%%2B%%3Dsite.supports:1' % vo)
    providersID = []
    if 'appdb:site' in data['appdb:appdb']:
        for site in data['appdb:appdb']['appdb:site']:
            if  type(site['site:service']) == type([]):
                for service in site['site:service']:
                    providersID.append(service['@id'])
            else:
                providersID.append(site['site:service']['@id'])

    # Get provider metadata
    endpoints = []
    for ID in providersID:
        if check_supported_VOs(ID):
            data = appdb_call('/rest/1.0/va_providers/%s' % ID)
            if (data['appdb:appdb']['virtualization:provider'].has_key('provider:endpoint_url') and
                    data['appdb:appdb']['virtualization:provider']['@service_type'] == 'org.openstack.nova'):
                provider_name = data['appdb:appdb']['virtualization:provider']['provider:name']
                provider_endpoint_url = data['appdb:appdb']['virtualization:provider']['provider:url']
                url = urlparse(provider_endpoint_url)
                endpoints.append(provider_name + ";" + "%s://%s" % url[0:2])

    return endpoints

if __name__ == "__main__":
    vo = "fedcloud.egi.eu"
    option = "vos"
    if len(sys.argv) > 2:
        vo = sys.argv[2]
        option = sys.argv[1]
    elif len(sys.argv) > 1:
        option = sys.argv[1]

    if option == "vos":
        for vo in get_vo_list():
            print(vo)
    elif option == "sites":
        for site in get_sites(vo):
            if site.endswith("/"):
                print(site[:-1])
            else:
                print(site)
    else:
        print("Incorrect Option.")