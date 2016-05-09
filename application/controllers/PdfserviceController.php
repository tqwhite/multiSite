<?php

class PdfserviceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    echo json_encode("hello from Pdfservice");
    exit;
        // action body
    }

    public function watermarkAction()
    {
    $parameters=$this->getRequest()->getParams();
// $parameters=array(
// 'templateFileName'=>"WorkEnvironmentPreferences.pdf",
// 'fileNameSuggestion'=>"tq",
// 'watermark'=>array('text'=>'HELLO!')
// );

$name='templateFileName';
if (!isset($parameters[$name])){
        header('Content-type: application/json');
	echo json_encode(Array("status"=>0, "message"=>"missing parameter: $name", "<br><br>PARAMETERS"=>$parameters, "<br><br>_SERVER"=>$_SERVER));
	exit;
}

	$name='fileNameSuggestion';
	if (!isset($parameters[$name])){
			header('Content-type: application/json');
		echo json_encode(Array("status"=>0, "message"=>"missing parameter: $name"));
		exit;
	}

	$name='watermark';
	if (!isset($parameters[$name])){
			header('Content-type: application/json');
		echo json_encode(Array("status"=>0, "message"=>"missing parameter: $name"));
		exit;
	}

    $outPath="media/tmpPlans/pdf/";

	$testPath="media/tmpPlans/libPdf/".$parameters['templateFileName'];
	$outFile="$outPath{$parameters['fileNameSuggestion']}.pdf";
	
	$scheme=!isset($_SERVER['REQUEST_SCHEME'])?'http':$_SERVER['REQUEST_SCHEME'];
	$uri="{$scheme}://{$_SERVER['HTTP_HOST']}/media/tmpPlans/pdf/{$parameters['fileNameSuggestion']}.pdf";
	
	$text=$parameters['watermark']['text'];

	$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
	$pdf = Zend_Pdf::load($testPath);

	$pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_LETTER);
	$page=$pdf->pages[0];	
	$page->setFont($font, 10); //10 points
	
	$width  = $page->getWidth();
	$height = $page->getHeight();
	$red = new Zend_Pdf_Color_Html('red');

	$leftBoundary1=50;
	$addressBlockTopLine=615;
	$fieldHeight=14;
	$standardGap=8;
	
    $textWidth = $this->_getTextWidth($text, $page->getFont(), $page->getFontSize());
	$center=($width-$textWidth)/2;



	$page->setFillColor($red)->drawText($text, $center, 410);
	
	
     $pdf->pages[0]=$page;
     $pdf->save($outFile);
	
	
	
        $status=array(
        	"status"=>"1",
        	"created"=>"1",
        	"uri"=>$uri,
        	"templateFileName"=>$parameters['templateFileName']
        	,"<br><br>PARAMETERS"=>$parameters, 
        	"<br><br>_SERVER"=>$_SERVER,
        );
        header('Content-type: application/json');
        echo json_encode($status);
        exit;
    }
    
    private function _getTextWidth($text, $font, $font_size) {
    //thanks: http://stackoverflow.com/questions/7744723/why-is-this-code-to-center-text-on-a-pdf-using-the-php-zend-pdf-library-not-work
    $drawing_text = iconv('', 'UTF-8', $text);
    $characters    = array();
    for ($i = 0; $i < strlen($drawing_text); $i++) {
        $characters[] = ord ($drawing_text[$i]);
    }

    $glyphs        = $font->glyphNumbersForCharacters($characters);
    $widths        = $font->widthsForGlyphs($glyphs);
    $text_width   = (array_sum($widths) / $font->getUnitsPerEm()) * $font_size;
    return $text_width;
}


}



