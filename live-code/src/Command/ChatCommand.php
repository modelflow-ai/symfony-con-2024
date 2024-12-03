<?php

namespace App\Command;

use ModelflowAi\Chat\AIChatRequestHandlerInterface;
use ModelflowAi\Chat\Request\Message\AIChatMessage;
use ModelflowAi\Chat\Request\Message\AIChatMessageRoleEnum;
use ModelflowAi\Chat\Response\AIChatResponseStream;
use ModelflowAi\Integration\Symfony\Criteria\ModelCriteria;
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

        $messages = [];

        while(true) {
            $question = $io->ask('You');
            if('exit' === $question) {
                break;
            }

            /** @var AIChatResponseStream $response */
            $response = $this->chatRequestHandler
                ->createRequest(...$messages)
                ->addUserMessage($question)
                ->addCriteria(ModelCriteria::LLAMA3_2)
                ->streamed()
                ->build()
                ->execute();

            foreach ($response->getMessageStream() as $message) {
                $io->write($message->content);
            }

            $io->newLine(2);

            $messages = $response->getRequest()->getMessages();
            $messages[] = new AIChatMessage(AIChatMessageRoleEnum::ASSISTANT, $response->getMessage()->content);
        }

        return Command::SUCCESS;
    }
}
