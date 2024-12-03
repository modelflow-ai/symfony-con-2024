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
