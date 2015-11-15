<?php
class lavoro
{
    function crea($id_mamma,$nome)
    {
        global $db;
        $this->created = false;
        $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $ordine = $row['totale'] + 1;

        $sql = "INSERT INTO lavoro (id_mamma,nome,ordine) VALUES ('".$id_mamma."','".$db->escape_string($nome)."','".$ordine."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getLavoro($id_mamma)
    {
        global $db;
        $lavoro = array();
        $sql = "SELECT * FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1 ORDER BY ordine ASC";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $lavoro[] = new zlavoro($row['id'],$row['id_mamma'],$row['nome'],$row['ordine']);
            }
        return $lavoro;
        }
    return $lavoro;
    }

    function aggiorna($id,$nome,$ordine)
    {
    global $db;
        $sql = "UPDATE parte SET nome = '".$db->escape_string($nome)."',ordine = '".$ordine."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

}
?>