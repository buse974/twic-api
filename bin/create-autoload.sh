#!/bin/bash

./classmap_generator.php -l ../vendor/zendframework/zendframework -o ../autoload_classmap.php -w;
#../vendor/bin/classmap_generator.php -l ../vendor/opentok/opentok/src/OpenTok -o ../autoload_classmap.php -w -a;
find ../module/ -maxdepth 1 -type d | grep -v ^../module/$ | xargs -I {} ./classmap_generator.php -l {}/src -o {}/autoload_classmap.php -w
