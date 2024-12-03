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
