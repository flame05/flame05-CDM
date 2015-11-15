<?php
class modello
{
    function crea($id_pro,$nome)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO modello (id_pro,nome) VALUES ('".$id_pro."','".$db->escape_string($nome)."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getModelli($id_pro)
    {
        global $db;
        $modelli = array();
        $sql = "SELECT * FROM modello WHERE id_pro = ".$id_pro." AND zen = 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $modelli[] = new zmodello($row['id'],$row['id_pro'],$row['nome']);
            }
        return $modelli;
        }
    return $modelli;
    }

    function elimina($id)
    {
        global $db;
        $sql = "UPDATE modello SET zen = 0 WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            $this->created = true;
    }
}
?>