<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignQuestion extends Model
{
    protected $table = 'campaigns_questions';

    public function options()
    {
        return $this->hasMany('App\CampaignQuestionOption', 'question_id');
    }

    public function answers()
    {
        return $this->hasMany('App\CampaignAnswer', 'question_id');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Campaign', 'campaign_id');
    }

    public function results()
    {
        return $this->campaign->results;
    }

    public function average()
    {
        return $this->answers()->avg('option_id');
    }

    public function standart_deviation($returnStr = false)
    {
        $str = '';

        $average = round($this->average() - $this->options()->orderBy('id')->first()->id, 0);

        $str .= '<h4>Standart Deviation</h4><br>';

        $str .= '<strong>Values:</strong> ';

        foreach ($this->results() as $result)
        {
            foreach ($result->answers()->where('question_id', $this->attributes['id'])->get() as $answer)
            {
                $str .= $answer->option_id . ' ';
            }
        }

        $str .= '<br>';

        $str .= '<strong>Total values:</strong> ' . count($this->answers) . '<br>';

        $str .= '<strong>Avg:</strong> ' . $average . '<br>';

        $top = 0;

        foreach ($this->results() as $result)
        {
            foreach ($result->answers()->where('question_id', $this->attributes['id'])->get() as $answer)
            {
                $top += pow(($answer->option_id - $average), 2);
            }
        }

        $str .= '<strong>DEDP</strong> (value[x] - average)^2: ';

        foreach ($this->results() as $result)
        {
            foreach ($result->answers()->where('question_id', $this->attributes['id'])->get() as $answer)
            {
                $str .= pow(($answer->option_id - $average), 2) . ' ';
            }
        }

        $str .= '<br>';

        $str .= '<strong>Sum of DEDP</strong>: ' . $top . '<br>';

        $sd = sqrt( ($top / count($this->answers)) );

        $str .= '<strong>Standart deviation</strong> sqrt([Sum of DEDP] / [Total values]): ' . $sd;

        if ($returnStr)
            return $str;
        else
            return $sd;
    }

    public function correlation_variables($main_question)
    {
        $x = [];
        $y = [];

        foreach ($this->results() as $result)
        {
            foreach ($result->answers()->where('question_id', $this->attributes['id'])->get() as $answer)
            {
                $x[] = $answer->option_id;
            }

            foreach ($result->answers()->where('question_id', $main_question->id)->get() as $answer)
            {
                $y[] = $answer->option_id;
            }
        }

        return [$x, $y];
    }
}
