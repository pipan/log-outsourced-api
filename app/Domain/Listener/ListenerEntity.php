<?php

namespace App\Domain\Listener;

use Exception;
use Lib\Entity\Entity;

class ListenerEntity extends Entity
{
    public function __construct($data)
    {
        parent::__construct([
            'id' => $data['id'] ?? 0,
            'uuid' => $data['uuid'] ?? '',
            'project_id' => $data['project_id'] ?? 0,
            'name' => $data['name'] ?? '',
            'rules' => $data['rules'] ?? [],
            'handler_slug' => $data['handler_slug'] ?? '',
            'handler_values' => $data['handler_values'] ?? []
        ]);

        $validator = ListenerValidator::forSchema()->forEntity($this);
        if ($validator->fails()) {
            throw new Exception('Listener entity is invalid: ' . $this->getUuid());
        }
    }

    private function with($key, $value)
    {
        $data = $this->toArray();
        $data[$key] = $value;
        return new ListenerEntity($data);
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getUuid()
    {
        return $this->data['uuid'];
    }

    public function getProjectId()
    {
        return $this->data['project_id'];
    }

    public function getName()
    {
        return $this->data['name'];
    }
    
    public function getRules()
    {
        return $this->data['rules'];
    }

    public function getHandlerSlug()
    {
        return $this->data['handler_slug'];
    }

    public function getHandlerValues()
    {
        return $this->data['handler_values'];
    }

    public function withName($value)
    {
        return $this->with('name', $value);
    }

    public function withRules($value)
    {
        return $this->with('rules', $value);
    }

    public function withHandlerSlug($value)
    {
        return $this->with('handler_slug', $value);
    }

    public function withHandlerValues($value)
    {
        return $this->with('handler_values', $value);
    }
}