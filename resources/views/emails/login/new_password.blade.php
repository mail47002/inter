<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Prisijungimo duomenys</h2>

		<div>
			<p>
				Sveiki, jūs pirmą kartą prisijungėte mūsų svetainėje per socialinį tinklą, todėl siunčiame jums jūsų prisijungimo duomenis.
			</p>

			<p>
				El. paštas prisijungimui: {{ $user->email }}<br>
				Slaptažodis: {{ $password }}
			</p>
		</div>
	</body>
</html>