<?php
class Node implements JsonSerializable
{
	private $id;
	private $name;
	private $type_id;
	private $weight;
	private $formated_name;
	private $count_associated_relations;	

	public function __construct (int $id, string $name, int $type_id, int $weight, string $formated_name)
	{
		$this->id 							= $id;
		$this->name 						= $name;
		$this->type_id 						= $type_id;
		$this->weight 						= $weight;
		$this->formated_name 				= $formated_name;
		$this->count_associated_relations 	= 0;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getTypeId(): ?int
	{
		return $this->type_id;
	}

	public function getWeight(): ?int
	{
		return $this->weight;
	}

	public function getFormatedName(): ?string
	{
		return $this->formated_name;
	}

	public function incAssociatedRelations(): self
	{
		++$count_associated_relations;

		return $this;
	}

	public function decAssociatedRelations(): self
	{
		--$count_associated_relations;

		return $this;
	}

    public function jsonSerialize()
    {
        return array(
			"id" => $this->id,
			"name" => $this->name,
			"type_id" => $this->type_id,
			"weight" => $this->weight,
			"formated_name" => $this->formated_name,
        );
    }
}

?>