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
        conn  =  httplib.HTTPSConnection('appdb.egi.eu')
        conn.request("GET", c)
        data = conn.getresponse().read()
        conn.close()
        data.replace('\n','')
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

def get_oss(endpoint):
    oss = []
    data = appdb_call('/rest/1.0/sites?flt=%%2B%%3Dvo.name:%s&%%2B%%3Dsite.supports:1' % vo)
    for site in data['appdb:appdb']['appdb:site']:
        if type(site['site:service']) == type([]):
            for service in site['site:service']:
                try:
                    va_data = appdb_call('/rest/1.0/va_providers/%s' % service['@id'])
                    if va_data['appdb:appdb']['virtualization:provider']['provider:name'] == endpoint:
                        for os_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:image']:
                            try:
                                if vo in os_tpl['@voname']:
                                    oss.append(os_tpl['@appname'] + ";" + os_tpl['@appcname'])
                            except:
                                continue
                except:
                    continue
        else:
            va_data = appdb_call('/rest/1.0/va_providers/%s' %site['site:service']['@id'])
            provider_name = va_data['appdb:appdb']['virtualization:provider']['provider:name']
            if provider_name == endpoint:
                for os_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:image']:
                    try:
                        if vo in os_tpl['@voname']:
                            oss.append(os_tpl['@appname'] + ";" + os_tpl['@appcname'])
                    except:
                        continue
    return oss
    
def get_instances(endpoint):
    instances = []
    data = appdb_call('/rest/1.0/sites?flt=%%2B%%3Dvo.name:%s&%%2B%%3Dsite.supports:1' % vo)
    for site in data['appdb:appdb']['appdb:site']:
        if type(site['site:service']) == type([]):
            for service in site['site:service']:
                try:
                    va_data = appdb_call('/rest/1.0/va_providers/%s' % service['@id'])
                    if va_data['appdb:appdb']['virtualization:provider']['provider:name'] == endpoint:
                        for resource_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:template']:
                            instance_desc = "%s MB - " % resource_tpl['provider_template:main_memory_size']
                            instance_desc += "%s CPUs" % resource_tpl['provider_template:physical_cpus']
                            instances.append((instance_desc, resource_tpl['provider_template:resource_name'].split("#")[1].replace(".","-")))
                except:
                    continue
        else:
            va_data = appdb_call('/rest/1.0/va_providers/%s' %site['site:service']['@id'])
            provider_name = va_data['appdb:appdb']['virtualization:provider']['provider:name']
            if provider_name == endpoint:
                for resource_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:template']:
                    instance_desc = "%s MB - " % resource_tpl['provider_template:main_memory_size']
                    instance_desc += "%s CPUs" % resource_tpl['provider_template:physical_cpus']
                    instances.append((instance_desc, resource_tpl['provider_template:resource_name'].split("#")[1].replace(".","-")))
    return instances

def print_javascript():
    for site in get_sites(vo):
        if site.endswith("/"):
            print site[:-1]
        else:
            print site
        for os in get_oss(site):
            print os
        for instance in get_instances(site):
            i = instance.split("#")[1]
            if "http" in i:
                print i[:i.index('http')-1]
            else:
                print i

def print_all():

        print " ~ Listing providers subscribed the [%s] VO" % vo
        # E.g. https://appdb.egi.eu/rest/1.0/sites?flt=%%2B%%3Dvo.name:training.egi.eu&%%2B%%3Dsite.supports:1
        data = appdb_call('/rest/1.0/sites?flt=%%2B%%3Dvo.name:%s&%%2B%%3Dsite.supports:1' % vo)

        for site in data['appdb:appdb']['appdb:site']:
                if  type(site['site:service']) == type([]):
                    for service in site['site:service']:
                       try:
                         va_data = appdb_call('/rest/1.0/va_providers/%s' % service['@id'])

                         # E.g.: https://appdb.egi.eu/rest/1.0/va_providers/8253G0
                         url = ("/rest/1.0/va_providers/%s" % service['@id'])

                         print "- %s [%s] \n\t--> Sitename: %s \
                                            \n\t--> Hostname: %s \
                                            \n\t--> Endpoint: %s \
                                            \n\t--> Status: %s \
                                            \n\t--> URL: %s" \
                                % (site['@name'],
                                   service['@id'],
                                   site['site:officialname'],
                                   service['@host'],
                                   va_data['appdb:appdb']['virtualization:provider']['provider:endpoint_url'],
                                   site['@status'],
                                   url)


                         print "\n ~ Getting published resource_tpl\n"
                         for resource_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:template']:
                                print "\t%s" % resource_tpl['provider_template:resource_id']
                         
                         print " ~ Getting published os_tpl"
                         for os_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:image']:
                                try:
                                        if vo in os_tpl['@voname']:
                                                print "\n\t - Name = %s [v%s] " % (os_tpl['@appname'], os_tpl['@vmiversion'])
                                                print "\t - OCCI ID = %s" % os_tpl['@va_provider_image_id']
                                                print "\t - URI = %s" % os_tpl['@mp_uri']
                                except:
                                        print ""

                       except:
                        print ""

                else:
                        va_data = appdb_call('/rest/1.0/va_providers/%s' % site['site:service']['@id'])

                        url = ("/rest/1.0/va_providers/%s" % site['site:service']['@id'])

                        print "\n- %s [%s] \n\t--> Sitename: %s \
                                           \n\t--> Hostname: %s \
                                           \n\t--> Endpoint: %s \
                                           \n\t--> Status: %s \
                                           \n\t--> URL: %s" \
                                % (site['@name'],
                                   site['site:service']['@id'],
                                   site['site:officialname'],
                                   site['site:service']['@host'],
                                   va_data['appdb:appdb']['virtualization:provider']['provider:endpoint_url'],
                                   site['@status'],
                                   url)
                         
                        print "\n ~ Getting published resource_tpl\n"
                        for resource_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:template']:
                                print "\t%s" % resource_tpl['provider_template:resource_id']

                        print "\n ~ Getting published os_tpl"
                        for os_tpl in va_data['appdb:appdb']['virtualization:provider']['provider:image']:
                                try:
                                        if vo in os_tpl['@voname']:
                                                print "\n\t - Name = %s [v%s] " % (os_tpl['@appname'], os_tpl['@vmiversion'])
                                                print "\t - OCCI ID = %s" % os_tpl['@va_provider_image_id']
                                                print "\t - URI = %s" % os_tpl['@mp_uri']
                                except:
                                        print ""

        print

if __name__ == "__main__":
    vo = "fedcloud.egi.eu"
    option = "all"
    if len(sys.argv) > 3:
        vo = sys.argv[3]
        endpoint = sys.argv[2]
        option = sys.argv[1]
    elif len(sys.argv) > 2:
        vo = sys.argv[2]
        option = sys.argv[1]
    elif len(sys.argv) > 1:
        option = sys.argv[1]

    if option == "vos":
        for vo in get_vo_list():
            print vo
    elif option == "sites":
        for site in get_sites(vo):
            if site.endswith("/"):
                print site[:-1]
            else:
                print site
    elif option == "os":
        for os in get_oss(endpoint):
            if os.endswith("/"):
                print os[:-1]
            else:
                print os
    elif option == "instances":
        hostname = urlparse(endpoint)[1].split(':')[0]
        for inst_desc, inst_name in get_instances(endpoint):
            print "%s;%s" % (inst_desc, inst_name)
    elif option == "javascript":
        print_javascript()
    else:
        print_all()
