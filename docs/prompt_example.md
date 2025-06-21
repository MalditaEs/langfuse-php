# Langfuse PHP - Prompt Management

This document demonstrates how to use the prompt retrieval functionality in the Langfuse PHP SDK.

## Basic Usage

```php
use Langfuse\Config\Config;
use Langfuse\Client\LangfuseClient;
use Langfuse\LangfuseManager;

// Initialize Langfuse
$config = new Config([
    Config::PUBLIC_KEY => 'your-public-key',
    Config::SECRET_KEY => 'your-secret-key',
]);

$client = new LangfuseClient($config);
$langfuse = new LangfuseManager($client);

// Get production prompt (default)
$prompt = $langfuse->getPrompt("Extract claims from text");

// Get by label
$prompt = $langfuse->getPrompt("Extract claims from text", "production");
$prompt = $langfuse->getPrompt("Extract claims from text", "latest");

// Get by version (not recommended as it requires code changes)
$prompt = $langfuse->getPrompt("Extract claims from text", null, 1);
```

## Working with Prompts

The `Prompt` object provides several useful methods:

```php
// Get prompt content
$promptText = $prompt->getPrompt();

// Get prompt metadata
$name = $prompt->getName();
$version = $prompt->getVersion();
$id = $prompt->getId();

// Compile prompt with variables
$variables = [
    'topic' => 'climate change',
    'format' => 'bullet points'
];
$compiledPrompt = $prompt->compile($variables);
```

## Integration with LLM Calls

You can integrate prompt management with your existing trace and generation monitoring:

```php
// Fetch the prompt
$prompt = $langfuse->getPrompt("Summarization prompt", "production");

// Use it with generation tracking
$result = $langfuse->withGeneration(
    'summarize-article',
    'gpt-4',
    ['role' => 'system', 'content' => $prompt->compile(['max_words' => 100])],
    function() use ($prompt) {
        // Your LLM call here
        return callOpenAI($prompt->getPrompt());
    }
);
```

## Error Handling

The `getPrompt` method will throw exceptions if:
- The prompt name doesn't exist
- Authentication fails
- Network errors occur

```php
try {
    $prompt = $langfuse->getPrompt("non-existent-prompt");
} catch (\Exception $e) {
    // Handle error
    echo "Failed to fetch prompt: " . $e->getMessage();
}
```