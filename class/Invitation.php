<?php
include_once(CLASSPATH . 'MyObject.php');
class Invitation extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'invitations', 'token', 'text');
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
		$invitation = $invitation->find_by($invitation->identifier, $id);
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
		include(LAYOUTPATH . 'languages/Invitation_' . $this->gui->user->rolle->language . '.php');

		$link = URL . (substr(URL, -1) != '/' ? '/' : '') . APPLVERSION .
			'index.php?go=logout&token=' . $this->get('token') .
			'&email=' . $this->get('email') .
			'&Stelle_ID=' . $this->get('stelle_id') .
			'&Name=' . urlencode($this->get('name')) .
			'&Vorname=' . urlencode($this->get('vorname')) .
			'&login_name=' . urlencode($this->get('loginname')) .
			'&language=' . $this->gui->user->rolle->language;

		$text = str_replace('$link', $link, $this->stelle->get('invitation_text')) ?: $strInvitationText . '

		' .  $strInvitationLink . ':
		
		' . $link . '
		
		' . $strInvitationLinkAlternative . ' "' . TITLE . '". ' . $strInvitationAfterLinkText . '
		
		' . $strInvitationQuestionsTo . ' ' . $this->inviter->get('Vorname') . ' ' . $this->inviter->get('Name') . ': ' . $this->inviter->get('email') . '
		
		' . $strInvitationAutomationText;

		$msg = $this->get('email') . 
'?subject=Einladung zur Registrierung bei ' . TITLE .
'&body=' . rawurlencode($strInvitationHeader . ($this->get('anrede') == 'Herr' ? 'r' : '') . ' ' . $this->get('anrede') . ' ' . $this->get('name') . ',

' . $text);
		return $msg;
	}
}
?>
