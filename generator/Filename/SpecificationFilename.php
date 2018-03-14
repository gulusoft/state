<?php
class SpecificationFilename
{
    protected $fileName = "";
    
    /**
     * @return string
     */
    public function __toString()
    {
        if($this->fileName) {
            return $this->fileName;
        }
        return __DIR__ . '/../../build/specification.xml';
    }
    
    public function __construct($fileName = "") {
        $this->fileName = $fileName;
    }
}
