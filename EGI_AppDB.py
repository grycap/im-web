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
import http.client
import xmltodict
from urllib.parse import urlparse

__copyright__ = "Copyright (c) 2016 EGI Foundation"
__license__ = "Apache Licence v2.0"

def get_vo_list():
    vos = []
    data = appdb_call('/rest/1.0/vos')
    for vo in data['appdb:appdb']['vo:vo']:
        vos.append(vo['@name'])
    return vos

def check_supported_VOs(data, vo):
    """
    Check if there are an image of the supported VO
    """
    if 'provider:image' in data['appdb:appdb']['virtualization:provider']:
        images = data['appdb:appdb']['virtualization:provider']['provider:image']
        if not isinstance(images, list):
            images = [images]
        for os_tpl in images:
            if '@voname' in os_tpl and vo in os_tpl['@voname']:
                return True
    return False

def appdb_call(c):
    conn = http.client.HTTPSConnection('appdb.egi.eu')
    conn.request("GET", c)
    data = conn.getresponse().read().decode()
    conn.close()
    data.replace('\n', '')
    return xmltodict.parse(data)

def get_sites(vo):
    data = appdb_call('/rest/1.0/sites?flt=%%2B%%3Dvo.name:%s&%%2B%%3Dsite.supports:1' % vo)
    providersID = []
    if 'appdb:site' in data['appdb:appdb']:
        if isinstance(data['appdb:appdb']['appdb:site'], list):
            sites = data['appdb:appdb']['appdb:site']
        else:
            sites = [data['appdb:appdb']['appdb:site']]
        for site in sites:
            if  isinstance(site['site:service'], list):
                for service in site['site:service']:
                    providersID.append(service['@id'])
            else:
                providersID.append(site['site:service']['@id'])

    # Get provider metadata
    endpoints = []
    for ID in providersID:
        data = appdb_call('/rest/1.0/va_providers/%s' % ID)
        if check_supported_VOs(data, vo):
            if ('provider:url' in data['appdb:appdb']['virtualization:provider'] and
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
