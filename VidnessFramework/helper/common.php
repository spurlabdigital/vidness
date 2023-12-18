<?php


class CommonHelper {
	public static function sendEmail($to, $subject, $message, $headers) {
		mail($to, $subject, $message, $headers);
	}

	public static function formatDateTime($date) {
		return date('D, M j, Y', strtotime($date));
	}

	public static function formatShortDateTime($date) {
		return date('F j, Y', strtotime($date));
	}

	public static function isValidEmail($email) {
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}

	public static function sendMail($to, $subject, $message, $headers){
		error_log('Email is not being sent');
	}
}

?>