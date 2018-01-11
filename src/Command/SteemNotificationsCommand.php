<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use MongoDB\Client as MongoDBClinet;
use MongoDB\BSON\UTCDateTime;

class SteemNotificationsCommand extends Command
{
    protected static $defaultName = 'steem:notifications';

    protected function configure()
    {
        $this
            ->setDescription('get notifications and send email.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $db = new MongoDBClinet('mongodb://steemit:steemit@mongo1.steemdata.com:27017/SteemData');
        $comments = $db->SteemData->Comments;

        $cursor = $comments->find(
            [
                'parent_author' => 'ety001',
                'created' => ['$gt' => new UTCDateTime((time() - 3*24*3600) * 1000)],
            ]
        );

        foreach ($cursor as $document) {
            echo $document['_id'], "\n";
        }

        $io->success('Send success');
    }
}
