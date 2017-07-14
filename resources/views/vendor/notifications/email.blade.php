<h2>Slaptažodžio priminimas</h2>
<div>
    <p>Tam, kad atstatyti jūsų slaptažodį užpildykite šią formą: {{ route('password.reset', $actionUrl) }}.<br>
        Šios nuorodos galiojimo laikas pasibaigs po {{ config('auth.passwords.users.expire', 60) }} min.</p>
</div>
