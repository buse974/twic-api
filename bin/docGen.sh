#!/bin/bash

#phpdoc -d ../module/Application/src/Application/ -t ../data/doc/php/ --template="responsive-twig"
phpdoc -d ../module/Application/src/Application/Service/ -t ../data/doc/php/ --template="responsive-twig"

make ../data/doc/sphinx/

apiary publish --path="../data/doc/apiary/thestudnetapi.apib" --api-name="thestudnetapi"
