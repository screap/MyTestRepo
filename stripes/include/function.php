<?php
	function getMixColor($strColor, $strMixColor, $intIntensity) {
		$aColors = array();
		$aMixedColors = array();
		if(substr($strColor, 0, 1) == '#') { $strColor = substr($strColor, 1); }
		if(substr($strMixColor, 0, 1) == '#') { $strMixColor = substr($strMixColor, 1); }
		$aColors['red']['color'] = hexdec(substr($strColor, 0, 2));
		$aColors['red']['mix'] = hexdec(substr($strMixColor, 0, 2));
		$aColors['green']['color'] = hexdec(substr($strColor, 2, 2));
		$aColors['green']['mix'] = hexdec(substr($strMixColor, 2, 2));
		$aColors['blue']['color'] = hexdec(substr($strColor, 4, 2));
		$aColors['blue']['mix'] = hexdec(substr($strMixColor, 4, 2));
		foreach($aColors as $strColor => $aColor) {
			$aMixedColors[$strColor] = round(($aColor['color'] + ($aColor['mix'] * $intIntensity)) / ($intIntensity + 1));
			$aMixedColors[$strColor] = $aMixedColors[$strColor] > 255 ? 255 : $aMixedColors[$strColor];
		}
		$strMixedColor = '#' . str_pad(dechex($aMixedColors['red']), 2, '0', STR_PAD_LEFT) . str_pad(dechex($aMixedColors['green']), 2, '0', STR_PAD_LEFT) . str_pad(dechex($aMixedColors['blue']), 2, '0', STR_PAD_LEFT);
		return($strMixedColor);
	}

	function parseContent($objItem) {
		$strContent = '';
		foreach($objItem->content as $objContent) {
			$strContent .= '<div class="content">'.str_replace("\n", '<br />', trim($objContent));
			$aTags = array(
				array('[i]', '[/i]', '<em>', '</em>'),
				array('[b]', '[/b]', '<strong>', '</strong>')
			);
			foreach($aTags as $aTag) {
				preg_match_all('#'.addcslashes($aTag[0], '\[|\]|\/').'(.+?)'.addcslashes($aTag[1], '\[|\]|\/').'#is', $strContent, $aPregResult);
				$strContent = str_replace($aPregResult[0][0], $aTag[2].$aPregResult[1][0].$aTag[3], $strContent);
			}
			if(isset($objContent->link) && $objContent->link != '') {
				$strLinkColor = getMixColor($objItem->color, '#202020', 1.5);
				if(isset($objContent->link->description) && $objContent->link->description != '') {
					$strLinkDescription = $objContent->link->description;
				} else {
					$strLinkDescription = 'Mehr Info';
				}
				$strContent .= '<a class="link" title="'.$strLinkDescription.'" href="'.trim($objContent->link).'" style="color:'.$strLinkColor.';">'.$strLinkDescription.'</a>';
			}
			if(isset($objContent->include) && $objContent->include != '') {
				$strInclude = './content/include/'.$objContent->include;
				if(file_exists($strInclude)) {
					ob_start();
					include($strInclude);
					$strContent .= ob_get_contents();
					ob_end_clean();
				}
			}
			$strContent .= '</div>';
			if(isset($objContent->image) && $objContent->image != '') {
				$strContent .= '<img src="'.HTTP_ROOT.'/image/'.$objContent->image.'" alt="'.$objContent->image.'" />';
			}
			$strContent .= '<div class="clear">&nbsp;</div>';
		}
		return($strContent);
	}

	function getOutput($objContent) {
		$strOutput = '';
		$aId = array();
		$strGetId = $_GET['item'];
		foreach($objContent->item as $objItem) {
			if(!isset($objItem->id) || $objItem->id == '') {
				$objItem->id = abs(crc32($objItem->content . $objItem->description . $objItem->title));
				while(in_array($objItem->id, $aId) || $objItem->id == 0) {
						$objItem->id = abs(crc32($objItem->size . $objItem->color . rand(0, 99999)));
				}
				array_push($aId, $objItem->id);
				$strItemId = 'item_'.$objItem->id;
			} else {
				$strItemId = $objItem->id;
			}
			if($strGetId == $objItem->id) {
				$strDisplayContent = 'block';
			} else {
				$strDisplayContent = 'none';
			}
			if(!isset($objItem->title) || $objItem->title == '') {
				$objItem->title = '&nbsp;';
			}
			$objItem->fontSize = $objItem->size * 1.1;
			$objItem->fontColor = getMixColor($objItem->color, '#323232', 0.9);
			$objItem->contentColor = getMixColor($objItem->color, '#ffffff', 3);
			$objItem->contentFontColor = getMixColor($objItem->color, '#323232', 1.1);
			if(isset($objItem->content) && $objItem->content != '') {
				$objContent = parseContent($objItem);
				$strOutput .= "\n".'<a id="'.$strItemId.'" title="'.$objItem->description.'" style="background-color:'.$objItem->color.'; font-size:'.$objItem->fontSize.'%; color:'.$objItem->fontColor.';" class="stripe_link" href="#'.$strItemId.'" onclick="showHide(\'content_'.$objItem->id.'\')"><span>'.$objItem->title.'</span></a>';
				$strOutput .= "\n".'<div id="content_'.$objItem->id.'" style="background-color:'.$objItem->color.'; display:'.$strDisplayContent.';" class="stripe_content"><div class="content_container" style="background-color:'.$objItem->contentColor.'; color:'.$objItem->contentFontColor.';">'.$objContent.'</div></div>';
			} else {
				$strOutput .= "\n".'<div id="'.$strItemId.'" title="'.$objItem->description.'" style="background-color:'.$objItem->color.'; font-size:'.$objItem->fontSize.'%; color:'.$objItem->fontColor.';" class="stripe_link"><span>'.$objItem->title.'</span></div>';
			}
		}
		return($strOutput);
	}
?>