<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Campaign;
use App\CampaignResult;
use App\CampaignAnswer;
use App\CampaignTag;
use App\CampaignQuestion;
use App\CampaignQuestionOption;
use App\UserCredit;
use Auth;
use Validator;
use File;
use Image;
use App\Services\Kashi\Kashi;
use Excel;
use App\Services\SpearmanCorrelation;

class CampaignsController extends Controller
{
    // Allowed question types
    protected $types = [
        'radio', 'select', 'check', 'string', 'text', 'matrix'
    ];

	public function __construct()
	{
        $this->middleware('auth', [
            'except' => [
                'index', 'answer', 'store_answer', 'answers', 'answered', 'search'
            ]
        ]);
	}

	public function index()
	{
		$entries = Campaign::where('active', '=', 1)
            ->where('public', '=', 1)
            ->orderBy('advertise_credits', 'desc')
            ->paginate(20);

		return view('frontend.campaigns.index', [
		    'entries' => $entries
        ]);
	}

	public function search(Request $request)
	{
		$entries = Campaign::where('active', '=', 1)
			->leftJoin('campaigns_tags', 'campaigns.id', '=', 'campaigns_tags.campaign_id')
			->where('campaigns.public', '=', 1)
			->where(function ($q) use ($request) {
				$q->where('campaigns.title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('campaigns_tags.title', 'LIKE', '%' . $request->search . '%');
			})
			->orderBy('campaigns.id', 'desc')
			->select('campaigns.*')
			->groupBy('campaigns.id')
			->paginate(20);

		return view('frontend.campaigns.index', [
		    'entries'   => $entries
        ]);
	}

	public function my()
	{
		$entries = Auth::guard('web')->user()
            ->campaigns()
            ->orderBy('id', 'desc')
            ->paginate(10);

        $anketos = Campaign::where('active', '=', 1)
            ->where('public', '=', 1)
            ->orderBy('advertise_credits', 'desc')
            ->paginate(20);

		return view('frontend.campaigns.my', [
		    'entries' => $entries,
            'anketos' => $anketos
        ]);
	}

	public function answer($id, Request $request)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->active) {
			$available = true;

			// check if answering from same computer is available
			if (!$entry->same_computer) {
				$available = false;

				if (Auth::guard('web')->check() && $entry->results()->where('user_id', Auth::guard('web')->user()->id)->count() == 0) {
                    $available = true;
                } else if ($entry->results()->where('ip', $request->getClientIp())->count() == 0) {
                    $available = true;
                }
			}

			if ($available) {
				return view('frontend.campaigns.answer', [
				    'entry' => $entry
                ]);
			} else {
                return redirect()->route('campaigns.answered');
            }
		} else {
            return redirect()->route('campaigns.notfound');
        }
	}

	public function store_answer($id, Request $request)
	{
		$campaign = Campaign::find($id);

		if ($campaign && $campaign->active) {
            if (!Auth::guard('web')->check() || Auth::guard('web')->user()->id !== $campaign->user_id) {
                $rules = [];

                foreach ($campaign->questions as $question) {
                    if ($question->required == 1) {
                        $rules[$question->id] = 'required';
                    }
                }

                // Need move to translate
                $messages = [
                    'required' => 'Būtina užpildyti'
                ];

                $this->validate($request, $rules, $messages);

                // Featured
                if ($campaign->advertise_results == 1) {
                    $price              = config('settings.featured_credits');
                    $advertise_credits  = $campaign->advertise_credits - $price;
                    $user_credits       = $campaign->used_credits + $price;

                    // Store advertise
                    $campaign->used_credits = $user_credits;
                    $campaign->advertise_credits = $advertise_credits;
                    $campaign->advertise_results = ($advertise_credits > 0) ? 1 : 0;

                    $campaign->save();

                    // Store results
                    $this->store_results($id, $campaign, $request, $price);

                    // Store user credits
                    $credits = new UserCredit;

                    $credits->user_id = $campaign->user_id;
                    $credits->description = 'Už reklamuojamą atsakymą';
                    $credits->credits = -$price;

                    $credits->save();
                } else {
                    // User credits
                    $user_credits = UserCredit::where('user_id', $campaign->user_id)->get();

                    // Total user credits
                    $credits = 0;

                    foreach ($user_credits as $user_credit) {
                        $credits += (int)$user_credit->credits;
                    }

                    // Price
                    $price = config('settings.campaigns_credits');

                    if ($credits > 0 && ($credits - $price) >= 0) {
                        // Store advertise
                        $campaign->used_credits += $price;

                        $campaign->save();
                        // Store results
                        $this->store_results($id, $campaign, $request, $price);

                        // Store user credits
                        $credits = new UserCredit;

                        $credits->user_id = $campaign->user_id;
                        $credits->description = 'Už atsakymą';
                        $credits->credits = -$price;

                        $credits->save();
                    }
                }

                if ($campaign->respondents) {
                    return redirect()->route('campaigns.answers', $campaign->id);
                } else {
                    return redirect()->route('campaigns.answered');
                }
            }

            return redirect()->back();
        }

        return redirect()->route('campaigns.notfound');
	}

    private function store_results($id, $campaign, $request, $price)
    {
        // Store result
        $result = new CampaignResult;

        $result->campaign_id    = $id;
        $result->user_id        = Auth::guard('web')->check() ? Auth::guard('web')->user()->id : 0;
        $result->ip             = $request->getClientIp();

        $result->save();

        // Store user credits
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->id !== $campaign->user_id) {
            $credits = new UserCredit;

            $credits->user_id = Auth::guard('web')->user()->id;
            $credits->description = 'Už atsakymą';
            $credits->credits = $price;

            $credits->save();
        }

        // Store question
        foreach ($campaign->questions as $question) {
            if ($request->has($question->id)) {
                if (in_array($question->type, ['radio', 'select', 'string', 'text'])) {
                    $answer = new CampaignAnswer;

                    $answer->campaign_id    = $id;
                    $answer->question_id    = $question->id;
                    $answer->result_id      = $result->id;
                    $answer->type           = $question->type;

                    if (in_array($question->type, ['radio', 'select'])) {
                        $answer->option_id = $request->get($question->id);
                    }

                    if (in_array($question->type, ['string', 'text'])) {
                        $answer->value = $request->get($question->id);
                    }

                    if ($request->get($question->id) == 'custom' && $question->tpe == 'radio') {
                        if (!$request->get('custom-' . $question->id)) {
                            return redirect()->back()->withInput();
                        }

                        $answer->option_id  = 0;
                        $answer->value      = $request->get('custom-' . $question->id);
                        $answer->type       = 'custom';
                    }

                    $answer->save();
                } else {
                    if ($question->type == 'check') {
                        foreach ($request->get($question->id) as $option_id => $value) {
                            $answer = new CampaignAnswer;

                            $answer->campaign_id    = $id;
                            $answer->question_id    = $question->id;
                            $answer->result_id      = $result->id;

                            if ($value == 'custom') {
                                if (!$request->get('custom-' . $question->id)) {
                                    return redirect()->back()->withInput();
                                }

                                $answer->option_id  = 0;
                                $answer->value      = $request->get('custom-' . $question->id);
                                $answer->type       = 'custom';
                            } else {
                                $answer->option_id  = $option_id;
                                $answer->type       = $question->type;
                            }

                            $answer->save();
                        }
                    }

                    if ($question->type == 'matrix') {
                        foreach ($request->get($question->id) as $option_id => $value) {
                            $answer = new CampaignAnswer;

                            $answer->campaign_id    = $id;
                            $answer->question_id    = $question->id;
                            $answer->result_id      = $result->id;
                            $answer->type           = $question->type;
                            $answer->option_id      = $option_id;
                            $answer->value          = $value;

                            $answer->save();
                        }
                    }
                }
            }
        }
    }

	public function notFound()
	{
		return view('frontend.campaigns.notfound');
	}

	public function answered()
	{
		return view('frontend.campaigns.answered');
	}

	public function create()
	{
		return view('frontend.campaigns.create');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'title' 		=> 'required|min:10',
			'description' 	=> 'required|min:15',
			'photo' 		=> 'mimes:jpeg,gif,bmp,png',
		]);

        $entry = new Campaign;

        $entry->user_id 		= Auth::guard('web')->user()->id;
        $entry->title 			= $request->title;
        $entry->description 	= $request->description;
        $entry->video 			= $request->video;
        $entry->respondents 	= $request->respondents ? 1 : 0;
        $entry->send_email 		= $request->send_email ? 1 : 0;
        $entry->same_computer 	= $request->same_computer ? 1 : 0;
        $entry->public 			= $request->public ? 1 : 0;
        $entry->active			= $request->active ? 1 : 0;

        // Uploading photo
        if ($request->hasFile('photo'))	{
            $file = $request->file('photo');

            if ($file->isValid()) {
                $path 	= 'uploads/campaigns/photos/' . base64_encode($entry->id) . '/';
                $name	= $file->getClientOriginalName();

                // deleting old if exists
                File::deleteDirectory($path);

                // creating directories
                File::makeDirectory($path . 'default', 0777, true, true);

                // Save
                $file->move($path . 'default/', $name);

                // Resize if needed
                if (Image::make($path . 'default/' . $name)->width() > 200) {
                    Image::make($path . 'default/' . $name)->widen(200)->save();
                }

                // Assign images
                $entry->photo = $path . 'default/' . $name;
            }
        }

        $entry->save();

        // Saving campaign tags
        if ($request->has('tags')) {
            $tags = explode(' ', $request->tags);

            foreach ($tags as $tag) {
                $entry_tag = new CampaignTag;

                $entry_tag->campaign_id     = $entry->id;
                $entry_tag->title 		    = $tag;

                $entry_tag->save();
            }
        }

        return redirect()
            ->route('campaigns.my')
            ->withCreated($entry->id);
	}

	public function edit($id)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id) {
			$tags = [];

			foreach ($entry->tags as $tag) {
				$tags[] = $tag->title;
			}

			$entry->tags = implode(' ', $tags);

            // Total user credits
            $user_credits = Auth::guard('web')->user()->credits()->get();

            $credits = 0;

            foreach ($user_credits as $user_credit) {
                $credits += (int)$user_credit->credits;
            }

			$entry->credits = $credits;

			return view('frontend.campaigns.edit', [
			    'entry' => $entry
            ]);
		} else {
            return redirect()->route('campaigns.my');
        }
	}

	public function questions($id)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id) {
			return view('frontend.campaigns.questions', [
                'entry' => $entry
            ]);
		} else {
            return redirect()->route('campaigns.my');
        }
	}

	public function results($id, Request $request)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id) {
			$filter 		= $request->filter;
			$questions 		= json_decode($request->questions);
			$bad_results 	= [ 0 ];
			$bad_questions 	= [ 0 ];

			if ((int)$filter == 1) {
				foreach ($entry->results as $key => $result) {
					$cool = 0;

					foreach ($questions as $question => $option) {
						$bad_questions[] = $question;

						foreach ($result->answers as $answer) {
							if ($answer->question_id == $question && $answer->option_id == $option) {
								$cool++;
							}
						}
					}

					if ($cool != count($questions)) {
						$bad_results[] = $result->id;
					}
				}
			}

			return view('frontend.campaigns.results', [
			    'entry'          => $entry,
                'bad_results'    => $bad_results,
                'bad_questions'  => $bad_questions
            ]);
		} else {
            return redirect()->route('campaigns.my');
        }
	}

	public function cross_tabulation($id, Request $request)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id && $request->has('question_first') && $request->has('question_second'))
		{
			$first = $entry->questions()->where('id', $request->question_first)->first();
			$second = $entry->questions()->where('id', $request->question_second)->first();

			$counts = [];
			$totals = [];
			$arr1 	= [];
			$arr2 	= [];

			foreach ($first->options as $foption) {
				foreach ($first->answers()->where('option_id', $foption->id)->get() as $fanswer) {
					foreach ($second->options as $soption){
						foreach ($second->answers()->where('option_id', $soption->id)->where('result_id', $fanswer->result_id)->get() as $sanswer) {
							if (isset($counts[$foption->id][$soption->id])) {
                                $counts[$foption->id][$soption->id]++;
                            } else {
                                $counts[$foption->id][$soption->id] = 1;
                            }

							if (isset($totals['y'][$soption->id])) {
                                $totals['y'][$soption->id] += 1;
                            } else {
                                $totals['y'][$soption->id] = 1;
                            }

							if (isset($totals['x'][$foption->id])) {
                                $totals['x'][$foption->id] += 1;
                            } else {
                                $totals['x'][$foption->id] = 1;
                            }

							$arr1[] = $foption->id;
							$arr2[] = $soption->id;
						}
					}
				}
			}

			$table 	= [];
			$kashi = new Kashi();

			foreach ($first->options as $foption) {
				$table[$foption->title] = [];

				foreach ($second->options as $soption) {
					if (isset($counts[$foption->id][$soption->id])) {
                        $table[$foption->title][$soption->title] = $counts[$foption->id][$soption->id];
                    }
				}
			}

			$results = $kashi->chiTest($table);
			$probability = $kashi->chiDist($results['chi'], $results['df']);

			// expected
			$observed = [];
			$expected = [];

			foreach ($first->options as $foption) {
				foreach ($second->options as $soption) {
					@$observed[$foption->id][$soption->id] = $counts[$foption->id][$soption->id] ? $counts[$foption->id][$soption->id] : 0;
					@$expected[$foption->id][$soption->id] = $totals['x'][$foption->id] * $totals['y'][$soption->id] / array_sum($totals['y']);
				}
			}

			$min_expected = 0;
			$cells_count  = 0;
			$cells 		  = 0;

			foreach ($expected as $e) {
				foreach ($e as $ee) {
					if ((int)$min_expected == 0) {
                        $min_expected = $ee;
                    }

					if ((int)$min_expected > (int)$ee && (int)$ee != 0) {
                        $min_expected = $ee;
                    }

					if ((int)$ee < 5) {
                        $cells_count++;
                    }

					$cells++;
				}
			}

			// likelihood
			$sum = 0;

			foreach ($observed as $key => $o){
				foreach ($o as $k => $oo) {
					if ($expected[$key][$k] && $oo)
						$sum += $oo * log( $oo / $expected[$key][$k] );
				}
			}

			$sum = 2 * $sum;

			$kashi = new Kashi();

			$lprobability = $kashi->chiDist($sum, $results['df']);

			$r = round($this->Correlation($arr1, $arr2), 3);

			$linear = (array_sum($totals['y']) - 1) * pow($r, 2);

			$linear_probability = $kashi->chiDist($linear, 1);

			return view('frontend.campaigns.cross_tabulation', [
                'entry'                 => $entry,
                'first'                 => $first,
                'second'                => $second,
                'counts'                => $counts,
                'totals'                => $totals,
                'results'               => $results,
                'probability'           => $probability,
                'likelihood'            => $sum,
                'lprobability'          => $lprobability,
                'linear'                => $linear,
                'linear_probability'    => $linear_probability,
                'cells_count'           => $cells_count,
                'min_expected'          => $min_expected,
                'cells'                 => $cells
			]);
		}

		return redirect()->route('campaigns.my');
	}

	public function regression_analysis($id, Request $request)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id && $request->has('questions')) {
			$main_question = 0;

			foreach ($request->questions as $question) {
				if ($question == $request->get('questions')[0]) {
                    $main_question = $entry->questions()->where('id', $question)->first();
                }

				$questions[] = $entry->questions()->where('id', $question)->first();
			}

			array_shift($questions);

			foreach ($questions as $question) {
				$vars = $question->correlation_variables($main_question);

				$question->correlation_coefficient = $this->Correlation($vars[0], $vars[1]);
				$question->b = $question->correlation_coefficient * ($question->standart_deviation() / $main_question->standart_deviation());
				$question->B = $question->b * $question->standart_deviation();
			}

			// main question B value
			$y = $main_question->average() - $main_question->options()->orderBy('id')->first()->id;
			$sum = 0;

			foreach ($questions as $question) {
				$sum += $question->b * ($question->average()  - $question->options()->orderBy('id')->first()->id);
			}

			$main_question->b = $y - $sum;
			$vars = $main_question->correlation_variables($main_question);
			$main_question->correlation_coefficient = $this->Correlation($vars[0], $vars[1]);

			return view('frontend.campaigns.regression', [
				'entry' => $entry,
				'questions' => $questions,
				'main_question' => $main_question
            ]);
		}

		return redirect()->route('campaigns.my');
	}

	public function correlation_analysis($id, Request $request)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id && $request->has('questions')) {
			$questions 	    = [];
			$arr1		    = [];
			$arr2		    = [];
			$spearman 	    = [];
			$pearson 	    = [];
			$totals 	    = [];
			$sig 		    = [];
			$new_questions  = [];

			foreach ($request->questions as $question) {
				$questions[] = $entry->questions()->where('id', $question)->first();
			}

			// checkbox
			foreach ($questions as $key => $question) {
				if ($question->type == 'check') {
					foreach ($question->options as $option)	{
						$new_question = new \ArrayObject();

						$new_question->id = time() . rand(1, 100);
						$new_question->real_id = $question->id;
						$new_question->option_id = $option->id;
						$new_question->title = $option->title;
						$new_question->type = 'checkbox';

						$new_questions[] = $new_question;
					}

					unset($questions[$key]);
				} elseif ($question->type == 'matrix') {
					foreach ($question->options()->where('matrix', 'y')->get() as $option) {
						$new_question = new \ArrayObject();

						$new_question->id = time() . rand(1, 100);
						$new_question->real_id = $question->id;
						$new_question->option_id = $option->id;
						$new_question->title = $option->title;
						$new_question->type = 'matrix';

						$new_questions[] = $new_question;
					}

					unset($questions[$key]);
				}
			}

			foreach ($new_questions as $new_question) {
				$questions[] = $new_question;
			}

			unset($new_questions);

			// regular
			foreach ($questions as $question) {
				foreach ($questions as $q) {
					foreach ($entry->results as $result) {
						if (($question->type != 'checkbox' && $q->type != 'checkbox' && $q->type != 'matrix' && $question->type != 'matrix' && $result->answers()->where('question_id', $question->id)->first() && $result->answers()->where('question_id', $q->id)->first()) || $question->type == 'checkbox' || $q->type == 'checkbox' || $q->type == 'matrix' || $question->type == 'matrix') 						{
							if ($question->type == 'checkbox') {
								$arr1[$question->id][$q->id][] = $result->answers()->where('question_id', $question->real_id)->where('option_id', $question->option_id)->first() ? 2 : 1;

								if (@!$totals[$question->id][$q->id]++) {
                                    $totals[$question->id][$q->id] = 1;
                                }
							} elseif ($question->type == 'matrix') {
								foreach ($result->answers()->where('question_id', $question->real_id)->where('option_id', $question->option_id)->where('type', 'matrix')->get() as $answer) {
									$arr1[$question->id][$q->id][] = $answer->value;

									if (@!$totals[$question->id][$q->id]++) {
                                        $totals[$question->id][$q->id] = 1;
                                    }
								}
							} else {
								foreach ($result->answers()->where('question_id', $question->id)->get() as $answer) {
									$arr1[$question->id][$q->id][] = $answer->option_id;

									if (@!$totals[$question->id][$q->id]++) {
                                        $totals[$question->id][$q->id] = 1;
                                    }
								}
							}

							if ($q->type == 'checkbox') {
								$arr2[$question->id][$q->id][] = $result->answers()->where('question_id', $q->real_id)->where('option_id', $q->option_id)->first() ? 2 : 1;
							} elseif ($q->type == 'matrix') {
								foreach ($result->answers()->where('question_id', $q->real_id)->where('option_id', $q->option_id)->where('type', 'matrix')->get() as $answer) {
									$arr2[$question->id][$q->id][] = $answer->value;
								}
							} else {
								foreach ($result->answers()->where('question_id', $q->id)->get() as $answer) {
									$arr2[$question->id][$q->id][] = $answer->option_id;
								}
							}
						}
					}
				}
			}

			foreach ($questions as $question) {
				foreach ($questions as $q) {
					$sp = new SpearmanCorrelation();

					$result = $sp->test($arr1[$question->id][$q->id], $arr2[$question->id][$q->id]);

					$spearman[$question->id][$q->id] 	= round($result, 3);
					$pearson[$question->id][$q->id] 	= round($this->Correlation($arr1[$question->id][$q->id], $arr2[$question->id][$q->id]), 3);
					$sig[$question->id][$q->id] 		= @round($this->getP($spearman[$question->id][$q->id], $totals[$question->id][$q->id]), 3);

					if (!$sig[$question->id][$q->id]) {
                        $sig[$question->id][$q->id] = '.';
                    }
				}
			}

			return view('frontend.campaigns.correlation', [
			    'entry'         => $entry,
                'questions'     => $questions,
                'correlation'   => [
                    'spearman'      => $spearman,
                    'pearson'       => $pearson
                ],
                'totals'        => $totals,
                'sig'           => $sig
            ]);
		}

		return redirect()->route('campaigns.my');
	}

	public function Correlation($arr1, $arr2)
	{
	    $correlation = 0;

	    $k = $this->SumProductMeanDeviation($arr1, $arr2);
	    $ssmd1 = $this->SumSquareMeanDeviation($arr1);
	    $ssmd2 = $this->SumSquareMeanDeviation($arr2);

	    $product = $ssmd1 * $ssmd2;

	    $res = sqrt($product);

	    @$correlation = $k / $res;

	    return $correlation;
	}

	private function getP($r, $n)
	{
		$t = $r / sqrt( (1 - pow($r, 2) ) / ($n - 2) );

		return $t;
	}

	private function t_p($n,$k,$r)
	{
		return $r*pow($n-$k-1,0.5)/pow(1-pow($r,2),0.5);
	}

	private function p_t($t,$df)
	{
		$p = $df/2;
		$x = 0.5+0.5*$t/pow(pow($t,2)+$df,0.5);
		$beta_gam = exp( -logBeta($p, $p) + $p * log($x) + $p * log(1.0 - $x) );
		return (2.0 * $beta_gam * betaFraction(1.0 - $x, $p, $p) / $p);
	}

	public function SumProductMeanDeviation($arr1, $arr2)
	{
	    $sum = 0;

	    $num = count($arr1);

	    for($i=0; $i<$num; $i++) {
	        $sum = $sum + $this->ProductMeanDeviation($arr1, $arr2, $i);
	    }

	    return $sum;
	}

	public function ProductMeanDeviation($arr1, $arr2, $item)
	{
	    return ($this->MeanDeviation($arr1, $item) * $this->MeanDeviation($arr2, $item));
	}

	public function SumSquareMeanDeviation($arr)
	{
	    $sum = 0;

	    $num = count($arr);

	    for($i=0; $i<$num; $i++) {
	        $sum = $sum + $this->SquareMeanDeviation($arr, $i);
	    }

	    return $sum;
	}

	public function SquareMeanDeviation($arr, $item)
	{
	    return $this->MeanDeviation($arr, $item) * $this->MeanDeviation($arr, $item);
	}

	public function SumMeanDeviation($arr)
	{
	    $sum = 0;

	    $num = count($arr);

	    for($i=0; $i<$num; $i++) {
	        $sum = $sum + $this->MeanDeviation($arr, $i);
	    }

	    return $sum;
	}

	public function MeanDeviation($arr, $item)
	{
	    $average = $this->Average($arr);

	    return @$arr[$item] - $average;
	}

	public function Average($arr)
	{
	    $sum = $this->Sum($arr);
	    $num = count($arr);

	    return $sum/$num;
	}

	public function Sum($arr)
	{
	    return array_sum($arr);
	}

	public function results_xlsx($id)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id) {
			Excel::create($this->_make_token($entry->title) . '.xlsx', function($excel) use ($entry) {
				$excel->setTitle('Anketos „'.$entry->title.'“ rezultatai');
			    $excel->setCreator('Apklausos internetu')->setCompany('ACVK');

				$excel->setDescription('Anketos rezultatai.');

				$excel->sheet('Anketos rezultatai', function ($sheet) use ($entry) {
					$sheet->loadView('frontend.campaigns.results_xlsx')->withEntry($entry);
				});

			})->download('xlsx');
		} else {
            return redirect()->route('campaigns.my');
        }
	}

    private function _make_token($title, $format='') {
        return mb_convert_case(implode('-', str_word_count(iconv('UTF-8', 'ASCII//TRANSLIT', str_replace(array("'", '"'), '', $title)), 1, $format)), MB_CASE_LOWER, 'UTF-8');
    }

	public function answers($id)
	{
		$entry = Campaign::find($id);

		if ($entry) {
			if ($entry->respondents) {
				$recommended = Campaign::where('advertise_results', '>', 0)
                    ->where('active', '=', 1)
                    ->where('id', '<>', $entry->id)
                    ->where('public', '=', 1)
                    ->orderBy('advertise_results', 'desc')
                    ->take(5)
                    ->get();


				return view('frontend.results.index', [
                    'entry'         => $entry,
                    'recommended'   => $recommended
                ]);
			} else {
                return view('frontend.results.private');
            }
		} else {
            return redirect()->route('home');
        }
	}

	public function add_question($id, $type)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id && in_array($type, $this->types) && $entry->active == 0) {
			return view('frontend.campaigns.add_question', [
                'entry'     => $entry,
                'type'      => $type
            ]);
		} else {
            return redirect()->route('campaigns.questions', $entry->id);
        }
	}

	public function edit_question($campaign_id, $question_id)
	{
		$c 	= Campaign::find($campaign_id);
		$cq = CampaignQuestion::find($question_id);

		if ($c && $c->user_id == Auth::guard('web')->user()->id && $cq && $cq->campaign_id == $c->id && $c->active == 0) {
			return view('frontend.campaigns.edit_question', [
                'entry'     => $c,
                'question'  => $cq,
                'type'      => $cq->type
            ]);
		} else {
            return redirect()->route('campaigns.questions', $c->id);
        }
	}

	public function store_question($id, $type, Request $request)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id && in_array($type, $this->types) && $entry->active == 0) {
			$validation = Validator::make($request->all(), [
				'title' 		=> 'required',
			]);

			if ($validation->fails()) {
				return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validation->messages());
			} else {
				$cq = new CampaignQuestion;

				$cq->title 			= $request->title;
				$cq->type 			= $type;
				$cq->campaign_id 	= $id;
				$cq->private 		= $request->private ? 1 : 0;
				$cq->required 		= $request->required ? 1 : 0;
				$cq->custom_answer 	= $request->custom_answer ? 1 : 0;
				$cq->note 			= $request->note;
				$cq->video 			= $request->video;

				$cq->save();

				if ($cq->type == 'matrix') {
					if ($request->has('option_x')) {
						foreach ($request->option_x as $value) {
							$qo = new CampaignQuestionOption;

							$qo->question_id = $cq->id;
							$qo->title = $value;
							$qo->matrix = 'x';

							$qo->save();
						}
					}

					if ($request->has('option_y')) {
						foreach ($request->option_y as $value) {
							$qo = new CampaignQuestionOption;

							$qo->question_id = $cq->id;
							$qo->title = $value;
							$qo->matrix = 'y';

							$qo->save();
						}
					}
				} else {
					if ($request->has('option')) {
						foreach ($request->option as $value) {
							$qo = new CampaignQuestionOption;

							$qo->question_id = $cq->id;
							$qo->title = $value;

							$qo->save();
						}
					}
				}

				// Uploading photo
				if ($request->hasFile('photo')) {
					$file = $request->file('photo');

					if ($file->isValid()) {
						$path 	= 'uploads/campaigns_questions/photos/' . base64_encode($cq->id) . '/';
						$name	= $file->getClientOriginalName();

						// deleting old if exists
						File::deleteDirectory($path);

						// creating directories
						File::makeDirectory($path . 'default', 0777, true, true);

						// Save
						$file->move($path . 'default/', $name);

						// Resize if needed
						if (Image::make($path . 'default/' . $name)->width() > 200)
							Image::make($path . 'default/' . $name)->widen(200)->save();

						// Assign images
						$cq->photo = $path . 'default/' . $name;
					}
				}

				$cq->save();

				return redirect()
                    ->route('campaigns.questions', $entry->id)
                    ->withEntry($entry);
			}
		} else {
            return redirect()->route('campaigns.questions', $entry->id);
        }
	}

	public function update_question($id, $question_id, Request $request)
	{
		$entry 	= Campaign::find($id);
		$cq 	= CampaignQuestion::find($question_id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id && $cq && $cq->campaign_id == $entry->id) {
			$validation = Validator::make($request->all(), [
				'title' 		=> 'required',
			]);

			if ($validation->fails()) {
				return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validation->messages());
			} else {
				$cq->title 			= $request->title;
				$cq->campaign_id 	= $id;
				$cq->private 		= $request->private ? 1 : 0;
				$cq->required 		= $request->required ? 1 : 0;
				$cq->custom_answer 	= $request->custom_answer ? 1 : 0;
				$cq->note 			= $request->note;
				$cq->video 			= $request->video;

				$cq->save();

				$cq->options()->delete();

				if ($cq->type == 'matrix') {
					if ($request->has('option_x')) {
						foreach ($request->option_x as $value) {
							$qo = new CampaignQuestionOption;

							$qo->question_id = $cq->id;
							$qo->title = $value;
							$qo->matrix = 'x';

							$qo->save();
						}
					}

					if ($request->has('option_y')) {
						foreach ($request->option_y as $value) {
							$qo = new CampaignQuestionOption;

							$qo->question_id = $cq->id;
							$qo->title = $value;
							$qo->matrix = 'y';

							$qo->save();
						}
					}
				} else {
					if ($request->has('option')) {
						foreach ($request->option as $value) {
							$qo = new CampaignQuestionOption;

							$qo->question_id = $cq->id;
							$qo->title = $value;

							$qo->save();
						}
					}
				}

				// Uploading photo
				if ($request->hasFile('photo')) {
					$file = $request->file('photo');

					if ($file->isValid()) {
						$path 	= 'uploads/campaigns_questions/photos/' . base64_encode($cq->id) . '/';
						$name	= $file->getClientOriginalName();

						// deleting old if exists
						File::deleteDirectory($path);

						// creating directories
						File::makeDirectory($path . 'default', 0777, true, true);

						// Save
						$file->move($path . 'default/', $name);

						// Resize if needed
						if (Image::make($path . 'default/' . $name)->width() > 200)
							Image::make($path . 'default/' . $name)->widen(200)->save();

						// Assign images
						$cq->photo = $path . 'default/' . $name;
					}
				}

				$cq->save();

				return redirect()
                    ->route('campaigns.questions', $entry->id)
                    ->withCreated($cq->id);
			}
		} else {
            return redirect()->route('campaigns.questions', $entry->id);
        }
	}

	public function copy($id)
	{
		$campaign = Campaign::find($id);

		if ($campaign && $campaign->user_id == Auth::guard('web')->user()->id) {
			$entry = new Campaign;

			$entry->user_id 		= Auth::guard('web')->user()->id;
			$entry->title 			= 'Kopija: ' . $campaign->title;
			$entry->description 	= $campaign->description;
			$entry->video 			= $campaign->video;
			$entry->respondents 	= $campaign->respondents;
			$entry->send_email 		= $campaign->send_email;
			$entry->same_computer 	= $campaign->same_computer;
			$entry->public 			= $campaign->public;

			$entry->save();

			// tags
			foreach ($campaign->tags as $tag) {
				$new_tag = new CampaignTag;

				$new_tag->campaign_id 	= $entry->id;
				$new_tag->title 		= $tag->title;

				$new_tag->save();
			}

			// copying photo
			if ($campaign->photo) {
				File::copyDirectory('uploads/campaigns/photos/' . base64_encode($campaign->id), 'uploads/campaigns/photos/' . base64_encode($entry->id));

				$entry->photo = str_replace(base64_encode($campaign->id), base64_encode($entry->id), $campaign->photo);
			}

			$entry->save();

			return redirect()
                ->route('campaigns.my')
                ->withCopied($entry->id);
		} else {
            return redirect()->route('campaigns.my');
        }
	}

	public function update($id, Request $request)
	{
		$entry = Campaign::find($id);

		if ($entry && $entry->user_id == Auth::guard('web')->user()->id) {
            // Total user credits
            $user_credits = Auth::guard('web')->user()->credits()->get();

            $available_credits = 0;

            foreach ($user_credits as $user_credit) {
                $available_credits += (int)$user_credit->credits;
            }

            $price = config('settings.featured_credits');

            // Rules
            $rules = [
                'title' => 'required|min:15',
                'description' => 'required|min:30',
                'photo' => 'mimes:jpeg,gif,bmp,png',
                'advertise_results' => 'enough',
            ];

            Validator::extend('enough', function ($attribute, $value, $parameters) use ($price, $available_credits, $request) {
                return ($request->advertise_results == 0 || ($available_credits > $price && $request->advertise_results > 0 && $request->advertise_results >= $price)) ? true : false;
            });

            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validation->messages());
            } else {
                $entry->user_id = Auth::guard('web')->user()->id;
                $entry->title = $request->title;
                $entry->description = $request->description;
                $entry->video = $request->video;
                $entry->respondents = $request->respondents ? 1 : 0;
                $entry->send_email = $request->send_email ? 1 : 0;
                $entry->same_computer = $request->same_computer ? 1 : 0;
                $entry->public = $request->public ? 1 : 0;
                $entry->active = $request->active ? 1 : 0;

                $entry->advertise_results = ($entry->advertise_credits > 0 || $request->advertise_results > 0) ? 1 : 0;

                if ($request->advertise_results > 0) {
                    $entry->advertise_credits = $request->advertise_results;
                }

                $entry->save();

                // Uploading photo
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');

                    if ($file->isValid()) {
                        $path = 'uploads/campaigns/photos/' . base64_encode($entry->id) . '/';
                        $name = $file->getClientOriginalName();

                        // deleting old if exists
                        File::deleteDirectory($path);

                        // creating directories
                        File::makeDirectory($path . 'default', 0777, true, true);

                        // Save
                        $file->move($path . 'default/', $name);

                        // Resize if needed
                        if (Image::make($path . 'default/' . $name)->width() > 200)
                            Image::make($path . 'default/' . $name)->widen(200)->save();

                        // Assign images
                        $entry->photo = $path . 'default/' . $name;
                    }
                }

                $entry->save();

                // Saving campaign tags
                if ($request->has('tags')) {
                    $entry->tags()->delete();

                    $tags = explode(' ', $request->tags);

                    foreach ($tags as $tag) {
                        $entry_tag = new CampaignTag;

                        $entry_tag->campaign_id = $entry->id;
                        $entry_tag->title = $tag;

                        $entry_tag->save();
                    }
                }

                // Saving credits
                if ($request->advertise_results > 0) {
                    UserCredit::create([
                        'user_id' => Auth::guard('web')->user()->id,
                        'credits' => -$request->advertise_results,
                        'description' => 'Reklamuokite anketą'
                    ]);
                }
            }

            return redirect()
                ->back()
                ->withUpdated($entry->id);
        }

        return redirect()->back();
    }

	public function destroy($id)
	{
		$entry = Campaign::find($id);

		if ($entry) {
			$path = 'uploads/campaigns/photos/' . base64_encode($entry->id) . '/';

			File::deleteDirectory($path);

			// Deleting
			$entry->delete();

			return redirect()
                ->route('campaigns.my')
                ->withDeleted($entry->id);
		} else {
            return Redirect::back();
        }
	}

	public function deactivate($id) {
		$entry = Campaign::find($id);

		if ($entry) {
			$entry->active = 0;

			$entry->results()->delete();

			$entry->save();
		}

        return redirect()->back();
	}

	public function destroy_question($id, $question_id)
	{
		$c 	= Campaign::find($id);
		$cq = CampaignQuestion::find($question_id);

		if ($c && $c->user_id == Auth::guard('web')->user()->id && $cq && $cq->campaign_id == $c->id && $c->active == 0) {
			$path = 'uploads/campaigns_questions/photos/' . base64_encode($cq->id) . '/';

			File::deleteDirectory($path);

			// Deleting
			$cq->delete();

			return redirect()
                ->route('campaigns.questions', $c->id)
                ->withDeleted($cq->id);
		} else {
            return redirect()->back();
        }
	}
}