#!/bin/bash

VERSION=1.5.11

if [ ! -d dist ]
then
	mkdir dist
fi

rm -f dist/IM-web-${VERSION}.tar.gz
tar --exclude=create_tar.sh --exclude=docker --exclude=doc --exclude=dist --exclude=.git --exclude=test -czf dist/IM-web-${VERSION}.tar.gz *
