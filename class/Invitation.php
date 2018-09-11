<?php
include_once(CLASSPATH . 'MyObject.php');
class Invitation extends MyObject {

	static $write_debug = false;
	static $identifier = 'token';

	function Invitation($gui) {
		$this->MyObject($gui, 'invitations');
		$this->identifier = Invitation::$identifier;
		$this->identifier_type = 'text';
		$this->validations = array(
			array(
				'attribute' => 'token',
				'condition' => 'not_null',
				'description' => 'Es muss ein UUID vorhanden sein.',
				'options' => null
			),
			array(
				'attribute' => 'email',
				'condition' => 'not_null',
				'description' => 'Es muss eine E-Mail angegeben werden.',
				'options' => null
			),
			array(
				'attribute' => 'stelle_id',
				'condition' => 'not_null',
				'description' => 'Es muss eine Stelle ausgewählt worden sein.',
				'options' => null
			)
		);
	}

	public static	function find_by_id($gui, $id) {
		$invitation = new Invitation($gui);
		$invitation = $invitation->find_by(Invitation::$identifier, $id);
		$inviter = new MyObject($gui, 'user');
		$invitation->inviter = $inviter->find_by('ID', $invitation->get('inviter_id'));
		$stelle = new MyObject($gui, 'stelle');
		$invitation->stelle = $stelle->find_by('ID', $invitation->get('stelle_id'));
		return $invitation;
	}

	public static	function find($gui, $where, $order) {
		$invitation = new Invitation($gui);
		return $invitation->find_where($where, $order);
	}

	function mailto_text() {
		# // ToDo Den Einladungstext fertig machen mit richtigem Ansprechpartner etc.
			#//ToDo Werte des Einladenden mit abfragen.
		$link = URL . APPLVERSION . 'index.php?go=logout&token=' . $this->get('token') . '&email=' . $this->get('email') . '&stelle_id=' . $this->get('stelle_id') . '&Name=' . urlencode($this->get('name')) . '&Vorname=' . urlencode($this->get('vorname'));
		$msg = $this->get('email') . 
'?subject=Einladung zur Registrierung bei ' . TITLE .
'&body=' . rawurlencode('Einladung für ' . $this->get('vorname') . ' ' . $this->get('name') . ',

Sie sind von ' . $this->inviter->get('Vorname') . ' ' . $this->inviter->get('Name') .  ' zur Mitarbeit bei ' . TITLE . ' eingeladen worden.
Um der Einladung zu folgen klicken Sie bitte auf den nachstehenden Link.

' . $link . '

Sollte sich kein Browserfenster öffnen, können Sie auch manuell ein Browserfenster öffnen und den Link in die Adresszeile kopieren. Achten Sie bitte darauf den vollständigen Text zu kopieren.

Der Link verweist auf die Registrierungsseite von ' . TITLE . '.
Dort werden Sie aufgefordert sich ein Benutzernamen und ein Passwort zu vergeben.
Ist die Registrierung erfolgreich abgeschlossen können Sie mit diesem Login in der Web-Anwendung arbeiten.

Für Rückfragen wenden Sie sich bitte an
' . $this->inviter->get('Vorname') . ' ' . $this->inviter->get('Name') . ' unter der E-Mail: ' . $this->inviter->get('email') . '

Diese E-Mail wurde automatisch mit kvwmap erstellt und ist nur für den Nutzer mit der angegebenen E-Mail-Adresse gedacht.
Bitte geben sie diese E-Mail nicht an Dritte weiter. Sie könnten sich statt Ihnen bei ' . TITLE . ' anmelden und in Ihrem Namen dort arbeiten und eingetragene Rechte wahrnehmen!');
		return $msg;
	}
}
?>
