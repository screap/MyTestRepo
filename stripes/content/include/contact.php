<?php
	require_once('./include/mimeMail/htmlMimeMail.php');
	$aError = array();
	$strError = '';
	$strInfo = '';
	$strName = '';
	$strEmail = '';
	$strMessage = '';
	$intTime = time();
	$_SESSION['contactForm'][$intTime] = 'new';
	if(isset($_POST['time']) && array_key_exists($_POST['time'], $_SESSION['contactForm'])) {
		if($_SESSION['contactForm'][$_POST['time']] == 'new') {
			$strName = $_POST['name'];
			$strEmail = $_POST['email'];
			$strMessage = $_POST['message'];
			if(strlen($strName) < 4) {
				array_push($aError, 'Gib deinen Namen ein.');
			}
			if(!eregi('^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9|ü|ä|ö]+([-_\.]?[a-z0-9|ü|ä|ö])+\.[a-z]{2,4}', $strEmail)) {
				array_push($aError, 'Eine gülte E-Mail-Adresse brauche ich schon.');
			}
			if(strlen($strMessage) < 10) {
				array_push($aError, 'Ohne Mitteilung hat es keinen Sinn.');
			}
			if(count($aError) == 0) {
				$strMailBody = '
					<h1>Mitteilung &uuml;ber Kontaktformular</h1>
					<table cellpadding="10" cellspacing="0" border="0">
						<tr>
							<td>Name:</td>
							<td><strong>' . htmlentities(utf8_decode($strName)) . '</strong></td>
						</tr>
						<tr>
							<td>E-Mail:</td>
							<td><strong>' . htmlentities(utf8_decode($strEmail)) . '</strong></td>
						</tr>
						<tr>
							<td>Mitteilung:</td>
							<td><strong>' . nl2br(htmlentities(utf8_decode($strMessage))) . '</strong></td>
						</tr>
					</table>
				';
				$objMimeMail = new htmlMimeMail();
				$objMimeMail->setFrom($strEmail);
				$objMimeMail->setSubject('Kontaktformular');
				$objMimeMail->setHTML($strMailBody);
				$objMimeMail->send(array('smile@screap.de'), 'mail');
				$strName = '';
				$strEmail = '';
				$strMessage = '';
				$_SESSION['contactForm'][$_POST['time']] = 'sent';
				$strInfo = 'Erfolgreich versandt.';
			}
		} else {
			$strInfo = 'Deine Mitteilung wurde bereits gesendet.';
		}
	}
	if(count($aError) > 0) {
		$strError = '<p class="formError">';
		foreach($aError as $strItem) {
			$strError .= $strItem.'<br />';
		}
		$strError .= '</p>';
	}
	if($strInfo != '') {
		$strInfo = '<p>'.$strInfo.'</p>';
	}
?>
	<form id="contactForm" action="<?php echo HTTP_ROOT.$objItem->id; ?>" method="post">
		<?php echo $strInfo; ?>
		<?php echo $strError; ?>
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td valign="top"><label for="name">Name</label></td>
				<td valign="top"><input id="name" name="name" value="<?php echo $strName; ?>" /></td>
			</tr>
			<tr>
				<td valign="top"><label for="email">E-Mail</label></td>
				<td valign="top"><input id="email" name="email" value="<?php echo $strEmail; ?>" /></td>
			</tr>
			<tr>
				<td valign="top"><label for="message">Mitteilung</label></td>
				<td valign="top"><textarea id="message" name="message" rows="4" cols="20"><?php echo $strMessage; ?></textarea></td>
			</tr>
			<tr>
				<td valign="top">&nbsp;<input type="hidden" name="time" id="time" value="<?php echo $intTime; ?>" /></td>
				<td valign="top"><input type="submit" value="Senden" /></td>
			</tr>
		</table>
	</form>