#!/bin/bash

#sudo bash
rm cache/* -fr
chmod a+rwx cache/ -R
chown www-data:www-data cache/ -R
chmod a+rwx logs/ -R
chown www-data:www-data logs/ -R
