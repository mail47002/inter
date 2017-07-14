<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>

	<body>
		<style>
			table tr td {
				border: 1px solid #000;
			}

			table thead tr td {
				font-weight: bold;
				background-color: #dcdcdc;
				color: #000;
			}
		</style>

		<table>
			<thead>
				<tr>
					<td>
						<strong>Laikas</strong>
					</td>

					<td>
						<strong>IP adresas</strong>
					</td>

					@foreach ($entry->questions as $question)
						<td colspan="{{ count($question->options) ? $question->options()->where('matrix', '<>', 'x')->count() : 1 }}">
							{{ $question->title }}
						</td>
					@endforeach
				</tr>
			</thead>

			<tr>
				<td></td>
				<td></td>

				@foreach ($entry->questions as $question)
					@if (count($question->options))
						@foreach ($question->options()->where('matrix', '<>', 'x')->get() as $option)
							<td width="10">
								{{ $option->title }}
							</td>
						@endforeach
					@else
						<td></td>
					@endif
				@endforeach
			</tr>

			@foreach ($entry->results as $result)
				<tr>
					<td>{{ $result->created_at }}</td>
					<td>{{ $result->ip }}</td>

					@foreach ($entry->questions as $question)
						@if (in_array($question->type, ['text', 'string']))
							<td width="10">
								@if ($result->answers()->where('question_id', '=', $question->id)->first())
									{{ $result->answers()->where('question_id', '=', $question->id)->first()->value }}
								@endif
							</td>
						@else
							@foreach ($question->options()->where('matrix', '<>', 'x')->get() as $option)
								<td width="10">
									@if (in_array($question->type, ['select', 'radio', 'check']))
										{{ $result->answers()->where('option_id', '=', $option->id)->first() ? 1 : 0 }}
									@endif


									@if ($question->type == 'matrix')
										@if ($result->answers()->where('option_id', '=', $option->id)->first())
											{{ $question->options()->where('id', '=', $result->answers()->where('option_id', '=', $option->id)->first()->value)->first()->title }}
										@endif
									@endif
								</td>
							@endforeach
						@endif
					@endforeach
				</tr>
			@endforeach
		</table>
	</body>
</html>