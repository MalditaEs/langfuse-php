<?php

require_once 'vendor/autoload.php';

use Langfuse\Config\Config;
use Langfuse\Client\LangfuseClient;
use Langfuse\LangfuseManager;

// Test script for prompt retrieval functionality
try {
    // Initialize with your Langfuse credentials
    $config = new Config([
        Config::PUBLIC_KEY => 'your-public-key',
        Config::SECRET_KEY => 'your-secret-key',
        Config::LANGFUSE_BASE_URI => 'https://cloud.langfuse.com/',
    ]);

    $client = new LangfuseClient($config);
    $langfuse = new LangfuseManager($client);

    echo "Testing Langfuse Prompt Retrieval\n";
    echo "=================================\n\n";

    // Example 1: Get production prompt (default)
    echo "1. Getting production prompt:\n";
    try {
        $prompt = $langfuse->getPrompt("Extract claims from text");
        echo "   Name: " . $prompt->getName() . "\n";
        echo "   Version: " . $prompt->getVersion() . "\n";
        echo "   Prompt: " . substr($prompt->getPrompt(), 0, 50) . "...\n\n";
    } catch (\Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n\n";
    }

    // Example 2: Get prompt by label
    echo "2. Getting prompt by label (production):\n";
    try {
        $prompt = $langfuse->getPrompt("Extract claims from text", "production");
        echo "   Name: " . $prompt->getName() . "\n";
        echo "   Version: " . $prompt->getVersion() . "\n";
        echo "   Prompt: " . substr($prompt->getPrompt(), 0, 50) . "...\n\n";
    } catch (\Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n\n";
    }

    // Example 3: Get prompt by label (latest)
    echo "3. Getting prompt by label (latest):\n";
    try {
        $prompt = $langfuse->getPrompt("Extract claims from text", "latest");
        echo "   Name: " . $prompt->getName() . "\n";
        echo "   Version: " . $prompt->getVersion() . "\n";
        echo "   Prompt: " . substr($prompt->getPrompt(), 0, 50) . "...\n\n";
    } catch (\Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n\n";
    }

    // Example 4: Get prompt by version
    echo "4. Getting prompt by version (1):\n";
    try {
        $prompt = $langfuse->getPrompt("Extract claims from text", null, 1);
        echo "   Name: " . $prompt->getName() . "\n";
        echo "   Version: " . $prompt->getVersion() . "\n";
        echo "   Prompt: " . substr($prompt->getPrompt(), 0, 50) . "...\n\n";
    } catch (\Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n\n";
    }

    // Example 5: Test prompt compilation with variables
    echo "5. Testing prompt compilation with variables:\n";
    try {
        // Assuming we have a prompt with {{variable}} placeholders
        $prompt = $langfuse->getPrompt("Example prompt with variables", "production");
        $compiled = $prompt->compile(['name' => 'John', 'task' => 'summarization']);
        echo "   Compiled prompt: " . substr($compiled, 0, 100) . "...\n\n";
    } catch (\Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n\n";
    }

} catch (\Exception $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";