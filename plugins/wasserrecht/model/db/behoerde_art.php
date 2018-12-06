<?php
class BehoerdeArt extends WrPgObject {
    
    protected $tableName = 'fiswrv_behoerde_art';
    /**
     * {@inheritDoc}
     * @see WrPgObject::toString()
     */
    public function toString()
    {
        return parent::toString() . " Abkürzung: " . $this->getAbkuerzung();
    }
}
?>