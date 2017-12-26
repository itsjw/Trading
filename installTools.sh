#!/usr/bin/env bash
ssh root@$1 rm -f ./setupServer.sh
ssh root@$1 wget https://raw.githubusercontent.com/mmaheo/Trading/master/setupServer.sh
ssh root@$1 chmod 777 ./setupServer.sh
ssh root@$1 ./setupServer.sh
