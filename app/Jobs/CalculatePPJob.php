<?php

namespace App\Jobs;

use App\Models\DailyChallenge;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Process;

class CalculatePPJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Collection $unserialisedScores, public string $beatmapId, public int $rulesetId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $serialisedScores = $this->serialiseScores();
        $ppValues = $this->calculatePPValues($serialisedScores);

        // Upsert is most efficient way of doing this within eloquent even though it's kinda ugly
        Score::upsert($ppValues, uniqueBy: ['score_id'], update: ['pp', 'pp_version']);
    }

    private function translateRulesetId(int $rulesetId) : string
    {
        if ($rulesetId == 0) return 'osu';
        if ($rulesetId == 1) return 'taiko';
        if ($rulesetId == 2) return 'catch';
        if ($rulesetId == 3) return 'mania';
        throw new \Exception('Unknown ruleset id');
    }

    private function serialiseScores() : Collection
    {
        $serialisedScores = collect();

        foreach($this->unserialisedScores as $scoreAttributes) {
            $score = new Score();
            $score->setRawAttributes($scoreAttributes, true);
            $serialisedScores->add($score);
        }

        return $serialisedScores;
    }

    private function calculatePPValues(Collection $scores) : array
    {
        $mode = $this->translateRulesetId($this->rulesetId);
        $values = [];

        foreach ($scores as $score) {
            $switches = [
                "--mehs {$score->mehs}",
                "--goods {$score->goods}",
                "--combo {$score->combo}",
                "--large-tick-misses {$score->large_tick_misses}",
                "--slider-tail-misses {$score->slider_tail_misses}",
                "--accuracy {$score->accuracy}",
                "--misses {$score->misses}",
                "--json"
            ];

            $mods = json_decode($score->mods);
            foreach ($mods as $mod) {
                $switches[] = "--mod $mod->acronym";

                if (isset($mod->settings)) {
                    foreach ($mod->settings as $modsetting => $settingvalue) {
                        $switches[] = "--mod-option $modsetting=$settingvalue";
                    }
                }
            }

            $result = Process::run("dotnet /opt/osu-tools/PerformanceCalculator.dll simulate $mode storage/app/private/beatmaps/{$this->beatmapId}.osu " . implode(' ', $switches));

            $performanceData = json_decode($result->output());
            $values[] = [
                'score_id' => $score->score_id,
                'user_id' => $score->user_id,
                'daily_challenge' => $score->daily_challenge,
                'score' => $score->score,
                'accuracy' => $score->accuracy,
                'placement' => $score->placement,
                'pp' => $performanceData->performance_attributes->pp,
                'pp_version' => config('app.osu_pp.version'),
            ];
        }

        return $values;
    }

    public $timeout = 10000;
}
