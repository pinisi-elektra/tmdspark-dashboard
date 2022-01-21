<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\FileUpload\InputFile;
use Illuminate\Foundation\Inspiring;
use Telegram;
use DB;
use Http;

/**
 * Class HelpCommand.
 */
class SparkCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'spark';

    /**
     * @var string Command Description
     */
    protected $description = '';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $response = $this->getUpdate();
        $text = $response->message->text;
        $explode = explode(" ", $text);
        $image = false;

        $this->replyWithChatAction(['action' => Actions::TYPING]);
    }
}
