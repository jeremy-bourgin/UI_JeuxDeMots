<?php
class Definition implements JsonSerializable
{
	private $definitions;

	public function __construct (array $definitions)
	{
		$this->definitions = $definitions; 
	}

	public function getDefintions(): ?array
	{
		return $this->definitions;
	}

	public function jsonSerialize()
    {
        return $this->definitions;
    }
}

?>