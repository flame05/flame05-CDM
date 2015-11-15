<?php
class articolo
{
    function crea($id_modello,$id_parte,$pos_madre,$posizione,$nome,$colore)
    {
        global $db;
        $this->created = false;

        $sql = "INSERT INTO articolo (id_modello,id_parte,posizione,pos_madre,nome,colore,id_user,tempo) VALUES ('".$id_modello."','".$id_parte."','".$posizione."','".$pos_madre."','".$db->escape_string($nome)."','".$colore."','".$_SESSION['id']."','".time()."')";
        if($db->query($sql))
            $this->created = true;
    }

    function sposta($id,$indietro = false)
    {
        global $db;

        $sql = "SELECT id_modello,posizione,pos_madre FROM articolo WHERE id = ".$id." AND zen = 1 LIMIT 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $posizione = $row['posizione'];
            $pos_madre = $row['pos_madre'];
            $id_modello = $row['id_modello'];

            /* AL CONTRARIO */
            if($indietro)
            {
                if($pos_madre == 1 && $posizione == 1)
                    return false;
                if($posizione > 1)
                {
                    $np = $posizione-1;
                    $sql = "UPDATE articolo SET posizione = ".$np." WHERE id = ".$id." LIMIT 1";
                    if($db->query($sql))
                        return true;
                }
                else
                {
                    $np = $pos_madre-1;
                    $sql = "SELECT id FROM lavori WHERE ordine = ".$np." AND id_mamma = ".$id_modello." LIMIT 1";
                    $result = $db->query($sql);
                    $row = $result->fetch_assoc();
                    $id_new = $row['id'];
                    $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_new." AND zen = 1";
                    $result = $db->query($sql);
                    $row = $result->fetch_assoc();
                    $totale = $row['totale'];
                    $sql = "UPDATE articolo SET posizione = ".$totale.",pos_madre = ".$np." WHERE id = ".$id." LIMIT 1";
                    if($db->query($sql))
                        return true;
                return 'error';
                }
            }


            /* IN AVANTI */

            $sql = "SELECT id FROM lavori WHERE ordine = ".$pos_madre." AND zen = 1 AND id_mamma = ".$id_modello." LIMIT 1";
            $result = $db->query($sql);
            if($result->num_rows > 0)
            {
                $row = $result ->fetch_assoc();
                $id_mamma = $row['id'];
                $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1";
                $result = $db->query($sql);
                $row = $result->fetch_assoc();
                $totale = $row['totale'];

                if($totale == $posizione)
                {

                    $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1";
                    $result = $db->query($sql);
                    $row = $result->fetch_assoc();
                    $totale = $row['totale'];
                    if($totale == $pos_madre)
                    {
                        $sql = "UPDATE articolo SET finito = 1 WHERE id = ".$id." LIMIT 1";
                        $db->query($sql);
                    return 'finito';
                    }
                    else
                    {
                        $nuovo_ordine_m = $pos_madre + 1;
                        $sql = "UPDATE articolo SET posizione = 1,pos_madre = ".$nuovo_ordine_m." WHERE id = ".$id." LIMIT 1";
                        if($db->query($sql))
                            return true;
                    }
                }
                else
                {
                    $nuovo_ordine = $posizione + 1;
                    $sql = "UPDATE articolo SET posizione = ".$nuovo_ordine." WHERE id = ".$id." LIMIT 1";
                    if($db->query($sql))
                        return true;
                }
            }
            return 'error';
        }
    return false;
    }
}
?>