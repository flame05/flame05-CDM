<?php
class parte
{
    function crea($id_modello,$nome)
    {
        global $db;
        $this->created = false;

        $sql = "SELECT count(*) AS totale FROM parte WHERE id_modello = ".$id_modello." AND zen = 1";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $ordine = $row['totale'] + 1;


        $sql = "INSERT INTO parte (id_modello,nome,ordine) VALUES ('".$id_modello."','".$db->escape_string($nome)."','".$ordine."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getParti($id_modello)
    {
        global $db;
        $parti = array();
        $sql = "SELECT * FROM parte WHERE id_modello = ".$id_modello." AND zen = 1 ORDER BY ordine ASC";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $parti[] = new zparte($row['id'],$row['id_modello'],$row['nome'],$row['ordine']);
            }
        return $parti;
        }
    return $parti;
    }

    function getModello($id_modello)
    {
        global $db;
        $sql = "SELECT nome FROM modello WHERE id = ".$id_modello." LIMIT 1";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        return $row['nome'];
    }

    function aggiorna($id,$nome,$ordine)
    {
    global $db;
        $sql = "UPDATE parte SET nome = '".$db->escape_string($nome)."',ordine = '".$ordine."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

    function elimina($id)
    {
        global $db;
        $sql = "UPDATE parte SET zen = 0 WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

    function loadParte($id)
    {
    global $db;
        $sql = "SELECT * FROM parte WHERE id = ".$id." LIMIT 1";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
        {
            $zu = array();
            $zu['id'] = $row['id'];
            $zu['nome'] = $row['nome'];
            $zu['ordine'] = $row['ordine'];
        }
    return $zu;
    }
}
?>