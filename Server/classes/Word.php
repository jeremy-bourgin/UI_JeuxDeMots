<?php

class Word extends Node
{
	private $definition;
	private $node_types;
	private $relation_types;
	private $relation_types_index;
	private $nodes;
	private $nodes_index;

	public function __construct (int $id, string $name, int $type_id, int $weight, string $formated_name, Definition $definition)
	{
		parent::__construct($id, $name, $type_id, $weight, $formated_name);
		$this->definition = $definition;
		$this->node_types = array();
		$this->relation_types = array();
		$this->relation_types_index = array();
		$this->nodes = array();
		$this->nodes_index = array();
	}

	public function getDefinition(): ?Definition
	{
		return $this->definition;
	}

	public function findNodeById(int $id): ?Node
	{
		$index = binary_search($this->nodes_index, $id);

		if ($index === false)
		{
			return null;
		}

		return $this->nodes[$index];
	}

	public function findRelationTypeById(int $id): ?RelationType
	{
		$index = binary_search($this->relation_types_index, $id);

		if ($index === false)
		{
			return null;
		}

		return $this->relation_types[$index];
	}
	
	public function addNodeType(NodeType $node_type): self
	{
		$this->node_types[] = $node_type;
		
		return $this;
	}

	public function addRelationType(RelationType $relation_type): self
	{
		$this->relation_types[] = $relation_type;
		$this->relation_types_index[] = $relation_type->getId();
		
		return $this;
	}

	public function getNodes(): ?array
	{
		return $this->nodes;
	}

	public function addNode(Node $node): self
	{
		$this->nodes[] = $node;
		$this->nodes_index[] = $node->getId();

		return $this;
	}

    public function jsonSerialize()
    {
		$array = parent::jsonSerialize();
		$array["definition"] = $this->definition;
		$array["node_types"] = $this->node_types;
		$array["relation_types"] = $this->relation_types;
		$array["nodes"] = $this->nodes;
		
		return $array;
    }
}
