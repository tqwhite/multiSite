<?php

class PdfserviceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    
        // action body
    }

    public function watermarkAction()
    {
    
    $parameters=$this->getRequest()->getParams();
    
    $outPath="/home/tqwhite/cmerdc/corporateMultisite/website/public/media/tmpPlans/pdf/";

	$testPath="/home/tqwhite/cmerdc/corporateMultisite/libPdf/".$parameters['templateFileName'];
	$outFile="$outPath{$parameters['fileNameSuggestion']}.pdf";
	
	$uri="/media/tmpPlans/pdf/{$parameters['fileNameSuggestion']}.pdf";
	
	$text=$parameters['watermark']['text'];

	$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
	$pdf = Zend_Pdf::load($testPath);

	$pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_LETTER);
	$page=$pdf->pages[0];	
	$page->setFont($font, 20); //10 points
	
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
        	"templateFileName"=>$parameters['templateFileName'],
        	"note:"=>"successful round trip of parameters to resultsX"
        );
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



