<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Slaptažodžio priminimas</h2>

		<div>
			Tam, kad atstatyti jūsų slaptažodį užpildykite šią formą: {{ route('password.reset', array($token)) }}.<br>
			Šios nuorodos galiojimo laikas pasibaigs po {{ Config::get('auth.reminder.expire', 60) }} min.
		</div>
	</body>
</html>