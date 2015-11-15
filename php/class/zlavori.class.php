<?php
class zlavori
{
    function __construct($id,$id_mamma,$nome,$colore,$ordine)
    {
        $this->id = $id;
        $this->id_modello = $id_mamma;
        $this->nome = $nome;
        $this->colore = $colore;
        $this->ordine = $ordine;
    }
}
?>