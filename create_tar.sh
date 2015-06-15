#!/bin/bash

VERSION=1.3.0

if [ ! -d dist ]
then
	mkdir dist
fi

rm -f dist/IM-web-${VERSION}.tar.gz
tar --exclude=create_tar.sh --exclude=doc --exclude=dist --exclude=.git  -czf dist/IM-web-${VERSION}.tar.gz *
