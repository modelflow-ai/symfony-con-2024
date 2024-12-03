# Live Coding Session

This folder contains the code for the live coding session at the Symfony Con 2024 in Vienna.

## Step 1: Create New Symfony Project

Install a new Symfony project using the Symfony Skeleton:

```bash
composer create-project symfony/skeleton symfony-con-2024
cd symfony-con-2024
```

## Step 2: Install dependencies

Install the dependencies for the project:

```bash
# 1. Install the Modelflow AI Symfony Bundle and the chat package
composer require modelflow-ai/symfony-bundle modelflow-ai/chat

# Install the Modelflow AI Adapters: Here OpenAI and Ollama
composer require modelflow-ai/openai-adapter modelflow-ai/ollama-adapter

# Install Symfony Maker Bundle
composer require symfony/maker-bundle --dev
```

## Step 3: Configure Modelflow AI with OpenAI Adapter

As there is currently no symfony flex recipe for the Modelflow AI Bundle, you need to configure the bundle manually.

```php
<?php

return [
    ...
    ModelflowAi\Integration\Symfony\ModelflowAiBundle::class => ['all' => true],
];
```

```yaml
# config/packages/modelflow_ai.yaml

modelflow_ai:
    providers:
        openai:
            enabled: true
            credentials:
                api_key: '%env(OPENAI_API_KEY)%'

    adapters:
        gpt4o:
            enabled: true
```

# Step 4: Create a Chatbot Command

Create a new command to interact with the chatbot:

```bash
bin/console make:command app:chat
```

Add the service `ModelflowAi\Chat\AIChatRequestHandlerInterface` to the constructor of the command:

```php
public function __construct(
    private AIChatRequestHandlerInterface $chatRequestHandler,
) {
    parent::__construct();
}
```

Implement the command logic:

```php
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
```

Run the command and ask the chatbot a question:

```bash
bin/console app:chat
```

## Step 5: Add chat conversion handling

Add a messages array to the execute method:

```php
protected function execute(InputInterface $input, OutputInterface $output): int
{
    $io = new SymfonyStyle($input, $output);

    $messages = [];

    while(true) {
        $question = $io->ask('You');
        if('exit' === $question) {
            break;
        }

        $response = $this->chatRequestHandler
            ->createRequest(...$messages)
            ->addUserMessage($question)
            ->build()
            ->execute();

        $io->success($response->getMessage()->content);

        $messages = $response->getRequest()->getMessages();
        $messages[] = new AIChatMessage(AIChatMessageRoleEnum::ASSISTANT, $response->getMessage()->content);
    }

    return Command::SUCCESS;
}
```

This will allow the chatbot to remember the conversation history and provide more contextually relevant responses.
