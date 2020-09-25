<?php

namespace App\Domain\Listener;

class ListenerEntity
{
    protected $data;

    public function __construct($id, $uuid, $projectId, $name, $rules, $handlerSlug, $handlerValues)
    {
        $this->data = [
            'id' => $id,
            'uuid' => $uuid,
            'project_id' => $projectId,
            'name' => $name,
            'rules' => $rules,
            'handler_slug' => $handlerSlug,
            'handler_values' => $handlerValues
        ];
    }

    public static function fromArray($properties)
    {
        return new ListenerEntity(
            $properties['id'],
            $properties['uuid'],
            $properties['project_id'],
            $properties['name'],
            $properties['rules'],
            $properties['handler_slug'],
            $properties['handler_values']
        );
    }

    public function getId()
    {
        return $this->data['id'] ?? 0;
    }

    public function setId($id)
    {
        return self::fromArray(['id' => $id] + $this->toArray());
    }

    public function getUuid()
    {
        return $this->data['uuid'] ?? '';
    }

    public function getProjectId()
    {
        return $this->data['project_id'] ?? 0;
    }

    public function getName()
    {
        return $this->data['name'] ?? '';
    }
    
    public function getRules()
    {
        return $this->data['rules'] ?? [];
    }

    public function getHandlerSlug()
    {
        return $this->data['handler_slug'] ?? '';
    }

    public function getHandlerValues()
    {
        return $this->data['handler_values'] ?? [];
    }

    public function toArray()
    {
        return $this->data;
    }
}