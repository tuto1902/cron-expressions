<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class CronController extends Controller
{
    public function index()
    {
        return view('cron.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'schedule' => 'required'
        ]);

        $schedule = $request->input('schedule');

        $apiKey = config('app.openai_key');
        $client = OpenAI::client($apiKey);

        $prompt = "Transform \"$schedule\" into a standard Cron expression";

        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 256,
            'n' => 1,
            'stop' => '\n',
            'temperature' => 0.7
        ]);

        $cronExpression = trim($result['choices'][0]['text']);

        if (!preg_match('/^(\*|[0-9,\-\/\*]+)\s+(\*|[0-9,\-\/\*]+)\s+(\*|[0-9,\-\/\*]+)\s+(\*|[0-9,\-\/\*]+)\s+(\*|[0-9,\-\/\*]+)$/', $cronExpression)) {
            return redirect()->back()->with(['error' => 'Invalid Cron Expression', 'cronExpression' => $cronExpression, 'schedule' => $schedule]);
        }

        return redirect()->back()->with(['cronExpression' => $cronExpression, 'schedule' => $schedule]);
    }
}
