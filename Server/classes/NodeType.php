<?php
class NodeType implements JsonSerializable
{
	private $id;
	private $name;
	
	private static $blacklist = array(
		6,
		8,
		200
	);

	public function __construct (int $id, string $name)
	{
		$this->id = $id;
		$this->name = $name; 
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

    public function jsonSerialize()
    {
        return array(
        	"id" => $this->id,
        	"name" => $this->name
        );
    }

    public static function isBlacklisted (int $id): ?bool
    {
    	return in_array($id, self::$blacklist, true);
    }

}

?>