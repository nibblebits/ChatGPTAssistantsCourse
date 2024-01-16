<?php

namespace App\Console\Commands;

use App\Assistants\BettyAssistant;
use Illuminate\Console\Command;

class AssistantTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assistant-test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bettyAssistant = new BettyAssistant();
        $conversation = $bettyAssistant->newConversation();
        $conversation->sendMessage('Hello betty set a calendar entry for 5th June 2025 at Cardiff airport 5 pm between friends!');
        $conversation->blockUntilResponded();
        
        print_r($conversation->getResponseData());
    }
}
