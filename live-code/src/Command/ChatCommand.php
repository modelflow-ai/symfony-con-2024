<?php

namespace App\Command;

use ModelflowAi\Chat\AIChatRequestHandlerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:chat',
    description: 'Command to interact with the LLM.',
)]
class ChatCommand extends Command
{
    public function __construct(
        private AIChatRequestHandlerInterface $chatRequestHandler,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $question = $io->ask('You');

        $response = $this->chatRequestHandler
            ->createRequest()
            ->addUserMessage($question)
            ->build()
            ->execute();

        $io->success($response->getMessage()->content);

        return Command::SUCCESS;
    }
}
