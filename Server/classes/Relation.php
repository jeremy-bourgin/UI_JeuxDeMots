<?php
abstract class Relation implements JsonSerializable
{
	private $id;
	private $node;
	private $weight;
	private $is_out;

	public function __construct (int $id, Node $node, int $weight, bool $is_out)
	{
		$this->id = $id;
		$this->node = $node; 
		$this->weight = $weight; 
		$this->is_out = $is_out;

		$this->node->incAssociatedRelations();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getNode(): ?Node
	{
		return $this->node;
	}

	public function getWeight(): ?int
	{
		return $this->weight;
	}
	
    public function jsonSerialize()
    {
        return array(
			"id" => $this->id,
			"node_id" => $this->node->getId(),
			"weight" => $this->weight,
			"is_out" => $this->is_out
        );
    }
}
