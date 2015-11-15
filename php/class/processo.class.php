<?php
class processo
{
    function crea($nome)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO processo (nome) VALUES ('".$db->escape_string($nome)."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getProcessi()
    {
        global $db;
        $processi = array();
        $sql = "SELECT * FROM processo WHERE zen = 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $processi[] = new zprocesso($row['id'],$row['nome']);
            }
        return $processi;
        }
    return $processi;
    }

    function elimina($id)
    {
        global $db;
        $sql = "UPDATE processo SET zen = 0 WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            $this->created = true;
    }

    function gPro($id)
    {
        $pro = new zprocesso($id,'');
        $pro->reload();
    return $pro;
    }
}
?>