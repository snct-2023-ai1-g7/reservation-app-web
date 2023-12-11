#!/bin/bash
if [ -z $1 ]; then 
    echo "Few args"
    exit
fi

if [ -z $2 ]; then 
    echo "Few args"
    exit
fi

before=$1
after=$2

vite="src/vite.config.js"
appTs="src/resources/js/app.ts"
env="src/.env"

cat $vite | sed "s/$before/$after/" > ~/vite
cat $appTs | sed "s/$before/$after/" > ~/appTs
cat $env | sed "s/$before/$after/" > ~/env