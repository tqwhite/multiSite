#!/bin/bash

# add -x to the shebang for debugging

get_script_dir () {
#http://www.ostricher.com/2014/10/the-right-way-to-get-the-directory-of-a-bash-script/
     SOURCE="${BASH_SOURCE[0]}"
     while [ -h "$SOURCE" ]; do
          DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
          SOURCE="$( readlink "$SOURCE" )"
          [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE"
     done
     directoryPath=$( dirname "$SOURCE" )
}

get_script_dir
export scriptDir=$directoryPath;

pushd $scriptDir > /dev/null

popd > /dev/null




export PATH=$PATH:$scriptDir:$scriptDir/shellScripts:

echo -e "\
$scriptDir
================================================================

pushwahcontent - push content to github
pushwahphp - push php to github
wahprod - open production server ssh session
wahcrepo - content repo
wahprepo - php repo
editwahcontent - open bbedit content project
editwahphp - open bbedit content project

\n\
================================================================\n\
\n\
";

alias wahprod="ssh wahprod";

alias wahprepo="cd /Volumes/qubuntuFileServer/clients/westonkaAnimalHospital/website/system; git status;"

alias wahcrepo="cd /Volumes/qubuntuFileServer/clients/westonkaAnimalHospital/website/content/main/store; git status;"

alias editwahcontent="open /Users/tqwhite/Documents/webdev/westonkaAnimalHospital/wahCONTENT.bbprojectd";
alias editwahphp="open /Users/tqwhite/Documents/webdev/westonkaAnimalHospital/wahPHP.bbprojectd";