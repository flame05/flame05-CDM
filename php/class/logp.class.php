<?php
class logp
{
    function crea($id_articolo,$id_lavoro)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO logp (id_articolo,id_lavoro,tempo,id_user) VALUES ('".$id_articolo."','".$id_lavoro."','".time()."','".$_SESSION['id']."')";
        if($db->query($sql))
            $this->created = true;
    }
}
?>