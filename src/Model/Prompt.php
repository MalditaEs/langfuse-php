<?php

declare(strict_types=1);

namespace Langfuse\Model;

use Webmozart\Assert\Assert;

class Prompt
{
    private string $id;
    private string $name;
    private $prompt;
    private ?array $config;
    private int $version;
    private ?string $type;
    private ?array $labels;
    private string $createdAt;
    private string $updatedAt;
    private string $createdBy;
    private string $projectId;

    public function __construct(array $data)
    {
        Assert::keyExists($data, 'id');
        Assert::keyExists($data, 'name');
        Assert::keyExists($data, 'prompt');
        Assert::keyExists($data, 'version');
        Assert::keyExists($data, 'createdAt');
        Assert::keyExists($data, 'updatedAt');
        Assert::keyExists($data, 'createdBy');
        Assert::keyExists($data, 'projectId');

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->prompt = $data['prompt'];
        $this->config = $data['config'] ?? null;
        $this->version = $data['version'];
        $this->type = $data['type'] ?? null;
        $this->labels = $data['labels'] ?? null;
        $this->createdAt = $data['createdAt'];
        $this->updatedAt = $data['updatedAt'];
        $this->createdBy = $data['createdBy'];
        $this->projectId = $data['projectId'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrompt()
    {
        return $this->prompt;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getLabels(): ?array
    {
        return $this->labels;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function compile(?array $variables = null)
    {
        if ($variables === null || empty($variables)) {
            return $this->prompt;
        }

        // Handle string prompt
        if (is_string($this->prompt)) {
            $compiled = $this->prompt;
            foreach ($variables as $key => $value) {
                $compiled = str_replace('{{' . $key . '}}', (string) $value, $compiled);
            }
            return $compiled;
        }

        // Handle array prompt (chat format)
        if (is_array($this->prompt)) {
            $compiled = [];
            foreach ($this->prompt as $message) {
                if (isset($message['role']) && isset($message['content'])) {
                    $content = $message['content'];
                    foreach ($variables as $key => $value) {
                        $content = str_replace('{{' . $key . '}}', (string) $value, $content);
                    }
                    $compiled[] = [
                        'role' => $message['role'],
                        'content' => $content
                    ];
                }
            }
            return $compiled;
        }

        return $this->prompt;
    }
}