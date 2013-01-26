<?php
namespace Q;

//Q\Utils::dump('hello');

class Utils{

static function dump($data, $name = false, $html=null, $echo_out = true){
	require_once('dump.php');
	dump($data, $name, $html, $echo_out);
}

static function dumpWeb($name, $label=null){
		$dumpHtml=true; // want html, not newlines, etc
		$dumpEcho=true; //sends result to string, true to use echo
		return self::dump($name, $label, $dumpHtml, $dumpEcho);
	}

static function dumpCli($name, $label=null){
		$dumpHtml=false; // want newlines, not html, etc
		$dumpEcho=true; //sends result to string, true to use echo
		return self::dump($name, $label, $dumpHtml, $dumpEcho);
	}

static function dumpWebString($name, $label=null){
		$dumpHtml=true; // want html, not newlines, etc
		$dumpEcho=false; //sends result to string, true to use echo
		return self::dump($name, $label, $dumpHtml, $dumpEcho);
	}

static function dumpCliString($name, $label=null){
		$dumpHtml=false; // want newlines, not html, etc
		$dumpEcho=false; //sends result to string, true to use echo
		return self::dump($name, $label, $dumpHtml, $dumpEcho);
	}

static function buildArray($inObj, $fieldNames){

	if (is_string($fieldNames)){
		$fieldNames=preg_replace('/,/', ' ', $fieldNames);
		$fieldNames=preg_replace('/\W+/', ' ', $fieldNames);
		$fieldNames=trim($fieldNames);
		$nameList=explode(' ', $fieldNames);
	}
	else if (is_array($fieldNames)){
		$nameList=$fieldNames;
	}
	else{
    throw new \Exception('Q\\Utils::buildArray says, $fieldNames must be string or array');
	}

	$outArray=array();
	if ($inObj[0]){ //can be addressed as an array

		$list=$inObj;
		for ($i=0, $len=count($list); $i<$len; $i++){
			$itemObj=$list[$i];
			$itemArray=array();

			$outList=array();
			for ($j=0, $len2=count($nameList); $j<$len2; $j++){
				$itemArray[$nameList[$j]]=$itemObj->$nameList[$j];
			}
			$outArray[]=$itemArray;

		}

	}
	else{
		$hasPropertiesFlag=false;
		foreach ($inObj as $label=>$data){
			$hasPropertiesFlag=true;
			break;
		}

		if ($hasPropertiesFlag){
			$list=$nameList;
			$outList=array();
			for ($i=0, $len=count($list); $i<$len; $i++){
				$outList[$list[$i]]=$inObj->$list[$i];
			}
			$outArray=$outList;
		}

	}

	return $outArray;
}

function newGuid(){
	//thanks: Kristof_Polleunis, http://php.net/manual/en/function.com-create-guid.php
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
    }
}

static function flattenToList($inArray){
	$outArray=array();
	foreach ($inArray as $label=>$data){
		$outArray[]=array($label, $data);
	}
	return $outArray;
}

static function callStack($stringFlag){
	$stackArray=debug_backtrace();
	$colorA='#ddf';
	$colorB='#dfd';

	$list=$stackArray;
	$outString='';
	$currColor=$colorA;
	$prevLine='-inside callstack-';
	$prevFile=__FILE__;
	for ($i=0, $len=count($list); $i<$len; $i++){
		$element=$list[$i];
		$class=isset($element['class'])?$element['class']:'';
		$line=isset($element['line'])?$element['line']:'';
		$function=isset($element['function'])?$element['function']:'';
		$file=$element['file'];

		$outString.="<tr style='background:$currColor;'><td>$i</td><td>{$class}::{$function}</td></tr>";


		$outString.="<tr style='background:$currColor;'><td>&nbsp;</td><td>{$prevFile} (line $prevLine)</td></tr>";
		$outString.="<tr style='background:transparent;'><td colspan='2'>&nbsp;</td></tr>";


		$currColor=($currColor==$colorA)?$colorB:$colorA;
		$prevLine=$line;
		$prevFile=$file;
	}

	$outString="<table style='font-family:sans-serif;'>$outString</table>";

	if ($stringFlag){
		return $outString;
	}
	else{
		echo $outString;
	}
}

static function validateProperties($args){
	$validatedEntity=$args['validatedEntity'];
	$propertyList=$args['propertyList'];
//	$targetScope=(isset($args['targetScope']))?$args['targetScope']:'';
	$source=(isset($args['source']))?$args['source']:'';

	$source=\Q\Utils::getPathElements($source, 3);

	$entityType=gettype($validatedEntity);

	$outMessages=array();

	foreach ($propertyList as $property){
		$propertyName=$property['name'];
		$importance=(isset($property['importance']))?$property['importance']:'';
		$requiredType=(isset($property['requiredType']))?$property['requiredType']:'';
		$assertNotEmptyFlag=(isset($property['assertNotEmptyFlag']))?$property['assertNotEmptyFlag']:'';

		if ($entityType=='array'){
			$validatedProperty=(isset($validatedEntity[$propertyName]))?$validatedEntity[$propertyName]:null;
		}
		else{
			$validatedProperty=$validatedEntity->$propertyName;
		}

		if ($importance!='optional' && !isset($validatedProperty)){
			$outMessages[]="$source says, *** $propertyName *** is missing";
		}

		if ($requiredType && gettype($validatedProperty)!=$requiredType){
			$outMessages[]="$source says, *** $propertyName *** is not of type: $requiredType";
		}

		if ($assertNotEmptyFlag && count($validatedProperty)==0){
			$outMessages[]="$source says, *** $propertyName *** cannot be empty";
		}

	}

	if (count($outMessages)>0){

	$list=$outMessages;
	$outString='validateProperties aborted execution:<p/>';
	for ($i=0, $len=count($list); $i<$len; $i++){
		$element=$list[$i];
		$outString.="$element<br/";
	}
	die($outString);
	}

}

static function getPathElements($path, $count){
	$list=explode('/', $path);
	$start=count($list)-$count;
	$outString='';

	for ($i=$start, $len=count($list); $i<$len; $i++){
		$element=$list[$i];
		$outString.=$element.'/';
	}

	return preg_replace('/\/$/', '', $outString);

}

/*



getDottedPath:function(baseObj, subPathString, debug){
	var target=baseObj,
		elements;
		this.getDottedPathLastProgressiveString='';

	var elements=subPathString.split('.');

	if (!subPathString){ return baseObj;}

	if (elements.length<2){
		return baseObj[subPathString];
	}
	else{
		for (var i=0, len=elements.length; i<len; i++){
			if (elements[i]){ //mainly eliminates trailing periods but would also eliminate double periods
				target=target[elements[i]];

				this.getDottedPathLastProgressiveString+=elements[i]+'.';
				if (typeof(target)=='undefined'){
					if (debug){ console.dir(elements[i]);}
					qtools.consoleMessage('bad path='+this.getDottedPathLastProgressiveString);
					return null;
				}
			}
		}
	}
	return target;
},
*/

static function getDottedPath($baseObj, $subPathString, $debug=true){
	$target=$baseObj;
	global $getDottedPathLastProgressiveString; //since this is static, I don't have a $this to hold it

	$elements=explode('.', $subPathString);

	if (!$subPathString){ return $baseObj; }

	if (count($elements)<2){ echo "exiting\n"; return $baseObj[$subPathString]; }
	else{
		for ($i=0, $len=count($elements); $i<$len; $i++){
			if ($elements[$i]){ //mainly eliminates trailing periods but would also eliminate double periods

				$target=$target[$elements[$i]];

				$getDottedPathLastProgressiveString.=$elements[$i].'.';
				if (!isset($target)){
					if ($debug){ echo $elements[$i]."<br/>\n";}
					echo 'bad path='.$getDottedPathLastProgressiveString."<br/>\n";
					return '';
				}
			}
		}
	}
	return $target;

}

static function htmlEntities($inData){

	if (gettype($inData)=='string'){
		return htmlentities($inData);
	}
	elseif (gettype($inData)=='array'){

		foreach ($inData as $label=>$data){
			$outArray[$label]=self::htmlentities($data);
		}
	}
	else{
		throw(new \Exception('Qutils::htmlentities says, no support for type='.gettype($inData)));
	}

	return $outArray;
}

static function errorListToString($errorList, $separator){
		$outString='';
		for ($i=0, $len=count($errorList); $i<$len; $i++){
			$element=$errorList[$i];
			$outString.="{$element[0]}:{$element[1]}$separator";
		}
		return $outString;
}

}//end of class


