<?php
	require('./include/phplot/phplot.php');
	$fileCsv = fopen('./content/include/graph.csv', 'r');
	$strTitle = '';
	$aData = array();
	$intAverage = 0;
	$intI = 0;
	while ($aItem = fgetcsv($fileCsv, 1000, ';')) {
		$intDate = $aItem[0];
		if($intI == 0) {
			$strTitle = $intDate;
		} elseif($intDate != '') {
			$intValue = $aItem[1];
			array_push($aData, array($intDate, $intI, $intValue));
			if($intValue == 10) {
				$intValue = $intValue * 2;
			} elseif($intValue == 9) {
				$intValue = $intValue * 1.5;
			}
			$intAverage = $intAverage + $intValue;
		}
		$intI++;
	}
	fclose($fileCsv);
	if($intValue == 0){
		$intDay = substr($intDate, 4, 2);
		$intMonth = substr($intDate, 2, 2);
		$intYear = substr($intDate, 0, 2);
		$intTimestamp = mktime(0, 0, 0, $intMonth, $intDay, $intYear);
		$intDayDifference = floor((time() - $intTimestamp) / 60 / 60 / 24);
		for($intJ = 1; $intJ <= $intDayDifference; $intJ++) {
			array_push($aData, array(date('ymd', $intTimestamp + ($intJ * 24 * 60 * 60)), $intI, $intValue));
			$intI++;
		}
	}
	$intAverage = $intAverage / ($intI - 1);
/*
	if($intAverage < 3) {
		print('<p><em>"Fuck off!"</em></p>');
	} elseif($intAverage < 5) {
		print('<p><em>"Warten auf mehr."</em></p>');
	} elseif($intAverage < 7) {
		print('<p><em>"Zeig mir die Zukunft!"</em></p>');
	} else{
		print('<p><em>"Yeah! That\'s it!"</em></p>');
	}
*/
	$objPlot = new PHPlot(800,200);
	$objPlot->SetXTitle($strTitle, 'plotup');
	$objPlot->SetDataValues($aData);
	$objPlot->SetXTickIncrement(1);
	$objPlot->SetYTickIncrement(1);
	$objPlot->SetPlotType('lines');
	$objPlot->SetDataType('data-data');
	$objPlot->SetDataColors($objItem->color);
	$objPlot->SetBackgroundColor($objItem->contentColor);
	$objPlot->SetTickColor($objItem->contentColor);
	$objPlot->SetTitleColor($objItem->contentColor);
	$objPlot->SetTextColor($objItem->contentColor);
	$objPlot->SetGridColor($objItem->contentColor);
	$objPlot->SetLightGridColor($objItem->contentColor);
	$objPlot->SetIsInline(true);
	$objPlot->SetDrawYGrid(false);
	$objPlot->SetXTickCrossing(0);
	$objPlot->SetYTickCrossing(0);
	$objPlot->SetOutputFile('./image/graph/graph.png');
	$objPlot->DrawGraph();
?>
<img src="<?php echo HTTP_ROOT; ?>/image/graph/graph.png" alt="graph.png" style="float:none; border-width:0;" />