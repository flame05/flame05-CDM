<?php
class utente
{
    function crea($user,$pass,$livello,$nome)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO utenti (username,password,livello,nome) VALUES ('".$db->escape_string($user)."','".$db->escape_string($pass)."','".$livello."','".$db->escape_string($nome)."')";
        if($db->query($sql))
            $this->created = true;
    }

    function elimina($id)
    {
        global $db;
        $sql = "UPDATE utenti SET zen = 0 WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

    function aggiorna($id,$pass,$livello,$nome,$user,$zen)
    {
        global $db;
        $sql = "UPDATE utenti SET password = '".$db->escape_string($pass)."',livello = '".$livello."',username = '".$db->escape_string($user)."',nome = '".$db->escape_string($nome)."',zen = '".$zen."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

    function getUsers()
    {
        global $db;
        $utenti = array();
        $sql = "SELECT * FROM utenti WHERE zen = 1 AND livello <= ".$_SESSION['livello']."";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
        {
            $utenti[] = new zutente($row['id'],$row['username'],$row['livello'],$row['nome']);
        }
    return $utenti;
    }

    function loadUser($id)
    {
    global $db;
        $sql = "SELECT * FROM utenti WHERE id = ".$id." AND zen = 1 AND livello <= ".$_SESSION['livello']." LIMIT 1";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
        {
            $zu = array();
            $zu['id'] = $row['id'];
            $zu['username'] = $row['username'];
            $zu['password'] = $row['password'];
            $zu['livello'] = $row['livello'];
            $zu['nome'] = $row['nome'];
            $zu['zen'] = $row['zen'];
        }
    return $zu;
    }
}
?>