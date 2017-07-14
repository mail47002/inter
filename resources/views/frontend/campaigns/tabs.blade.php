<ul class="nav nav-tabs" role="tablist">
	<li class="{{ Route::getCurrentRoute()->getName() == 'campaigns.edit' ? "active" : NULL }}">
		<a href="{{ route('campaigns.edit', $entry->id) }}">Anketos nustatymai</a>
	</li>
	<li class="{{ in_array(Route::getCurrentRoute()->getName(), ['campaigns.questions', 'campaigns.questions.add']) ? "active" : NULL }}">
		<a href="{{ route('campaigns.questions', $entry->id) }}">Klausimai</a>
	</li>
	<li class="{{ in_array(Route::getCurrentRoute()->getName(), ['campaigns.results', 'campaigns.cross_tabulation']) ? "active" : NULL }}">
		<a href="{{ route('campaigns.results', $entry->id) }}">Rezultatai</a>
	</li>
</ul>

<p></p>