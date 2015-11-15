<?php
class lavori
{
    function crea($id_mamma,$nome,$colore)
    {
        global $db;
        $this->created = false;
        $sql = "SELECT count(*) AS totale FROM lavori WHERE id_mamma = ".$id_mamma." AND zen = 1";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $ordine = $row['totale'] + 1;

        $sql = "INSERT INTO lavoro (id_mamma,nome,colore,ordine) VALUES ('".$id_mamma."','".$db->escape_string($nome)."','".$db->escape_string($colore)."','".$ordine."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getLavori($id_mamma)
    {
        global $db;
        $lavori = array();
        $sql = "SELECT * FROM lavori WHERE id_mamma = ".$id_mamma." AND zen = 1 ORDER BY ordine ASC";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $lavori[] = new zlavori($row['id'],$row['id_mamma'],$row['nome'],$row['colore'],$row['ordine']);
            }
        return $lavori;
        }
    return $lavori;
    }

    function aggiorna($id,$nome,$colore,$ordine)
    {
    global $db;
        $sql = "UPDATE parte SET nome = '".$db->escape_string($nome)."',ordine = '".$ordine."',colore = '".$db->escape_string($colore)."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }
}
?>