<?php
class RelationType implements JsonSerializable
{
	private $id;
	private $name;
	private $gpname;
	private $help;
	private $associated_relations;

	private static $blacklist = array(
		12, 
		29,
		45,
		46,
		47,
		48,
		66,
		118,
		200,
		1000,
		1001,
		1002,
		2001
	);

	public function __construct (int $id, string $name, string $gpname, string $help)
	{
		$this->id = $id;
		$this->name = $name; 
		$this->gpname = $gpname;
		$this->help = $help;
		$this->associated_relations = array();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getGpName(): ?string
	{
		return $this->gpname;
	}

	public function getHelp(): ?string
	{
		return $this->help;
	} 

	public function getAssociatedRelations(): ?array
	{
		return $this->associated_relations;
	}

	public function addAssociatedRelation(Relation $relation): self
	{
		$this->associated_relations[] = $relation;
		
		return $this;
	}

    public function jsonSerialize()
    {
        return array(
			"id" => $this->id,
			"name" => $this->name,
			"gpname" => $this->gpname,
			"help" => $this->help,
			"associated_relations" => $this->associated_relations
        );
    }

    public static function isBlacklisted (int $id): ?bool
    {
    	return in_array($id, self::$blacklist, true);
    }

}
?>