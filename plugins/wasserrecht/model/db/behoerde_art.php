<?php
class BehoerdeArt extends WrPgObject {
    
    public static $tableName = 'fiswrv_behoerde_art';
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