<?php
class zprocesso
{
    function __construct($id,$nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }

    function reload()
    {
        global $db;
        $sql = "SELECT nome FROM processo WHERE id =".$this->id." LIMIT 1";
        $result = $db->query($sql);
        if($row = $result->fetch_assoc())
        {
            $this->nome = $row['nome'];
        }
    }
}
?>