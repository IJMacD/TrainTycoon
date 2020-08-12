#!/bin/sh

# inspired by:
#   https://blog.fortrabbit.com/deploying-code-with-rsync
#   https://www.exratione.com/2016/03/deployment-of-scripts-and-static-content-with-git-rsync-and-simple-unix-tools/

DIR="$( cd "$( dirname "$0" )" && pwd)"
REPO="$( cd "$DIR/.." && pwd)"
TARGET_DIR=/var/www/xampp.ijmacd.com/html/TrainTycoon

ENV=prod

echo "# Deploying ${REPO}"
rsync -av --filter="merge ${DIR}/rsync-filter" ${REPO}/ ec2-user@ssh.ijmacd.com:$TARGET_DIR
